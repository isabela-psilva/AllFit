<?php

session_start();

include 'conexao.php';

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];


// Busca o treino do usuário

$sqlTreino = mysql_query("
    SELECT *
    FROM treinos
    WHERE id_usuario = '$id_usuario'
");


if (!$sqlTreino) {
    die("Erro ao buscar treino: " . mysql_error());
}


// Verifica se existe treino

if (mysql_num_rows($sqlTreino) > 0) {

    $treino = mysql_fetch_array($sqlTreino);

    $id_treino = $treino["id_treino"];

    $tipo = $treino["tipo_exercicio"];
    $horarioInicio = $treino["horario_inicio"];
    $horarioFim = $treino["horario_fim"];
    $duracao = $treino["duracao"];
    $local = $treino["local"];
    $diasTreino = $treino["dias_treino"];
    $diasTotal = $treino["dias_total"];

    $temTreino = true;

} else {

    $temTreino = false;

}


// Busca as dificuldades de mobilidade

$dificuldades = array();

if ($temTreino) {

    $sqlDificuldades = mysql_query("
        SELECT id_dificuldade
        FROM treino_dificuldade
        WHERE id_treino = '$id_treino'
    ");

    if (!$sqlDificuldades) {
        die("Erro ao buscar dificuldades: " . mysql_error());
    }

    while ($dificuldade = mysql_fetch_array($sqlDificuldades)) {

        $dificuldades[] = $dificuldade["id_dificuldade"];

    }

}


// Converte os dias armazenados no banco para um array

$dias = array();

if ($temTreino && $diasTreino != "") {

    $dias = explode(", ", $diasTreino);

}


// Nomes dos dias

$nomesDias = array(

    "segunda" => "Segunda-feira",
    "terca" => "Terça-feira",
    "quarta" => "Quarta-feira",
    "quinta" => "Quinta-feira",
    "sexta" => "Sexta-feira"

);


// Nome do local

$nomesLocal = array(

    "casa" => "Em casa",
    "academia" => "Academia",
    "ar_livre" => "Ao ar livre"

);

?>


<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Plano de Exercícios</title>

    <link
        rel="stylesheet"
        href="Base_Style.css"
    >

    <link
        rel="stylesheet"
        href="dashboardStyle.css"
    >

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

</head>


<body>


<section class="dashboard">


    <!-- MENU LATERAL -->

    <aside class="sidebar">


        <img
            class="logo-dash"
            src="logo 1.png"
            alt="AllFit"
        >


        <nav class="menu-dash">


            <a href="dashboard.php">

                <img
                    src="home.png"
                    alt=""
                >

                Início

            </a>


            <a href="perfil.php">

                <img
                    src="profile.png"
                    alt=""
                >

                Meu Perfil

            </a>


            <a href="dieta.php">

                <img
                    src="diet.png"
                    alt=""
                >

                Alimentação

            </a>


            <a href="rotina_exercicios.php">

                <img
                    src="dumbbell.png"
                    alt=""
                >

                Exercícios

            </a>


            <a href="configuracao.php">

                <img
                    src="settings.png"
                    alt=""
                >

                Configurações

            </a>


        </nav>


        <a
            class="sair-dash"
            href="index.html"
        >

            <img
                src="logout.png"
                alt=""
            >

            Sair

        </a>


    </aside>


    <!-- CONTEÚDO -->

    <div class="page-container">


        <main class="conteudo-dash">


            <!-- TOPO -->

            <div class="topo-dash">


                <div>


                    <div class="texto-topo">


                        <h1>
                            Seu Plano de Exercícios
                        </h1>


                        <p>
                            Treinos organizados conforme seus dados,
                            dias disponíveis e objetivo.
                        </p>


                        <button
                            class="secondary-button"
                            style="width: 150px; margin-top: 10px;"
                            onclick="window.location.href='exercicio.php'"
                        >
                            Editar meu plano
                        </button>


                    </div>


                </div>


                <div class="imagem-topo">

                    <img
                        src="dumbbell.png"
                        alt=""
                    >

                </div>


            </div>


            <?php if (!$temTreino) { ?>


                <!-- SEM TREINO -->

                <section
                    class="resumo-exercicio"
                >


                    <div class="dash-card">

                        <p>Tipo</p>

                        <h2>
                            Não definido
                        </h2>

                        <span>
                            Sem rotina
                        </span>

                    </div>


                    <div class="dash-card">

                        <p>Frequência</p>

                        <h2>
                            --
                        </h2>

                        <span>
                            Sem rotina
                        </span>

                    </div>


                    <div class="dash-card">

                        <p>Horário</p>

                        <h2>
                            --
                        </h2>

                        <span>
                            Sem rotina
                        </span>

                    </div>


                    <div class="dash-card">

                        <p>Local</p>

                        <h2>
                            --
                        </h2>

                        <span>
                            Sem rotina
                        </span>

                    </div>


                </section>


                <section class="area-treino">


                    <div
                        class="treino-card"
                        style="grid-column:1/-1;text-align:center;"
                    >


                        <h2>
                            Nenhum plano de exercícios cadastrado
                        </h2>


                        <p>
                            Crie sua rotina para receber
                            exercícios personalizados.
                        </p>


                        <br>


                        <a
                            href="exercicio.php"
                            class="primary-button"
                        >
                            Criar plano
                        </a>


                    </div>


                </section>


                <section class="orientacoes-treino">


                    <h2>
                        Orientações do plano
                    </h2>


                    <div class="orientacoes-grid">

                        <span>
                            Nenhuma orientação disponível.
                        </span>

                    </div>


                </section>


            <?php } else { ?>


                <!-- RESUMO DO TREINO -->

                <section class="resumo-exercicio">


                    <div class="dash-card">

                        <p>
                            Tipo
                        </p>

                        <h2>
                            <?php echo $tipo; ?>
                        </h2>

                        <span>
                            Treino principal
                        </span>

                    </div>


                    <div class="dash-card">

                        <p>
                            Frequência
                        </p>


                        <h2>
                            <?php echo count($dias); ?>x
                        </h2>


                        <span>

                            <?php

                            $diasFormatados = array();

                            foreach ($dias as $dia) {

                                if (isset($nomesDias[$dia])) {

                                    $diasFormatados[] =
                                        $nomesDias[$dia];

                                }

                            }

                            echo implode(", ", $diasFormatados);

                            ?>

                        </span>

                    </div>


                    <div class="dash-card">

                        <p>
                            Horário
                        </p>


                        <h2>

                            <?php
                            echo substr($horarioInicio, 0, 5);
                            ?>

                        </h2>


                        <span>

                            Até

                            <?php
                            echo substr($horarioFim, 0, 5);
                            ?>

                            •
                            <?php echo $duracao; ?> min

                        </span>

                    </div>


                    <div class="dash-card">

                        <p>
                            Local
                        </p>


                        <h2>

                            <?php

                            if (isset($nomesLocal[$local])) {

                                echo $nomesLocal[$local];

                            } else {

                                echo $local;

                            }

                            ?>

                        </h2>


                        <span>
                            Local escolhido
                        </span>

                    </div>


                </section>


                <!-- TREINOS -->

                <section class="area-treino">


                    <?php foreach ($dias as $dia) { ?>


                        <div class="treino-card">


                            <h2>

                                <?php

                                if (isset($nomesDias[$dia])) {

                                    echo $nomesDias[$dia];

                                } else {

                                    echo $dia;

                                }

                                ?>

                            </h2>


                            <h3>
                                <?php echo $tipo; ?>
                            </h3>


                            <?php


                            /*
                             * Exercícios temporários.
                             *
                             * Depois podemos criar uma tabela
                             * específica para os exercícios.
                             */


                            if (in_array(1, $dificuldades)) {

                                ?>


                                <div class="exercicio-item">

                                    <b>
                                        Caminhada leve
                                    </b>

                                    <span>
                                        20 minutos
                                    </span>

                                </div>


                                <div class="exercicio-item">

                                    <b>
                                        Alongamento
                                    </b>

                                    <span>
                                        10 minutos
                                    </span>

                                </div>


                            <?php

                            } else {

                                ?>


                                <div class="exercicio-item">

                                    <b>
                                        Agachamento
                                    </b>

                                    <span>
                                        3 séries • 12 repetições
                                    </span>

                                </div>


                                <div class="exercicio-item">

                                    <b>
                                        Abdominal
                                    </b>

                                    <span>
                                        3 séries • 15 repetições
                                    </span>

                                </div>


                                <div class="exercicio-item">

                                    <b>
                                        Alongamento
                                    </b>

                                    <span>
                                        10 minutos
                                    </span>

                                </div>


                            <?php } ?>


                        </div>


                    <?php } ?>


                </section>


                <!-- ORIENTAÇÕES -->

                <section class="orientacoes-treino">


                    <h2>
                        Orientações do plano
                    </h2>


                    <div class="orientacoes-grid">


                        <span>
                            Aquecimento de 10 minutos
                        </span>


                        <span>
                            Hidratação durante o treino
                        </span>


                        <span>
                            Alongamento após finalizar
                        </span>


                        <span>
                            Respeitar limites físicos
                        </span>


                        <span>
                            Manter frequência semanal
                        </span>


                    </div>


                </section>


            <?php } ?>


        </main>


    </div>


</section>


<footer class="footer-dash">

    <p>
        AllFit © 2026
    </p>

</footer>


</body>

</html>