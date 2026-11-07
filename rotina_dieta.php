<?php
    session_start();

    include 'conexao.php';

    if (!isset($_SESSION["id_usuario"])) {
        header("Location: login.php");
        exit;
    }

    $id_usuario = (int) $_SESSION["id_usuario"];

    $mensagem = isset($_GET["mensagem"]) ? $_GET["mensagem"] : "";
    $titulo = isset($_GET["titulo"]) ? $_GET["titulo"] : "";


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

    $tem_dieta = mysql_num_rows($sql_dieta) > 0;

    $dieta = null;
    $id_dieta = 0;

    if ($tem_dieta) {
        $dieta = mysql_fetch_assoc($sql_dieta);
        $id_dieta = (int) $dieta["id_dieta"];
    }


    /*
     * Busca os dados do perfil para calcular o IMC.
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
    $id_objetivo = 0;
    $imc = 0;

    if (
        $perfil != null &&
        $perfil["peso"] != "" &&
        $perfil["altura"] != "" &&
        $perfil["peso"] > 0 &&
        $perfil["altura"] > 0
    ) {

        $peso = $perfil["peso"];
        $altura = $perfil["altura"];

        if ($altura > 3) {
            $altura = $altura / 100;
        }

        if ($altura > 0) {

            $imc = $peso / ($altura * $altura);

            if ($imc < 18.5) {
                $id_objetivo = 2;
            }
            else if ($imc < 25) {
                $id_objetivo = 1;
            }
            else {
                $id_objetivo = 3;
            }

            $sql_objetivo = mysql_query("
                SELECT nome
                FROM objetivos
                WHERE id_objetivo = '$id_objetivo'
            ");

            if (!$sql_objetivo) {
                die("Erro ao buscar objetivo: " . mysql_error());
            }

            if (mysql_num_rows($sql_objetivo) > 0) {

                $dados_objetivo = mysql_fetch_assoc($sql_objetivo);

                $objetivo = $dados_objetivo["nome"];
            }
        }
    }


    /*
     * Busca as restrições alimentares da dieta.
     */
    $restricoes = array();
    $ids_restricoes = array();

    if ($tem_dieta) {

        $sql_restricoes = mysql_query("
            SELECT
                ra.id_restricao,
                ra.nome
            FROM dieta_restricao dr
            INNER JOIN restricoes_alimentares ra
                ON dr.id_restricao = ra.id_restricao
            WHERE dr.id_dieta = '$id_dieta'
        ");

        if (!$sql_restricoes) {
            die("Erro ao buscar restrições: " . mysql_error());
        }

        while ($restricao = mysql_fetch_assoc($sql_restricoes)) {

            $ids_restricoes[] = (int) $restricao["id_restricao"];

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
     * Tipos de refeição e dias da semana.
     */
    $tipos_refeicao = array(
        "Café da manhã",
        "Almoço",
        "Lanche",
        "Jantar"
    );

    $dias_semana = array(
        "Segunda-feira",
        "Terça-feira",
        "Quarta-feira",
        "Quinta-feira",
        "Sexta-feira",
        "Sábado",
        "Domingo"
    );


    /*
     * Busca receitas compatíveis com o objetivo
     * e com as restrições alimentares.
     */
    $receitas = array();
    $receitas_por_tipo = array();

    if ($tem_dieta && $id_objetivo > 0) {

        $filtro_restricoes = "";

        if (count($ids_restricoes) > 0) {

            $lista_ids = implode(
                ",",
                array_map("intval", $ids_restricoes)
            );

            $filtro_restricoes = "
                AND NOT EXISTS (
                    SELECT 1
                    FROM receita_restricao rr
                    WHERE rr.id_receita = r.id_receita
                    AND rr.id_restricao IN ($lista_ids)
                )
            ";
        }

        $sql_receitas = mysql_query("
            SELECT DISTINCT
                r.id_receita,
                r.nome,
                r.descricao,
                r.calorias,
                r.tempo_preparo,
                r.tipo_refeicao,
                r.imagem
            FROM receitas r

            INNER JOIN receita_objetivo ro
                ON r.id_receita = ro.id_receita

            WHERE ro.id_objetivo = '$id_objetivo'

            $filtro_restricoes

            ORDER BY r.tipo_refeicao, r.nome
        ");

        if (!$sql_receitas) {
            die("Erro ao buscar receitas: " . mysql_error());
        }

        while ($receita = mysql_fetch_assoc($sql_receitas)) {

            $receitas[] = $receita;

            $tipo = $receita["tipo_refeicao"];

            if (!isset($receitas_por_tipo[$tipo])) {
                $receitas_por_tipo[$tipo] = array();
            }

            $receitas_por_tipo[$tipo][] = $receita;
        }
    }


    /*
     * Processa a troca de uma receita.
     * Atualiza somente a refeição escolhida.
     */
    if (
        isset($_POST["acao"]) &&
        $_POST["acao"] == "trocar_receita" &&
        $tem_dieta
    ) {

        $id_cardapio = isset($_POST["id_cardapio"])
            ? (int) $_POST["id_cardapio"]
            : 0;

        $id_nova_receita = isset($_POST["id_receita"])
            ? (int) $_POST["id_receita"]
            : 0;


        /*
         * Confere se a refeição pertence
         * à dieta do usuário.
         */
        $sql_item = mysql_query("
            SELECT
                cd.id_cardapio,
                cd.tipo_refeicao
            FROM cardapio_dieta cd
            INNER JOIN dietas d
                ON cd.id_dieta = d.id_dieta
            WHERE cd.id_cardapio = '$id_cardapio'
            AND cd.id_dieta = '$id_dieta'
            AND d.id_usuario = '$id_usuario'
            LIMIT 1
        ");

        if (!$sql_item) {
            die("Erro ao localizar refeição: " . mysql_error());
        }

        if (mysql_num_rows($sql_item) > 0) {

            $item = mysql_fetch_assoc($sql_item);
            $tipo_item = $item["tipo_refeicao"];

            $receita_permitida = false;

            if (isset($receitas_por_tipo[$tipo_item])) {

                foreach ($receitas_por_tipo[$tipo_item] as $opcao) {

                    if ((int) $opcao["id_receita"] == $id_nova_receita) {

                        $receita_permitida = true;
                        break;
                    }
                }
            }

            if ($receita_permitida) {

                $sql_atualizar = mysql_query("
                    UPDATE cardapio_dieta
                    SET id_receita = '$id_nova_receita'
                    WHERE id_cardapio = '$id_cardapio'
                    AND id_dieta = '$id_dieta'
                ");

                if (!$sql_atualizar) {
                    die("Erro ao trocar receita: " . mysql_error());
                }

                header(
                    "Location: rotina_dieta.php?titulo=" .
                    urlencode("Receita atualizada") .
                    "&mensagem=" .
                    urlencode("Sua refeição foi alterada com sucesso!")
                );

                exit;

            }
            else {

                header(
                    "Location: rotina_dieta.php?titulo=" .
                    urlencode("Atenção") .
                    "&mensagem=" .
                    urlencode("Essa receita não está disponível para essa refeição.")
                );

                exit;
            }

        }
        else {

            header(
                "Location: rotina_dieta.php?titulo=" .
                urlencode("Atenção") .
                "&mensagem=" .
                urlencode("Não foi possível localizar essa refeição.")
            );

            exit;
        }
    }


    /*
     * Gera e salva o cardápio somente quando
     * ainda não existem refeições cadastradas.
     */
    if ($tem_dieta && $id_objetivo > 0) {

        $tipos_disponiveis = true;

        foreach ($tipos_refeicao as $tipo) {

            if (
                !isset($receitas_por_tipo[$tipo]) ||
                count($receitas_por_tipo[$tipo]) == 0
            ) {
                $tipos_disponiveis = false;
            }
        }

        if ($tipos_disponiveis) {

            $listas_aleatorias = $receitas_por_tipo;

            foreach ($listas_aleatorias as $tipo => $lista) {
                shuffle($listas_aleatorias[$tipo]);
            }

            foreach ($dias_semana as $indice_dia => $dia) {

                foreach ($tipos_refeicao as $tipo) {

                    $dia_sql = mysql_real_escape_string($dia);
                    $tipo_sql = mysql_real_escape_string($tipo);

                    $sql_existente = mysql_query("
                        SELECT id_cardapio
                        FROM cardapio_dieta
                        WHERE id_dieta = '$id_dieta'
                        AND dia_semana = '$dia_sql'
                        AND tipo_refeicao = '$tipo_sql'
                        LIMIT 1
                    ");

                    if (!$sql_existente) {
                        die("Erro ao verificar cardápio: " . mysql_error());
                    }

                    if (mysql_num_rows($sql_existente) == 0) {

                        $lista = $listas_aleatorias[$tipo];

                        $quantidade = count($lista);

                        $posicao = $indice_dia % $quantidade;

                        $receita_escolhida = $lista[$posicao];

                        $id_receita_escolhida = (int) $receita_escolhida["id_receita"];

                        $sql_inserir = mysql_query("
                            INSERT INTO cardapio_dieta (
                                id_dieta,
                                dia_semana,
                                tipo_refeicao,
                                id_receita
                            )
                            VALUES (
                                '$id_dieta',
                                '$dia_sql',
                                '$tipo_sql',
                                '$id_receita_escolhida'
                            )
                        ");

                        if (!$sql_inserir) {
                            die("Erro ao salvar cardápio: " . mysql_error());
                        }
                    }
                }
            }
        }
    }


    /*
     * Busca o cardápio salvo no banco.
     */
    $cardapio_salvo = array();

    if ($tem_dieta) {

        $sql_cardapio = mysql_query("
            SELECT
                cd.id_cardapio,
                cd.dia_semana,
                cd.tipo_refeicao,
                cd.id_receita,
                r.nome,
                r.descricao,
                r.calorias,
                r.tempo_preparo
            FROM cardapio_dieta cd
            INNER JOIN receitas r
                ON cd.id_receita = r.id_receita
            WHERE cd.id_dieta = '$id_dieta'
            ORDER BY cd.id_cardapio
        ");

        if (!$sql_cardapio) {
            die("Erro ao buscar cardápio salvo: " . mysql_error());
        }

        while ($item = mysql_fetch_assoc($sql_cardapio)) {

            $dia = $item["dia_semana"];
            $tipo = $item["tipo_refeicao"];

            $cardapio_salvo[$dia][$tipo] = $item;
        }
    }


    /*
     * Lista de compras temporária.
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


<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Plano Alimentar</title>

    <link rel="stylesheet" href="Base_Style.css">
    <link rel="stylesheet" href="dashboardStyle.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <style>

        /*
         * Estilo dos modais.
         */
        .modal-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.65);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            padding: 15px;
            box-sizing: border-box;
        }

        .modal-box {
            background: #ffffff;
            width: 100%;
            max-width: 450px;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.25);
            box-sizing: border-box;
        }

        .modal-box h2 {
            margin-bottom: 12px;
            color: #222222;
        }

        .modal-box p {
            margin-bottom: 20px;
            color: #555555;
        }

        .modal-box select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cccccc;
            border-radius: 8px;
            font-size: 14px;
            margin-top: 10px;
        }

        .modal-botoes {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 22px;
            flex-wrap: wrap;
        }

        .modal-botoes button {
            cursor: pointer;
        }

        .comida-item .secondary-button {
            cursor: pointer;
        }

        .modal-sucesso .modal-box {
            border-top: 5px solid #269B55;
        }

        .icone-sucesso {
            width: 55px;
            height: 55px;
            background: #e5f5eb;
            color: #269B55;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: bold;
            margin: 0 auto 15px;
        }

    </style>

</head>


<body>

    <div class="pagina-dashboard">

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

                    <a href="#" onclick="abrirDieta(); return false;">
                        <img src="diet.png" alt="">
                        Alimentação
                    </a>

                    <a href="#" onclick="abrirExercicios(); return false;">
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
                                    onclick="window.location.href='dieta.php?editar=true'">

                                    Editar meu plano

                                </button>

                            </div>

                        </div>

                        <div class="imagem-topo">
                            <img src="diet.png" alt="">
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

                                <a href="dieta.php" class="primary-button">
                                    Criar plano alimentar
                                </a>

                            </div>

                        </section>


                    <?php } else { ?>


                        <section class="resumo-alimentar">

                            <div class="dash-card">

                                <p>Objetivo</p>

                                <h2>
                                    <?php echo htmlspecialchars($objetivo, ENT_QUOTES, "UTF-8"); ?>
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
                                    <?php echo htmlspecialchars($texto_restricoes, ENT_QUOTES, "UTF-8"); ?>
                                </h2>

                                <span>Personalizado</span>

                            </div>


                            <div class="dash-card">

                                <p>Ciclo</p>

                                <h2>
                                    <?php echo htmlspecialchars($dieta["ciclo"], ENT_QUOTES, "UTF-8"); ?>
                                </h2>

                                <span>Rotina semanal</span>

                            </div>

                        </section>


                        <?php if ($id_objetivo == 0) { ?>

                            <section class="area-plano">

                                <div class="semana-card" style="grid-column: 1 / -1; text-align:center;">

                                    <h2>Perfil incompleto</h2>

                                    <p>
                                        Para montar seu cardápio, complete seu perfil
                                        com peso e altura.
                                    </p>

                                    <br>

                                    <a href="perfil.php" class="primary-button">
                                        Completar perfil
                                    </a>

                                </div>

                            </section>


                        <?php } else if (count($receitas) == 0) { ?>


                            <section class="area-plano">

                                <div class="semana-card" style="grid-column: 1 / -1; text-align:center;">

                                    <h2>Nenhuma receita compatível</h2>

                                    <p>
                                        Não encontramos receitas para seu objetivo
                                        e suas restrições alimentares.
                                    </p>

                                </div>

                            </section>


                        <?php } else { ?>


                            <section class="area-plano" id="areaPlano">

                                <?php foreach ($dias_semana as $dia) { ?>

                                    <div class="semana-card">

                                        <h2>
                                            <?php echo htmlspecialchars($dia, ENT_QUOTES, "UTF-8"); ?>
                                        </h2>


                                        <?php foreach ($tipos_refeicao as $tipo) { ?>

                                            <div class="comida-item">

                                                <b>
                                                    <?php
                                                        if ($tipo == "Café da manhã") {
                                                            echo "Café";
                                                        }
                                                        else {
                                                            echo htmlspecialchars($tipo, ENT_QUOTES, "UTF-8");
                                                        }
                                                    ?>
                                                </b>


                                                <?php if (isset($cardapio_salvo[$dia][$tipo])) { ?>

                                                    <?php $item = $cardapio_salvo[$dia][$tipo]; ?>

                                                    <span>
                                                        <?php echo htmlspecialchars($item["nome"], ENT_QUOTES, "UTF-8"); ?>
                                                    </span>


                                                    <button
                                                        type="button"
                                                        class="secondary-button"
                                                        style="margin-top: 8px;"
                                                        onclick="abrirModalTroca(<?php echo (int) $item['id_cardapio']; ?>)">

                                                        Alterar

                                                    </button>


                                                    <!-- Modal de alteração -->

                                                    <div
                                                        id="modalTroca<?php echo (int) $item['id_cardapio']; ?>"
                                                        class="modal-container">

                                                        <div class="modal-box">

                                                            <h2>Alterar refeição</h2>

                                                            <p>
                                                                Escolha uma nova receita para
                                                                <?php echo htmlspecialchars($tipo, ENT_QUOTES, "UTF-8"); ?>.
                                                            </p>


                                                            <form method="POST" action="rotina_dieta.php">

                                                                <input
                                                                    type="hidden"
                                                                    name="acao"
                                                                    value="trocar_receita">

                                                                <input
                                                                    type="hidden"
                                                                    name="id_cardapio"
                                                                    value="<?php echo (int) $item['id_cardapio']; ?>">


                                                                <select name="id_receita" required>

                                                                    <?php
                                                                        if (isset($receitas_por_tipo[$tipo])) {
                                                                            foreach ($receitas_por_tipo[$tipo] as $opcao) {
                                                                    ?>

                                                                        <option
                                                                            value="<?php echo (int) $opcao['id_receita']; ?>"
                                                                            <?php
                                                                                if (
                                                                                    (int) $opcao['id_receita'] ==
                                                                                    (int) $item['id_receita']
                                                                                ) {
                                                                                    echo "selected";
                                                                                }
                                                                            ?>>

                                                                            <?php echo htmlspecialchars($opcao["nome"], ENT_QUOTES, "UTF-8"); ?>

                                                                        </option>

                                                                    <?php
                                                                            }
                                                                        }
                                                                    ?>

                                                                </select>


                                                                <div class="modal-botoes">

                                                                    <button
                                                                        type="button"
                                                                        class="secondary-button"
                                                                        onclick="fecharModalTroca(<?php echo (int) $item['id_cardapio']; ?>)">

                                                                        Cancelar

                                                                    </button>


                                                                    <button
                                                                        type="submit"
                                                                        class="primary-button">

                                                                        Confirmar alteração

                                                                    </button>

                                                                </div>

                                                            </form>

                                                        </div>

                                                    </div>


                                                <?php } else { ?>

                                                    <span>
                                                        Nenhuma receita disponível para esta refeição.
                                                    </span>

                                                <?php } ?>

                                            </div>

                                        <?php } ?>

                                    </div>

                                <?php } ?>

                            </section>

                            <section class="lista-compras">

                                <h2>Lista de compras da semana</h2>

                                <div class="compras-grid" id="comprasGrid">

                                    <?php foreach ($compras as $item) { ?>

                                        <span>
                                            <?php echo htmlspecialchars($item, ENT_QUOTES, "UTF-8"); ?>
                                        </span>

                                    <?php } ?>

                                </div>

                            </section>

                        <?php } ?>

                    <?php } ?>

                </main>

            </div>

        </section>


        <footer class="footer-dash">

            <p>AllFit © 2026</p>

        </footer>


        <!-- Modal de sucesso ou aviso -->

        <?php if ($mensagem != "") { ?>

            <div
                id="modalSucesso"
                class="modal-container modal-sucesso">

                <div class="modal-box">

                    <div class="icone-sucesso">
                        ✓
                    </div>

                    <h2>
                        <?php echo htmlspecialchars($titulo, ENT_QUOTES, "UTF-8"); ?>
                    </h2>

                    <p>
                        <?php echo htmlspecialchars($mensagem, ENT_QUOTES, "UTF-8"); ?>
                    </p>

                    <button
                        type="button"
                        class="primary-button"
                        onclick="fecharModalSucesso()">

                        Entendi

                    </button>

                </div>

            </div>

        <?php } ?>


    </div>


    <script>

        /*
         * Abre o modal de alteração.
         */
        function abrirModalTroca(id) {

            var modal = document.getElementById("modalTroca" + id);

            if (modal) {
                modal.style.display = "flex";
            }
        }


        /*
         * Fecha o modal de alteração.
         */
        function fecharModalTroca(id) {

            var modal = document.getElementById("modalTroca" + id);

            if (modal) {
                modal.style.display = "none";
            }
        }


        /*
         * Fecha o modal de sucesso.
         */
        function fecharModalSucesso() {

            var modal = document.getElementById("modalSucesso");

            if (modal) {
                modal.style.display = "none";
            }
        }


        /*
         * Fecha os modais ao clicar fora da caixa.
         */
        window.addEventListener("click", function(event) {

            if (event.target.classList.contains("modal-container")) {
                event.target.style.display = "none";
            }

        });


        /*
         * Navegação do menu.
         */
        function abrirDieta() {

            <?php if ($tem_dieta) { ?>

                window.location.href = "rotina_dieta.php";

            <?php } else { ?>

                window.location.href = "dieta.php";

            <?php } ?>

        }


        function abrirExercicios() {

            window.location.href = "rotina_exercicios.php";

        }


        /*
         * Exibe o modal de sucesso ou aviso
         * após o redirecionamento.
         */
        window.addEventListener("load", function() {

            var modal = document.getElementById("modalSucesso");

            if (modal) {
                modal.style.display = "flex";
            }

        });

    </script>

</body>

</html>
