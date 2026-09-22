<?php
    session_start();

    include 'conexao.php';

    if (!isset($_SESSION["id_usuario"])) {
        header("Location: login.php");
        exit;
    }

    $id_usuario = $_SESSION["id_usuario"];

    $mensagem = "";
    $titulo = "";

    if (isset($_GET["mensagem"])) {
        $mensagem = $_GET["mensagem"];
    }

    if (isset($_GET["titulo"])) {
        $titulo = $_GET["titulo"];
    }


    /*
     * Busca a dieta mais recente do usuário.
     */
    $sql_dieta = mysql_query("
        SELECT *
        FROM dietas
        WHERE id_usuario = '$id_usuario'
        ORDER BY id_dieta DESC
        LIMIT 1
    ");

    if (!$sql_dieta) {
        die("Erro ao buscar dieta: " . mysql_error());
    }


    /*
     * Verifica se o usuário possui uma dieta.
     */
    $tem_dieta = mysql_num_rows($sql_dieta) > 0;

    $dieta = null;

    if ($tem_dieta) {
        $dieta = mysql_fetch_assoc($sql_dieta);
    }


    /*
     * Busca os dados do perfil para calcular o objetivo.
     */
    $sql_perfil = mysql_query("
        SELECT idade, altura, peso
        FROM perfis
        WHERE id_usuario = '$id_usuario'
    ");

    if (!$sql_perfil) {
        die("Erro ao buscar perfil: " . mysql_error());
    }

    $perfil = null;

    if (mysql_num_rows($sql_perfil) > 0) {
        $perfil = mysql_fetch_assoc($sql_perfil);
    }


    /*
     * Calcula o objetivo com base no IMC.
     */
    $objetivo = "Não definido";

    if ($perfil != null && $perfil["peso"] != "" && $perfil["altura"] != "") {

        $peso = $perfil["peso"];
        $altura = $perfil["altura"];

        /*
         * Caso a altura esteja cadastrada em centímetros,
         * converte para metros.
         */
        if ($altura > 3) {
            $altura = $altura / 100;
        }

        if ($altura > 0) {

            $imc = $peso / ($altura * $altura);

            if ($imc < 18.5) {
                $objetivo = "Ganhar Massa";
            }
            else if ($imc < 25) {
                $objetivo = "Manutenção";
            }
            else {
                $objetivo = "Emagrecer";
            }
        }
    }


    /*
     * Busca as restrições da dieta.
     */
    $restricoes = array();

    if ($tem_dieta) {

        $id_dieta = $dieta["id_dieta"];

        $sql_restricoes = mysql_query("
            SELECT r.nome
            FROM restricoes r
            INNER JOIN dieta_restricao dr
                ON dr.id_restricao = r.id_restricao
            WHERE dr.id_dieta = '$id_dieta'
            ORDER BY r.nome
        ");

        if (!$sql_restricoes) {
            die("Erro ao buscar restrições: " . mysql_error());
        }

        while ($restricao = mysql_fetch_assoc($sql_restricoes)) {
            $restricoes[] = $restricao["nome"];
        }
    }


    /*
     * Monta o texto das restrições.
     */
    if (count($restricoes) == 0) {
        $texto_restricoes = "Nenhuma";
    }
    else {
        $texto_restricoes = implode(", ", $restricoes);
    }


    /*
     * Cardápio semanal.
     */
    $cardapio = array();

    if ($objetivo == "Emagrecer") {

        $cardapio = array(
            array("Segunda-feira", "Omelete com tomate", "Frango grelhado com arroz integral", "Banana com aveia", "Sopa de legumes"),
            array("Terça-feira", "Iogurte natural com frutas", "Peixe assado com batata doce", "Maçã", "Salada com ovos"),
            array("Quarta-feira", "Tapioca com queijo branco", "Carne magra com legumes", "Vitamina de banana", "Omelete verde"),
            array("Quinta-feira", "Pão integral com ovo", "Frango desfiado com mandioca", "Mix de frutas", "Creme de abóbora"),
            array("Sexta-feira", "Panqueca de banana", "Arroz, feijão e carne magra", "Iogurte com granola", "Salada proteica"),
            array("Sábado", "Cuscuz com ovo", "Macarrão integral com frango", "Suco natural e torrada", "Wrap saudável"),
            array("Domingo", "Frutas com aveia", "Prato livre equilibrado", "Castanhas e fruta", "Caldo leve")
        );

    }
    else if ($objetivo == "Ganhar Massa") {

        $cardapio = array(
            array("Segunda-feira", "Ovos mexidos e tapioca", "Arroz, feijão e frango", "Vitamina de banana", "Macarrão integral com carne"),
            array("Terça-feira", "Pão integral com queijo", "Carne com batata doce", "Iogurte com aveia", "Frango com legumes"),
            array("Quarta-feira", "Cuscuz com ovos", "Peixe, arroz e feijão", "Banana com pasta de amendoim", "Omelete reforçado"),
            array("Quinta-feira", "Panqueca de aveia", "Frango com mandioca", "Vitamina proteica", "Arroz integral com carne"),
            array("Sexta-feira", "Tapioca com frango", "Macarrão com atum", "Iogurte e granola", "Carne magra com batata"),
            array("Sábado", "Ovos e pão integral", "Frango, arroz e legumes", "Frutas e castanhas", "Wrap de frango"),
            array("Domingo", "Aveia com banana", "Prato livre equilibrado", "Vitamina de frutas", "Sopa com proteína")
        );

    }
    else {

        $cardapio = array(
            array("Segunda-feira", "Frutas com aveia", "Frango com arroz integral", "Iogurte natural", "Omelete com salada"),
            array("Terça-feira", "Pão integral com ovo", "Peixe com legumes", "Banana", "Sopa leve"),
            array("Quarta-feira", "Tapioca simples", "Carne magra com arroz", "Maçã", "Salada completa"),
            array("Quinta-feira", "Iogurte com frutas", "Frango grelhado", "Mix de castanhas", "Creme de legumes"),
            array("Sexta-feira", "Panqueca de banana", "Arroz, feijão e ovo", "Vitamina natural", "Wrap saudável"),
            array("Sábado", "Cuscuz com queijo", "Macarrão integral", "Torrada integral", "Omelete verde"),
            array("Domingo", "Frutas variadas", "Prato equilibrado", "Iogurte", "Caldo leve")
        );
    }


    /*
     * Receitas.
     */
    $receitas = array();

    if ($objetivo == "Ganhar Massa") {

        $receitas = array(
            array(
                "Vitamina Proteica",
                "Banana, aveia, leite e pasta de amendoim.",
                "520 kcal • 10 min",
                "https://images.unsplash.com/photo-1542444459-db63c0f3d7ea"
            ),
            array(
                "Macarrão Integral",
                "Macarrão integral com carne moída magra.",
                "650 kcal • 30 min",
                "https://images.unsplash.com/photo-1473093295043-cdd812d0e601"
            ),
            array(
                "Omelete Reforçado",
                "Ovos, queijo branco e frango desfiado.",
                "480 kcal • 15 min",
                "https://images.unsplash.com/photo-1525351484163-7529414344d8"
            )
        );

    }
    else if ($objetivo == "Emagrecer") {

        $receitas = array(
            array(
                "Salada Proteica",
                "Receita leve com ovos, folhas verdes e frango.",
                "320 kcal • 15 min",
                "https://images.unsplash.com/photo-1512621776951-a57141f2eefd"
            ),
            array(
                "Bowl Saudável",
                "Arroz integral, legumes, frango e molho natural.",
                "450 kcal • 25 min",
                "https://images.unsplash.com/photo-1546069901-ba9599a7e63c"
            ),
            array(
                "Café Energético",
                "Iogurte, frutas, aveia e sementes.",
                "280 kcal • 10 min",
                "https://images.unsplash.com/photo-1490645935967-10de6ba17061"
            )
        );

    }
    else {

        $receitas = array(
            array(
                "Prato Equilibrado",
                "Proteína, arroz integral e legumes.",
                "500 kcal • 25 min",
                "https://images.unsplash.com/photo-1546069901-ba9599a7e63c"
            ),
            array(
                "Omelete Verde",
                "Ovos com espinafre e salada.",
                "350 kcal • 15 min",
                "https://images.unsplash.com/photo-1525351484163-7529414344d8"
            ),
            array(
                "Frutas com Aveia",
                "Frutas naturais com aveia e sementes.",
                "300 kcal • 10 min",
                "https://images.unsplash.com/photo-1490645935967-10de6ba17061"
            )
        );
    }


    /*
     * Lista de compras.
     */
    if ($objetivo == "Ganhar Massa") {

        $compras = array(
            "Ovos",
            "Frango",
            "Arroz",
            "Feijão",
            "Banana",
            "Aveia",
            "Batata doce",
            "Macarrão integral"
        );

    }
    else if ($objetivo == "Emagrecer") {

        $compras = array(
            "Ovos",
            "Frango",
            "Arroz integral",
            "Banana",
            "Aveia",
            "Legumes",
            "Iogurte natural",
            "Batata doce"
        );

    }
    else {

        $compras = array(
            "Frutas",
            "Ovos",
            "Arroz integral",
            "Legumes",
            "Iogurte",
            "Frango",
            "Aveia",
            "Castanhas"
        );
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Plano Alimentar</title>

    <link rel="stylesheet" href="../estilos/Base_Style.css">
    <link rel="stylesheet" href="../estilos/dashboardStyle.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>

    <div class="pagina-dashboard">

        <section class="dashboard">

            <aside class="sidebar">

                <img class="logo-dash" src="../imagens/logo 1.png" alt="AllFit">

                <nav class="menu-dash">

                    <a href="../AllFit/dashboard.php">
                        <img src="../imagens/home.png" alt="">
                        Início
                    </a>

                    <a href="../AllFit/perfil.php">
                        <img src="../imagens/profile.png" alt="">
                        Meu Perfil
                    </a>

                    <a href="#" onclick="abrirDieta()">
                        <img src="../imagens/diet.png" alt="">
                        Alimentação
                    </a>

                    <a href="#" onclick="abrirExercicios()">
                        <img src="../imagens/dumbbell.png" alt="">
                        Exercícios
                    </a>

                    <a href="../AllFit/configuracao.php">
                        <img src="../imagens/settings.png" alt="">
                        Configurações
                    </a>

                </nav>

                <a class="sair-dash" href="../index.php">

                    <img src="../imagens/logout.png" alt="">
                    Sair

                </a>

            </aside>


            <div class="page-container">

                <main class="conteudo-dash">

                    <div class="topo-dash">

                        <div class="texto-topo">

                            <div>

                                <h1>Seu Plano Alimentar</h1>

                                <p>
                                    Dietas e receitas organizadas para sua semana.
                                </p>

                                <button
                                    class="secondary-button"
                                    style="width: 150px; margin-top: 10px;"
                                    onclick="window.location.href='../paginas/dieta.php?editar=true'">
                                    Editar meu plano
                                </button>

                            </div>

                        </div>

                        <div class="imagem-topo">

                            <img src="../imagens/diet.png" alt="">

                        </div>

                    </div>


                    <?php if (!$tem_dieta) { ?>

                        <section class="resumo-alimentar">

                            <div class="dash-card">

                                <p>Objetivo</p>
                                <h2>Não definido</h2>
                                <span>Sem plano ativo</span>

                            </div>


                            <div class="dash-card">

                                <p>Calorias diárias</p>
                                <h2>-- kcal</h2>
                                <span>Não definido</span>

                            </div>


                            <div class="dash-card">

                                <p>Restrições</p>
                                <h2>Não definido</h2>
                                <span>Não personalizado</span>

                            </div>


                            <div class="dash-card">

                                <p>Ciclo</p>
                                <h2>--</h2>
                                <span>Sem rotina</span>

                            </div>

                        </section>


                        <section class="area-plano" id="areaPlano">

                            <div
                                class="semana-card"
                                style="grid-column: 1 / -1; text-align:center;">

                                <h2>Nenhuma dieta cadastrada</h2>

                                <p>
                                    Você ainda não criou um plano alimentar.
                                </p>

                                <br>

                                <a
                                    href="../paginas/dieta.php"
                                    class="primary-button">
                                    Criar plano alimentar
                                </a>

                            </div>

                        </section>

                    <?php } else { ?>

                        <section class="resumo-alimentar">

                            <div class="dash-card">

                                <p>Objetivo</p>

                                <h2>
                                    <?php echo $objetivo; ?>
                                </h2>

                                <span>Plano ativo</span>

                            </div>


                            <div class="dash-card">

                                <p>Calorias diárias</p>

                                <h2>
                                    <?php echo $dieta["meta_calorias"]; ?> kcal
                                </h2>

                                <span>Meta sugerida</span>

                            </div>


                            <div class="dash-card">

                                <p>Restrições</p>

                                <h2>
                                    <?php echo $texto_restricoes; ?>
                                </h2>

                                <span>Personalizado</span>

                            </div>


                            <div class="dash-card">

                                <p>Ciclo</p>

                                <h2>
                                    <?php echo $dieta["ciclo"]; ?>
                                </h2>

                                <span>Rotina semanal</span>

                            </div>

                        </section>


                        <section class="area-plano" id="areaPlano">

                            <?php foreach ($cardapio as $dia) { ?>

                                <div class="semana-card">

                                    <h2>
                                        <?php echo $dia[0]; ?>
                                    </h2>


                                    <div class="comida-item">

                                        <b>Café</b>

                                        <span>
                                            <?php echo $dia[1]; ?>
                                        </span>

                                    </div>


                                    <div class="comida-item">

                                        <b>Almoço</b>

                                        <span>
                                            <?php echo $dia[2]; ?>
                                        </span>

                                    </div>


                                    <div class="comida-item">

                                        <b>Lanche</b>

                                        <span>
                                            <?php echo $dia[3]; ?>
                                        </span>

                                    </div>


                                    <div class="comida-item">

                                        <b>Jantar</b>

                                        <span>
                                            <?php echo $dia[4]; ?>
                                        </span>

                                    </div>


                                    <a
                                        href="#receitas"
                                        class="primary-button">
                                        Ver receitas
                                    </a>

                                </div>

                            <?php } ?>

                        </section>


                        <section id="receitas" class="receitas-area">

                            <h2>Receitas recomendadas</h2>


                            <div class="receitas-grid" id="receitasGrid">

                                <?php foreach ($receitas as $receita) { ?>

                                    <div class="receita-card">

                                        <img
                                            src="<?php echo $receita[3]; ?>"
                                            alt="<?php echo $receita[0]; ?>">

                                        <h3>
                                            <?php echo $receita[0]; ?>
                                        </h3>

                                        <p>
                                            <?php echo $receita[1]; ?>
                                        </p>

                                        <span>
                                            <?php echo $receita[2]; ?>
                                        </span>

                                    </div>

                                <?php } ?>

                            </div>

                        </section>


                        <section class="lista-compras">

                            <h2>Lista de compras da semana</h2>


                            <div class="compras-grid" id="comprasGrid">

                                <?php foreach ($compras as $item) { ?>

                                    <span>
                                        <?php echo $item; ?>
                                    </span>

                                <?php } ?>

                            </div>

                        </section>

                    <?php } ?>

                </main>

            </div>

        </section>


        <footer class="footer-dash">

            <p>AllFit © 2026</p>

        </footer>

    </div>


    <script>

        function abrirDieta() {

            <?php if ($tem_dieta) { ?>

                window.location.href = "../paginas/rotina_dieta.php";

            <?php } else { ?>

                window.location.href = "../paginas/dieta.php";

            <?php } ?>

        }


        function abrirExercicios() {

            window.location.href = "../paginas/rotinaExercicio.php";

        }

    </script>

</body>

</html>
