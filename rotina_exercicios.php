
<?php

session_start();

include 'conexao.php';

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = intval($_SESSION["id_usuario"]);

// ==========================================
// BUSCAR O TREINO DO USUÁRIO
// ==========================================

$sqlTreino = mysql_query("
    SELECT *
    FROM treinos
    WHERE id_usuario = '$id_usuario'
    ORDER BY id_treino DESC
    LIMIT 1
");

if (!$sqlTreino) {
    die("Erro ao buscar treino: " . mysql_error());
}

$temTreino = false;

if (mysql_num_rows($sqlTreino) > 0) {

    $treino = mysql_fetch_assoc($sqlTreino);

    $id_treino = $treino["id_treino"];

    $tipo = $treino["tipo_exercicio"];
    $horarioInicio = $treino["horario_inicio"];
    $horarioFim = $treino["horario_fim"];
    $duracao = $treino["duracao"];
    $local = $treino["local"];
    $diasTreino = $treino["dias_treino"];
    $diasTotal = $treino["dias_total"];

    $temTreino = true;

}

// ==========================================
// BUSCAR DIFICULDADES DE MOBILIDADE
// ==========================================

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

    while ($dificuldade = mysql_fetch_assoc($sqlDificuldades)) {

        $dificuldades[] = intval($dificuldade["id_dificuldade"]);

    }

}

// ==========================================
// CONVERTER OS DIAS DO TREINO
// ==========================================

$dias = array();

if ($temTreino && $diasTreino != "") {

    $dias = explode(", ", $diasTreino);

}

$nomesDias = array(

    "segunda" => "Segunda-feira",
    "terca" => "Terça-feira",
    "quarta" => "Quarta-feira",
    "quinta" => "Quinta-feira",
    "sexta" => "Sexta-feira"

);

// ==========================================
// NOMES DOS LOCAIS
// ==========================================

$nomesLocal = array(

    "casa" => "Em casa",
    "academia" => "Academia",
    "ar_livre" => "Ao ar livre"

);

// ==========================================
// ETAPAS DO TREINO
// ==========================================

$etapasTreino = array(

    "aquecimento" => "Aquecimento",
    "principal" => "Treino principal",
    "finalizacao" => "Finalização"

);

$iconesEtapas = array(

    "aquecimento" => "1",
    "principal" => "2",
    "finalizacao" => "3"

);

// ==========================================
// BUSCAR EXERCÍCIOS DO CATÁLOGO
// ==========================================

$exerciciosPorEtapa = array(

    "aquecimento" => array(),
    "principal" => array(),
    "finalizacao" => array()

);

