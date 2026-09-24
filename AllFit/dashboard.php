<?php
    session_start();

    if (!isset($_SESSION["id_usuario"])) {
        header("Location: login.php");
        exit;
    }

    $id_usuario = $_SESSION["id_usuario"];

    include "conexao.php";


    // ==========================
    // BUSCAR PERFIL
    // ==========================

    $sql = mysql_query("
        SELECT *
        FROM perfis
        WHERE id_usuario = '$id_usuario'
    ");

    if (!$sql) {
        die("Erro na consulta: " . mysql_error());
    }

    $perfil = null;

    if (mysql_num_rows($sql) > 0) {
        $perfil = mysql_fetch_assoc($sql);
    }


    // ==========================
    // CALCULAR OBJETIVO
    // ==========================

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


    // ==========================
    // BUSCAR TREINO
    // ==========================

    $treino = null;

    $sqlTreino = mysql_query("
        SELECT *
        FROM treinos
        WHERE id_usuario = '$id_usuario'
    ");

    if (!$sqlTreino) {
        die("Erro na consulta de treino: " . mysql_error());
    }

    if (mysql_num_rows($sqlTreino) > 0) {
        $treino = mysql_fetch_assoc($sqlTreino);
    }


    // ==========================
    // DADOS DO TREINO
    // ==========================

    $dias = array();
    $quantidadeDias = 0;
    $primeiroDia = null;

    if ($treino != null) {

        if (!empty($treino["dias_treino"])) {

            $dias = explode(", ", $treino["dias_treino"]);

            $quantidadeDias = count($dias);

            $primeiroDia = $dias[0];
        }
    }


    // Nomes dos dias
    $nomesDias = array(
        "segunda" => "Segunda-feira",
        "terca" => "Terça-feira",
        "quarta" => "Quarta-feira",
        "quinta" => "Quinta-feira",
        "sexta" => "Sexta-feira"
    );
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


        <!-- ==========================
             MENU LATERAL
        =========================== -->

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


                <a href="configuracao.php">

                    <img src="settings.png" alt="">

                    Configurações

                </a>

            </nav>


            <a href = "index.html" class="sair-dash">

                <img src="logout.png" alt="">

                Sair

            </a>

        </aside>



        <!-- ==========================
             CONTEÚDO
        =========================== -->

        <div class="page-container">

            <main class="conteudo-dash">


                <!-- ==========================
                     TOPO
                =========================== -->

                <div class="topo-dash">

                    <div>

                        <h1>
                            Olá, <?php echo $_SESSION["nome_usuario"]; ?>! 👋
                        </h1>

                        <p>
                            Vamos alcançar seus objetivos juntos!
                        </p>

                    </div>


                    <div class="imagem-topo">

                        <img src="logo.png" alt="">

                    </div>


                    <?php if ($perfil == null) { ?>

                        <div id="define-profile" style="text-align: right;">

                            <h3>
                                Você ainda não definiu seu perfil!
                            </h3>

                            <button
                                class="primary-button"
                                onclick="window.location.href = 'perfil.php'">

                                Definir perfil

                            </button>

                        </div>

                    <?php } ?>


                </div>



                <!-- ==========================
                     CARDS
                =========================== -->

                <section class="cards-dash">


                    <!-- CARD IMC -->

                    <div class="dash-card" id="card-imc">

                        <p>IMC</p>


                        <?php if ($perfil == null) { ?>

                            <h2>
                                Não Definido
                            </h2>

                            <span>
                                Cadastre seu Perfil
                            </span>


                        <?php } else { ?>


                            <?php

                                $imc = $perfil["peso"] /
                                       ($perfil["altura"] * $perfil["altura"]);


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


                            <h2>

                                <?php
                                    echo number_format($imc, 2, ",", ".");
                                ?>

                            </h2>


                            <span>

                                <?php
                                    echo $biotipo;
                                ?>

                            </span>


                        <?php } ?>


                    </div>



                    <!-- CARD OBJETIVO -->

                    <div class="dash-card" id="card-obj">

                        <p>Objetivo</p>


                        <?php if ($objetivo == null) { ?>


                            <h2>
                                Não definido
                            </h2>

                            <span>
                                Cadastre seu Perfil
                            </span>


                        <?php } else { ?>


                            <h2>
                                <?php echo $objetivo; ?>
                            </h2>

                            <span>
                                Definido pelo IMC
                            </span>


                        <?php } ?>


                    </div>



                    <!-- CARD TREINOS -->

                    <div class="dash-card" id="card-treinoPorSemana">

                        <p>
                            Treinos / Semana
                        </p>


                        <?php if ($treino == null) { ?>


                            <h2>
                                Não definido
                            </h2>

                            <span>
                                Sem rotina
                            </span>


                        <?php } else { ?>


                            <h2>
                                <?php echo $quantidadeDias; ?>x/semana
                            </h2>

                            <span>
                                Rotina semanal
                            </span>


                        <?php } ?>


                    </div>



                    <!-- CARD PLANO -->

                    <div class="dash-card" id="card-planoAtual">

                        <p>
                            Plano Atual
                        </p>


                        <?php if ($treino == null) { ?>


                            <h2>
                                Não definido
                            </h2>

                            <span>
                                Sem plano ativo
                            </span>


                        <?php } else { ?>


                            <h2>
                                Exercício
                            </h2>

                            <span>
                                Treino ativo
                            </span>


                        <?php } ?>


                    </div>


                </section>



                <!-- ==========================
                     PARTE INFERIOR
                =========================== -->

                <section class="area-baixo">


                    <!-- PROGRESSO -->

                    <div class="painel">

                        <h1>
                            Seu progresso
                        </h1>


                        <div class="progresso-circulo">

                            <svg width="220" height="220">

                                <circle
                                    class="bg"
                                    cx="110"
                                    cy="110"
                                    r="90">
                                </circle>


                                <circle
                                    class="progress"
                                    cx="110"
                                    cy="110"
                                    r="90">
                                </circle>

                            </svg>


                            <span id="progressoTexto">
                                0%
                            </span>

                        </div>


                        <?php if ($perfil == null || $treino == null) { ?>

                            <p id="textoProgresso">

                                Crie seu perfil e uma rotina para acompanhar seu progresso.

                            </p>


                        <?php } else { ?>


                            <p id="textoProgresso">

                                Muito bem! Você está no caminho certo para alcançar seus objetivos.

                            </p>


                        <?php } ?>


                    </div>



                    <!-- PRÓXIMO TREINO -->

                    <div class="painel">

                        <h1>
                            Próximo treino
                        </h1>


                        <?php if ($treino == null) { ?>


                            <img
                                class="img-treino"
                                src="treino_perna.avif"
                                alt="Treino"
                                style="display:none;">


                            <h2>
                                Não definido
                            </h2>


                            <p>
                                Crie uma rotina de exercícios.
                            </p>


                        <?php } else { ?>


                            <img
                                class="img-treino"
                                src="treino_perna.avif"
                                alt="Treino">


                            <h2>

                                Treino de

                                <?php

                                    if (isset($nomesDias[$primeiroDia])) {

                                        echo $nomesDias[$primeiroDia];

                                    } else {

                                        echo $primeiroDia;

                                    }

                                ?>

                            </h2>


                            <p>

                                Horário:

                                <?php
                                    echo substr(
                                        $treino["horario_inicio"],
                                        0,
                                        5
                                    );
                                ?>

                            </p>


                        <?php } ?>


                    </div>


                </section>


            </main>

        </div>

    </section>



    <!-- ==========================
         MODAL
    =========================== -->

    <div
        id="meuModal"
        class="modal-container"
        style="display: none;">

        <div class="modal-box">

            <h2
                id="modalTitulo"
                style="margin-top: 0;">

                Título

            </h2>


            <br>


            <p></p>


            <p id="modalTexto">

                Mensagem da operação.

            </p>


            <input
                type="button"
                id="btnModalOk"
                class="primary-button"
                value="Ok">

        </div>

    </div>



    <!-- ==========================
         RODAPÉ
    =========================== -->

    <footer class="footer-dash">

        <p>
            AllFit © 2026
        </p>

    </footer>



    <!-- ==========================
         JAVASCRIPT
    =========================== -->

    <script>

        /*
        ========================================
        PROGRESSO
        ========================================
        */

        function atualizarProgresso(valor) {

            const progress =
                document.querySelector(".progress");

            const texto =
                document.getElementById("progressoTexto");


            const radius = 90;

            const circumference =
                2 * Math.PI * radius;


            let porcentagem =
                Number(valor);


            if (porcentagem < 0) {

                porcentagem = 0;

            }


            if (porcentagem > 100) {

                porcentagem = 100;

            }


            progress.style.strokeDasharray =
                circumference;


            progress.style.strokeDashoffset =
                circumference;


            if (porcentagem > 0) {

                progress.style.strokeDashoffset =
                    circumference -
                    (porcentagem / 100) *
                    circumference;

            }


            texto.innerText =
                porcentagem + "%";

        }



        /*
        ========================================
        AO CARREGAR A PÁGINA
        ========================================
        */

        window.onload = function() {

            <?php if ($treino != null) { ?>

                atualizarProgresso(65);

            <?php } else { ?>

                atualizarProgresso(0);

            <?php } ?>

        };



        /*
        ========================================
        ABRIR DIETA
        ========================================
        */

        function abrirDieta() {

            <?php if ($treino != null) { ?>
            // Não precisa fazer nada aqui relacionado à dieta.
            <?php } ?>

            <?php
                $sqlDieta = mysql_query("
                        SELECT id_dieta
                        FROM dietas
                        WHERE id_usuario = '$id_usuario'
                        LIMIT 1
                        ");

                $temDieta = false;

                if ($sqlDieta && mysql_num_rows($sqlDieta) > 0) {
                    $temDieta = true;
                }
            ?>

            <?php if ($temDieta) { ?>

            window.location.href = "rotina_dieta.php";

            <?php } else { ?>

            window.location.href = "dieta.php";

            <?php } ?>

        }



        /*
        ========================================
        ABRIR EXERCÍCIOS
        ========================================
        */

        function abrirExercicios() {

            <?php if ($treino != null) { ?>

                window.location.href =
                    "rotina_exercicios.php";

            <?php } else { ?>

                window.location.href =
                    "exercicio.php";

            <?php } ?>

        }

    </script>


</body>

</html>