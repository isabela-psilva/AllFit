<?php
    session_start();

    if (!isset($_SESSION["id_usuario"])) {
        header("Location: login.php");
        exit;
    }

    $id_usuario = $_SESSION["id_usuario"];

    include "conexao.php";

    $sql = mysql_query("Select * From perfis
                        Where id_usuario = '$id_usuario'");

    if (!$sql) {
        die("Erro na consulta: " . mysql_error());
    }

    $perfil = null;

    if (mysql_num_rows($sql) > 0) {
        $perfil = mysql_fetch_assoc($sql);
    }

    $objetivo = null;

    if ($perfil != null) {
        $imc = $perfil["peso"] / ($perfil["altura"] * $perfil["altura"]);

        if ($imc < 18.5) {
            $objetivo = "Ganhar Massa";
        }
        elseif ($imc < 25) {
            $objetivo = "Melhorar Alimentação";
        }
        else {
            $objetivo = "Emagrecer";
        }
    }
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="Base_Style.css">
    <link rel="stylesheet" href="dashboardStyle.css">
    <script src="script.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <section class="dashboard">

        <aside class="sidebar">
            <img class="logo-dash" src="logo 1.png" alt="AllFit">

            <nav class="menu-dash">
                <a href="dashboard.php">
                    <img src="home.png" alt="">
                    Início
                </a>
                <a href="perfil.php">
                    <img src="profile.png" alt="">
                    Meu Perfil
                </a>
                <a href="#" onclick="abrirDieta()">
                    <img src="diet.png" alt="">
                    Alimentação
                </a>
                <a href="#" onclick="abrirExercicios()">
                    <img src="dumbbell.png" alt="">
                    Exercícios
                </a>
                <a href="configuracao.html">
                    <img src="settings.png" alt="">
                    Configurações
                </a>
            </nav>

            <a onclick="fazerLogout()" class="sair-dash">
                <img src="logout.png" alt="">
                Sair
            </a>
        </aside>
        <div class="page-container">
            <main class="conteudo-dash">

                <div class="topo-dash">
                    <div>
                        <h1>Olá, <?php echo $_SESSION["nome_usuario"]; ?>! 👋</h1>
                        <p>Vamos alcançar seus objetivos juntos!</p>
                    </div>

                    <div class="imagem-topo">
                        <img src="logo.png" alt="">
                    </div>

                    <div id="define-profile" style="text-align: right; display: none;">
                        <h3>Você ainda não definiu seu perfil!</h3>
                        <button class="primary-button" onclick="window.location.href = 'perfil.php'">Definir perfil</button>
                    </div>
                </div>

                <section class="cards-dash">
                    <div class="dash-card" id="card-imc">
                        <p>IMC</p>

                        <?php if ($perfil == null) { ?>
                            <h2>Não Definido</h2>
                            <span>Cadastre seu Perfil</span>
                        <?php }
                        else { ?>
                            <?php
                            $imc = $perfil["peso"] / ($perfil["altura"] * $perfil["altura"]);

                            if ($imc < 18.5) {
                                $biotipo = "Abaixo do peso";
                            }
                            elseif ($imc < 25) {
                                $biotipo = "Peso Normal";
                            }
                            elseif ($imc < 30) {
                                $biotipo = "Sobrepeso";
                            }
                            elseif ($imc < 35) {
                                $biotipo = "Obesidade Grau I";
                            }
                            elseif ($imc < 40) {
                                $biotipo = "Obesidade Grau II";
                            }
                            else {
                                $biotipo = "Obesidade Grau III";
                            }
                            ?>

                            <h2><?php echo number_format($imc, 2, ",", ","); ?></h2>
                            <span><?php echo $biotipo; ?></span>
                        <?php } ?>
                        
                    </div>

                    <div class="dash-card" id="card-obj"> 
                        <p>Objetivo</p> 

                        <?php if ($objetivo == null) { ?>

                            <h2>Não definido</h2>
                            <span>Cadastre seu Perfil</span>

                        <?php } else { ?>

                            <h2><?php echo $objetivo; ?></h2>
                            <span>Definido pelo IMC</span>

                        <?php } ?>

                    </div>

                    <div class="dash-card" id="card-treinoPorSemana">
                        <p>Treinos / Semana</p>
                        <h2>4</h2>
                        <span>Rotina semanal</span>
                    </div>

                    <div class="dash-card" id="card-planoAtual">
                        <p>Plano Atual</p>
                        <h2>Completo</h2>
                        <span>Dieta + treino</span>
                    </div>
                </section>

                <section class="area-baixo">
                    <div class="painel">
                        <h1>Seu progresso</h1>
                        <div class="progresso-circulo">
                            <svg width="220" height="220">
                                <circle class="bg" cx="110" cy="110" r="90"></circle>
                                <circle class="progress" cx="110" cy="110" r="90"></circle>
                            </svg>

                            <span id="progressoTexto">0%</span>
                        </div>
                        <p id="textoProgresso">
                            Crie seu perfil e uma rotina para acompanhar seu progresso.
                        </p>
                    </div>
                    <div class="painel">
                        <h1>Próximo treino</h1>
                        <img class="img-treino" src="treino_perna.avif" alt="Treino" style="display:none;">
                        <h2>Não definido</h2>
                        <p>Crie uma rotina de exercícios.</p>
                    </div>

                </section>

            </main>
        </div>
    </section>

    <div id="meuModal" class="modal-container" style="display: none;">
        <div class="modal-box">
            <h2 id="modalTitulo" style="margin-top: 0;">Título</h2>
            <br><p></p>
            <p id="modalTexto">Mensagem da operação.</p>
            <input type="button" id="btnModalOk" class="primary-button" value="Ok">
        </div>
    </div>
    <footer class="footer-dash">
        <p>AllFit © 2026</p>
    </footer>
    <script>
        const usuarioLogadoEmail = localStorage.getItem("usuarioLogado");
        
        const dieta = usuarioLogadoEmail ? JSON.parse(localStorage.getItem(`dieta_${usuarioLogadoEmail}`)) : null;
        const treino = usuarioLogadoEmail ? JSON.parse(localStorage.getItem(`treino_${usuarioLogadoEmail}`)) : null;

        window.onload = () => {

            atualizarCards();
            atualizarPaineis();
        };


        function atualizarCards() {
            if (<?php echo $perfil == null ? "true" : "false"; ?>) {

                document.getElementById("define-profile").style.display = "block";

                document.querySelector("#card-imc h2").innerText = "Não definido";
                document.querySelector("#card-imc span").innerText = "Cadastre seu perfil";
            } 
            else {

                document.getElementById("define-profile").style.display = "none";
            }

            if (!treino) {
                document.querySelector("#card-treinoPorSemana h2").innerText = "Não definido";
                document.querySelector("#card-treinoPorSemana span").innerText = "Sem rotina";
            }  
            else {
                document.querySelector("#card-treinoPorSemana h2").innerText = treino.dias.length + "x/semana";
                document.querySelector("#card-treinoPorSemana span").innerText = "Rotina semanal";
            }

            if (!dieta && !treino) {
                document.querySelector("#card-planoAtual h2").innerText = "Não definido";
                document.querySelector("#card-planoAtual span").innerText = "Sem plano ativo";
            } 
            else if (dieta && treino) {
                document.querySelector("#card-planoAtual h2").innerText = "Completo";
                document.querySelector("#card-planoAtual span").innerText = "Dieta + treino";
            } 
            else if (dieta) {
                document.querySelector("#card-planoAtual h2").innerText = "Dieta";
                document.querySelector("#card-planoAtual span").innerText = "Plano alimentar ativo";
            } 
            else {
                document.querySelector("#card-planoAtual h2").innerText = "Exercício";
                document.querySelector("#card-planoAtual span").innerText = "Treino ativo";
            }
        }

        function atualizarPaineis() {
            const paineis = document.querySelectorAll(".painel");

            const temTreino =
            treino &&
            treino.dias &&
            treino.dias.length > 0 &&
            treino.horarioInicio;

            if (!temTreino) {
                atualizarProgresso(0);

                document.getElementById("textoProgresso").innerText =
                "Crie seu perfil e uma rotina para acompanhar seu progresso.";

                paineis[1].querySelector("img").style.display = "none";
                paineis[1].querySelector("h2").innerText = "Não definido";
                paineis[1].querySelector("p").innerText = "Crie uma rotina de exercícios.";

                return;
            }

            atualizarProgresso(65);

            document.getElementById("textoProgresso").innerText =
            "Muito bem! Você está no caminho certo para alcançar seus objetivos.";

            paineis[1].querySelector("img").style.display = "block";

            const primeiroDia = treino.dias[0] || "segunda";

            paineis[1].querySelector("h2").innerText =
            "Treino de " + formatarDia(primeiroDia);

            paineis[1].querySelector("p").innerText =
            "Hoje - " + treino.horarioInicio;
        }

        function classificarIMC(imc) {
            if (imc < 18.5) return "Abaixo do peso";
            if (imc < 25) return "Peso normal";
            if (imc < 30) return "Sobrepeso";
            if (imc < 35) return "Obesidade Grau I";
            if (imc < 40) return "Obesidade Grau II";
            return "Obesidade Grau III";
        }

        function formatarDia(dia) {
            const nomes = {
            segunda: "Segunda-feira",
            terca: "Terça-feira",
            quarta: "Quarta-feira",
            quinta: "Quinta-feira",
            sexta: "Sexta-feira"
        };

        return nomes[dia] || dia;
    }

    function atualizarProgresso(valor) {
        const progress = document.querySelector(".progress");
        const texto = document.getElementById("progressoTexto");

        const radius = 90;
        const circumference = 2 * Math.PI * radius;

        let porcentagem = Number(valor);

        if (porcentagem < 0) porcentagem = 0;
        if (porcentagem > 100) porcentagem = 100;

        progress.style.strokeDasharray = circumference;
        progress.style.strokeDashoffset = circumference;

        if (porcentagem > 0) {
            progress.style.strokeDashoffset =
            circumference - (porcentagem / 100) * circumference;
        }

        texto.innerText = porcentagem + "%";
    }
    function abrirDieta() {
        const emailLogado = localStorage.getItem("usuarioLogado");
        const dieta = localStorage.getItem(`dieta_${emailLogado}`);

        if (dieta) {
            window.location.href = "../paginas/rotina_dieta.html";
        } else {
            window.location.href = "../paginas/dieta.html";
        }
    }

    function abrirExercicios() {
        const emailLogado = localStorage.getItem("usuarioLogado");
        const treino = localStorage.getItem(`treino_${emailLogado}`);

        if (treino) {
            window.location.href = "../paginas/rotina_exercicios.html";
        } else {
            window.location.href = "../paginas/rotinaExercicio.html";
        }
    }
    </script>
</body>
</html>