if ($temTreino) {

    $tipoSQL = mysql_real_escape_string($tipo);
    $localSQL = mysql_real_escape_string($local);

    // Filtro das dificuldades de mobilidade

    $filtroDificuldades = "";

    if (!empty($dificuldades)) {

        $listaDificuldades = implode(
            ",",
            array_map("intval", $dificuldades)
        );

        $filtroDificuldades = "
            AND NOT EXISTS (
                SELECT 1
                FROM exercicio_dificuldade ed
                WHERE ed.id_exercicio = e.id_exercicio
                AND ed.id_dificuldade IN ($listaDificuldades)
            )
        ";

    }

    // Buscar exercícios compatíveis

    $sqlExercicios = mysql_query("
        SELECT *
        FROM exercicios e
        WHERE e.tipo_exercicio = '$tipoSQL'
        AND (
            e.local = '$localSQL'
            OR e.local = 'todos'
        )
        $filtroDificuldades
        ORDER BY e.etapa_treino, e.categoria, e.nome
    ");

    if (!$sqlExercicios) {
        die("Erro ao buscar exercícios: " . mysql_error());
    }

    // Separar por etapa e categoria

    while ($exercicio = mysql_fetch_assoc($sqlExercicios)) {

        $etapa = $exercicio["etapa_treino"];
        $categoria = $exercicio["categoria"];

        if (!isset($exerciciosPorEtapa[$etapa])) {
            continue;
        }

        if (!isset($exerciciosPorEtapa[$etapa][$categoria])) {

            $exerciciosPorEtapa[$etapa][$categoria] = array();

        }

        $exerciciosPorEtapa[$etapa][$categoria][] = $exercicio;

    }

}

// Verifica se existem exercícios compatíveis

$temExercicios = false;

foreach ($exerciciosPorEtapa as $categorias) {

    if (!empty($categorias)) {

        $temExercicios = true;
        break;

    }

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Plano de Exercícios</title>

    <link rel="stylesheet" href="Base_Style.css">
    <link rel="stylesheet" href="dashboardStyle.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        
        .etapa-treino {
            margin-top: 22px;
            padding: 18px;
            border-radius: 12px;
            background: transparent;
            border: 1px solid rgba(76, 175, 80, 0.25);
        }

        .etapa-treino h4 {
            margin-bottom: 15px;
            color: #4caf50;
            font-size: 18px;
        }

        .etapa-treino h5 {
            margin: 15px 0 10px;
            color: inherit;
            font-size: 15px;
        }

        .exercicio-item {
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 12px;
            background: rgba(76, 175, 80, 0.08);
            border-left: 4px solid #4caf50;
        }

        .exercicio-item b {
            display: block;
            margin-bottom: 6px;
            color: inherit;
        }

        .exercicio-item span {
            display: block;
            font-size: 13px;
            color: inherit;
            opacity: 0.8;
        }

        .exercicio-item p {
            margin-top: 8px;
            font-size: 14px;
            color: inherit;
            opacity: 0.85;
        }

        .sem-exercicios-etapa {
            font-size: 14px;
            color: inherit;
            opacity: 0.75;
        }

    </style>

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
                <img src="home.png" alt="">
                Início
            </a>

            <a href="perfil.php">
                <img src="profile.png" alt="">
                Meu Perfil
            </a>

            <a href="dieta.php">
                <img src="diet.png" alt="">
                Alimentação
            </a>

            <a href="rotina_exercicios.php">
                <img src="dumbbell.png" alt="">
                Exercícios
            </a>

            <a href="configuracao.php">
                <img src="settings.png" alt="">
                Configurações
            </a>

        </nav>

        <a class="sair-dash" href="index.html">

            <img src="logout.png" alt="">
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

                        <h1>Seu Plano de Exercícios</h1>

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

                    <img src="dumbbell.png" alt="">

                </div>

            </div>

            <?php if (!$temTreino) { ?>

                <!-- SEM TREINO -->

                <section class="resumo-exercicio">

                    <div class="dash-card">
                        <p>Tipo</p>
                        <h2>Não definido</h2>
                        <span>Sem rotina</span>
                    </div>

                    <div class="dash-card">
                        <p>Frequência</p>
                        <h2>--</h2>
                        <span>Sem rotina</span>
                    </div>

                    <div class="dash-card">
                        <p>Horário</p>
                        <h2>--</h2>
                        <span>Sem rotina</span>
                    </div>

                    <div class="dash-card">
                        <p>Local</p>
                        <h2>--</h2>
                        <span>Sem rotina</span>
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

                        <a href="exercicio.php" class="primary-button">
                            Criar plano
                        </a>

                    </div>

                </section>

                <section class="orientacoes-treino">

                    <h2>Orientações do plano</h2>

                    <div class="orientacoes-grid">

                        <span>Nenhuma orientação disponível.</span>

                    </div>

                </section>

            <?php } else { ?>

                <!-- RESUMO DO TREINO -->

                <section class="resumo-exercicio">

                    <div class="dash-card">

                        <p>Tipo</p>

                        <h2>
                            <?php echo htmlspecialchars($tipo); ?>
                        </h2>

                        <span>Treino principal</span>

                    </div>

                    <div class="dash-card">

                        <p>Frequência</p>

                        <h2>
                            <?php echo count($dias); ?>x
                        </h2>

                        <span>

                            <?php

                            $diasFormatados = array();

                            foreach ($dias as $dia) {

                                if (isset($nomesDias[$dia])) {

                                    $diasFormatados[] = $nomesDias[$dia];

                                }

                            }

                            echo htmlspecialchars(
                                implode(", ", $diasFormatados)
                            );

                            ?>

                        </span>

                    </div>

                    <div class="dash-card">

                        <p>Horário</p>

                        <h2>
                            <?php echo htmlspecialchars(substr($horarioInicio, 0, 5)); ?>
                        </h2>

                        <span>

                            Até

                            <?php echo htmlspecialchars(substr($horarioFim, 0, 5)); ?>

                            •
                            <?php echo htmlspecialchars($duracao); ?> min

                        </span>

                    </div>

                    <div class="dash-card">

                        <p>Local</p>

                        <h2>

                            <?php

                            if (isset($nomesLocal[$local])) {

                                echo $nomesLocal[$local];

                            } else {

                                echo htmlspecialchars($local);

                            }

                            ?>

                        </h2>

                        <span>Local escolhido</span>

                    </div>

                </section>

                <!-- TREINOS POR DIA -->

                <section class="area-treino">

                    <?php $indiceDia = 0; ?>

                    <?php foreach ($dias as $dia) { ?>

                        <div class="treino-card">

                            <h2>

                                <?php

                                if (isset($nomesDias[$dia])) {

                                    echo $nomesDias[$dia];

                                } else {

                                    echo htmlspecialchars($dia);

                                }

                                ?>

                            </h2>

                            <h3>
                                <?php echo htmlspecialchars($tipo); ?>
                            </h3>

                            <?php if ($temExercicios) { ?>

                                <!-- ETAPAS DO TREINO -->

                                <?php foreach ($etapasTreino as $etapa => $nomeEtapa) { ?>

                                    <div class="etapa-treino">

                                        <h4>

                                            <?php echo $iconesEtapas[$etapa]; ?>.

                                            <?php echo htmlspecialchars($nomeEtapa); ?>

                                        </h4>

                                        <?php

                                        $categoriasEtapa =
                                            $exerciciosPorEtapa[$etapa];

                                        ?>

                                        <?php if (!empty($categoriasEtapa)) { ?>

                                            <?php foreach (
                                                $categoriasEtapa
                                                as $categoria => $listaExercicios
                                            ) { ?>

                                                <h5>
                                                    <?php
                                                    echo htmlspecialchars($categoria);
                                                    ?>
                                                </h5>

                                                <?php

                                                $totalExercicios =
                                                    count($listaExercicios);

                                                // Exibir até dois exercícios por categoria

                                                $quantidadeExibir = min(
                                                    2,
                                                    $totalExercicios
                                                );

                                                // Variar os exercícios entre os dias

                                                $inicioCategoria = (
                                                    $indiceDia +
                                                    ($etapa == "principal" ? 1 : 0)
                                                ) % $totalExercicios;

                                                for (
                                                    $i = 0;
                                                    $i < $quantidadeExibir;
                                                    $i++
                                                ) {

                                                    $posicao = (
                                                        $inicioCategoria + $i
                                                    ) % $totalExercicios;

                                                    $exercicio =
                                                        $listaExercicios[$posicao];

                                                ?>

                                                    <div class="exercicio-item">

                                                        <b>

                                                            <?php
                                                            echo htmlspecialchars(
                                                                $exercicio["nome"]
                                                            );
                                                            ?>

                                                        </b>

                                                        <span>

                                                            <?php if (
                                                                $exercicio["duracao_minutos"] > 0
                                                            ) { ?>

                                                                <?php
                                                                echo intval(
                                                                    $exercicio["duracao_minutos"]
                                                                );
                                                                ?> minutos

                                                            <?php } ?>

                                                            <?php if (
                                                                $exercicio["series"] > 0
                                                            ) { ?>

                                                                •
                                                                <?php
                                                                echo intval(
                                                                    $exercicio["series"]
                                                                );
                                                                ?> séries

                                                            <?php } ?>

                                                            <?php if (
                                                                $exercicio["repeticoes"] > 0
                                                            ) { ?>

                                                                •
                                                                <?php
                                                                echo intval(
                                                                    $exercicio["repeticoes"]
                                                                );
                                                                ?> repetições

                                                            <?php } ?>

                                                        </span>

                                                        <?php if (
                                                            !empty($exercicio["descricao"])
                                                        ) { ?>

                                                            <p>

                                                                <?php
                                                                echo htmlspecialchars(
                                                                    $exercicio["descricao"]
                                                                );
                                                                ?>

                                                            </p>

                                                        <?php } ?>

                                                    </div>

                                                <?php } ?>

                                            <?php } ?>

                                        <?php } else { ?>

                                            <p class="sem-exercicios-etapa">

                                                Nenhum exercício disponível
                                                nesta etapa.

                                            </p>

                                        <?php } ?>

                                    </div>

                                <?php } ?>

                            <?php } else { ?>

                                <p>

                                    Não encontramos exercícios cadastrados
                                    compatíveis com as opções selecionadas.

                                </p>

                            <?php } ?>

                        </div>

                        <?php $indiceDia++; ?>

                    <?php } ?>

                </section>

                <!-- ORIENTAÇÕES -->

                <section class="orientacoes-treino">

                    <h2>Orientações do plano</h2>

                    <div class="orientacoes-grid">

                        <span>
                            Faça o aquecimento antes de iniciar o treino.
                        </span>

                        <span>
                            Mantenha-se hidratado durante a atividade.
                        </span>

                        <span>
                            Respeite seus limites físicos.
                        </span>

                        <span>
                            Interrompa a atividade se sentir dor ou desconforto.
                        </span>

                        <span>
                            Mantenha uma rotina de descanso.
                        </span>

                    </div>

                </section>

            <?php } ?>

        </main>

    </div>

</section>

<footer class="footer-dash">

    <p>AllFit © 2026</p>

</footer>

</body>

</html>
