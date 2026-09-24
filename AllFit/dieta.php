<?php

    session_start();

    include 'conexao.php';

    if (!isset($_SESSION["id_usuario"])) {

        header("Location: login.php");
        exit;

    }

    $id_usuario = $_SESSION["id_usuario"];


    // ==========================================
    // VERIFICAR SE JÁ EXISTE UMA DIETA
    // ==========================================

    $sqlDieta = mysql_query("
        SELECT id_dieta
        FROM dietas
        WHERE id_usuario = '$id_usuario'
        LIMIT 1
    ");

    if (!$sqlDieta) {
        die("Erro ao verificar dieta: " . mysql_error());
    }

    $temDieta = mysql_num_rows($sqlDieta) > 0;


    // ==========================================
    // SE JÁ EXISTE E NÃO ESTÁ EDITANDO
    // VAI PARA A ROTINA
    // ==========================================

    if ($temDieta && !isset($_GET["editar"])) {

        header("Location: rotina_dieta.php");
        exit;

    }


    $mensagem = "";
    $titulo = "";


    if (isset($_GET["mensagem"])) {

        $mensagem = $_GET["mensagem"];

    }


    if (isset($_GET["titulo"])) {

        $titulo = $_GET["titulo"];

    }


    // ==========================================
    // RESTRIÇÕES
    // ==========================================

    $restricoesAlimentares = array(

        1 => "Laticínios",
        2 => "Glúten",
        3 => "Oleaginosas",
        4 => "Leguminosas",
        5 => "Frutos do mar",
        6 => "Ovos",
        7 => "Outros"

    );


    $outrasRestricoes = array(

        8 => "Vegano",
        9 => "Vegetariano",
        10 => "Diabético",
        11 => "Hipertenso",
        12 => "Por motivos religiosos",
        13 => "Outra restrição"

    );

?>


<html lang="pt-br">

<head>

<meta charset="UTF-8">

<title>Dieta</title>

<link rel="stylesheet" href="Base_Style.css">

<script src="script.js"></script>

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

</head>

<body>

<header>

    <div class="logo">

        <img
            width="100px"
            src="logo 1.png"
            alt="AllFit"
        >

    </div>

    <nav>

        <a href="dashboard.php">
            Início
        </a>

        <a href="configuracao.php">
            Configurações
        </a>

        <a href="index.html">
            Sair
        </a>

    </nav>

</header>


<section class="banner" id="main">

    <h1>
        Crie uma dieta
        <i>
            <span class="title-glow">
                personalizada
            </span>
        </i>
    </h1>

    <p>
        Dieta criada sob medida para você.
    </p>

</section>


<section class="fundo-dieta">

    <div class="form-dieta">

        <div class="formulario">

            <form
                name="frm_dieta"
                action="gravar_dieta.php"
                method="POST"
            >

                <section class="center-form">

                    <h2>
                        Restrições e Metas
                    </h2>

                    <br>


                    <!-- ================================= -->
                    <!-- RESTRIÇÕES ALIMENTARES              -->
                    <!-- ================================= -->

                    <label>
                        Você possui alergias ou intolerâncias?
                    </label>

                    <br>

                    <input
                        type="radio"
                        name="tem_restricao"
                        data-target="listaRestricoes"
                        value="sim"
                    >
                    Sim

                    <input
                        type="radio"
                        name="tem_restricao"
                        data-target="listaRestricoes"
                        value="nao"
                    >
                    Não


                    <section
                        id="listaRestricoes"
                        style="display: none;"
                    >

                        <br>

                        <?php

                            foreach (
                                $restricoesAlimentares
                                as $id => $nome
                            ) {

                        ?>

                            <input
                                type="checkbox"
                                name="restricoes[]"
                                value="<?php
                                    echo $id;
                                ?>"
                            >

                            <?php
                                echo htmlspecialchars(
                                    $nome
                                );
                            ?>

                            <br>

                        <?php

                            }

                        ?>

                    </section>


                    <br>
                    <br>


                    <!-- ================================= -->
                    <!-- OUTRAS RESTRIÇÕES                   -->
                    <!-- ================================= -->

                    <label>
                        Possui restrição por algum outro motivo?
                    </label>

                    <br>

                    <input
                        type="radio"
                        name="tem_restricao_plus"
                        data-target="listaRestricoesPlus"
                        value="sim"
                    >
                    Sim

                    <input
                        type="radio"
                        name="tem_restricao_plus"
                        data-target="listaRestricoesPlus"
                        value="nao"
                    >
                    Não


                    <br>
                    <br>


                    <section
                        id="listaRestricoesPlus"
                        style="display: none;"
                    >

                        <?php

                            foreach (
                                $outrasRestricoes
                                as $id => $nome
                            ) {

                        ?>

                            <input
                                type="checkbox"
                                name="restricoes[]"
                                value="<?php
                                    echo $id;
                                ?>"
                            >

                            <?php
                                echo htmlspecialchars(
                                    $nome
                                );
                            ?>

                            <br>

                        <?php

                            }

                        ?>


                        <br>
                        <br>


                        <label>
                            Quais alimentos você não pode consumir?
                        </label>

                        <br>
                        <br>

                        <textarea
                            class="form-item"
                            name="alimentos_nao_consumidos"
                            placeholder="Digite aqui os alimentos que você não pode consumir"
                            cols="80"
                            rows="4"
                        ></textarea>

                    </section>


                    <br>


                    <!-- ================================= -->
                    <!-- VALOR                              -->
                    <!-- ================================= -->

                    <label>
                        Quanto você pretende gastar?
                    </label>

                    <br>
                    <br>

                    <input
                        class="form-item"
                        name="txt_valor"
                        type="number"
                        placeholder="Insira o valor em reais"
                        min="10"
                        max="99999"
                        step="0.01"
                    >


                    <br>
                    <br>


                    <!-- ================================= -->
                    <!-- GRUPO ALIMENTAR                    -->
                    <!-- ================================= -->

                    <label>
                        Em qual grupo alimentar a dieta é focada?
                    </label>

                    <br>
                    <br>

                    <select
                        class="form-item"
                        name="grupo_alimentar"
                        required
                    >

                        <option
                            value=""
                            disabled
                            selected
                            hidden
                        >
                            Selecione uma opção
                        </option>

                        <option value="Grãos">
                            Grãos
                        </option>

                        <option value="Carboidratos">
                            Carboidratos
                        </option>

                        <option value="Proteínas">
                            Proteínas
                        </option>

                        <option value="Laticínios">
                            Laticínios
                        </option>

                        <option value="Todos os grupos">
                            Todos os grupos
                        </option>

                    </select>


                    <br>
                    <br>


                    <!-- ================================= -->
                    <!-- CALORIAS                           -->
                    <!-- ================================= -->

                    <label>
                        Defina uma meta diária de calorias consumidas:
                    </label>

                    <br>
                    <br>

                    <input
                        class="form-item"
                        name="txt_calorias"
                        type="number"
                        placeholder="Insira a meta de calorias"
                        min="100"
                        max="99999"
                    >


                    <br>
                    <br>


                    <!-- ================================= -->
                    <!-- HORÁRIOS                           -->
                    <!-- ================================= -->

                    <h3>
                        Rotina Alimentar
                    </h3>

                    <label>
                        Quais os horários das suas refeições?
                    </label>

                    <br>
                    <br>

                    <p>
                        Café:

                        <input
                            class="form-item"
                            name="hora_cafe"
                            type="time"
                        >
                    </p>

                    <p>
                        Almoço:

                        <input
                            class="form-item"
                            name="hora_almoco"
                            type="time"
                        >
                    </p>

                    <p>
                        Janta:

                        <input
                            class="form-item"
                            name="hora_janta"
                            type="time"
                        >
                    </p>


                    <br>


                    <!-- ================================= -->
                    <!-- CICLO                               -->
                    <!-- ================================= -->

                    <label>
                        Quanto tempo deve durar o ciclo da dieta?
                    </label>

                    <br>
                    <br>

                    <select
                        class="form-item"
                        name="ciclo"
                        required
                    >

                        <option
                            value=""
                            disabled
                            selected
                            hidden
                        >
                            Selecione uma opção
                        </option>

                        <option value="Uma semana">
                            Uma semana
                        </option>

                        <option value="Um mês">
                            Um mês
                        </option>

                        <option value="Quatro meses">
                            Quatro meses
                        </option>

                    </select>


                    <br>
                    <br>


                    <!-- ================================= -->
                    <!-- SUPLEMENTO                          -->
                    <!-- ================================= -->

                    <label>
                        Você utiliza algum suplemento alimentar?
                    </label>

                    <br>
                    <br>

                    <input
                        type="radio"
                        name="usa_suplemento"
                        data-target="textoSuplemento"
                        value="sim"
                    >
                    Sim

                    <input
                        type="radio"
                        name="usa_suplemento"
                        data-target="textoSuplemento"
                        value="nao"
                    >
                    Não


                    <br>
                    <br>


                    <section
                        id="textoSuplemento"
                        style="display: none;"
                    >

                        <input
                            class="form-item"
                            type="text"
                            name="suplemento"
                            placeholder="Digite o suplemento alimentar utilizado"
                            maxlength="100"
                        >

                    </section>


                    <br>


                    <!-- ================================= -->
                    <!-- BOTÕES                              -->
                    <!-- ================================= -->

                    <input
                        class="primary-button"
                        name="btn_confirmar"
                        type="button"
                        value="Finalizar"
                        onclick="Confirmar()"
                    >

                    <br>
                    <br>

                    <input
                        class="primary-button"
                        name="btn_cancelar"
                        type="button"
                        value="Cancelar"
                        onclick="Cancelar()"
                    >


                    <!-- ================================= -->
                    <!-- MODAL                               -->
                    <!-- ================================= -->

                    <div
                        id="meuModal"
                        class="modal-container"
                        style="display: none;"
                    >

                        <div class="modal-box">

                            <h2
                                id="modalTitulo"
                                style="margin-top: 0;"
                            >
                                Título
                            </h2>

                            <br>

                            <p id="modalTexto">
                                Mensagem da operação.
                            </p>

                            <input
                                type="button"
                                id="btnModalOk"
                                class="primary-button"
                                value="Ok"
                            >

                        </div>

                    </div>

                </section>

            </form>

        </div>

    </div>

</section>


<footer>

    <p>
        AllFit © 2026
    </p>

</footer>


<script>

    /*
     * Mostra/esconde as opções adicionais
     * dos radio buttons.
     */

    document.addEventListener(
        "change",
        function(e) {

            const radio = e.target;

            if (
                !radio.matches(
                    'input[type="radio"][data-target]'
                )
            ) {
                return;
            }

            const groupName = radio.name;

            const targetId =
                radio.dataset.target;

            const target =
                document.getElementById(targetId);

            if (!target) {
                return;
            }

            const checkedRadio =
                document.querySelector(
                    `input[name="${groupName}"]:checked`
                );

            if (
                checkedRadio &&
                checkedRadio.value === "sim"
            ) {

                target.style.display = "block";

            }
            else {

                target.style.display = "none";

            }

        }
    );


    /*
     * Validação do formulário.
     */

    function Confirmar() {

        const restricao =
            document.querySelector(
                'input[name="tem_restricao"]:checked'
            );

        const restricaoPlus =
            document.querySelector(
                'input[name="tem_restricao_plus"]:checked'
            );

        const suplemento =
            document.querySelector(
                'input[name="usa_suplemento"]:checked'
            );


        const valor =
            document.frm_dieta.txt_valor.value;

        const grupo =
            document.frm_dieta.grupo_alimentar.value;

        const calorias =
            document.frm_dieta.txt_calorias.value;

        const cafe =
            document.frm_dieta.hora_cafe.value;

        const almoco =
            document.frm_dieta.hora_almoco.value;

        const janta =
            document.frm_dieta.hora_janta.value;

        const ciclo =
            document.frm_dieta.ciclo.value;


        /*
         * Verifica os radio buttons.
         */

        if (!restricao) {

            exibirModal(
                "Atenção!",
                "Selecione se possui alergias ou intolerâncias."
            );

            return;
        }


        if (!restricaoPlus) {

            exibirModal(
                "Atenção!",
                "Selecione se possui outras restrições."
            );

            return;
        }


        if (!suplemento) {

            exibirModal(
                "Atenção!",
                "Selecione se utiliza suplemento alimentar."
            );

            return;
        }


        /*
         * Verifica os campos obrigatórios.
         */

        if (valor === "") {

            exibirModal(
                "Atenção!",
                "Informe quanto pretende gastar."
            );

            return;
        }


        if (grupo === "") {

            exibirModal(
                "Atenção!",
                "Selecione um grupo alimentar."
            );

            return;
        }


        if (calorias === "") {

            exibirModal(
                "Atenção!",
                "Informe sua meta diária de calorias."
            );

            return;
        }


        if (cafe === "") {

            exibirModal(
                "Atenção!",
                "Informe o horário do café da manhã."
            );

            return;
        }


        if (almoco === "") {

            exibirModal(
                "Atenção!",
                "Informe o horário do almoço."
            );

            return;
        }


        if (janta === "") {

            exibirModal(
                "Atenção!",
                "Informe o horário da janta."
            );

            return;
        }


        if (ciclo === "") {

            exibirModal(
                "Atenção!",
                "Selecione o ciclo da dieta."
            );

            return;
        }


        /*
         * Validação do valor.
         */

        if (
            Number(valor) < 10 ||
            Number(valor) > 99999
        ) {

            exibirModal(
                "Erro!",
                "Valor em dinheiro inválido!",
                null,
                document.frm_dieta.txt_valor
            );

            document.frm_dieta.txt_valor.value = "";

            return;
        }


        /*
         * Validação das calorias.
         */

        if (
            Number(calorias) < 100 ||
            Number(calorias) > 99999
        ) {

            exibirModal(
                "Erro!",
                "Meta de calorias inválida!",
                null,
                document.frm_dieta.txt_calorias
            );

            document.frm_dieta.txt_calorias.value = "";

            return;
        }


        /*
         * Tudo certo.
         * Envia para gravar_dieta.php.
         */

        document.frm_dieta.submit();

    }


    /*
     * Cancelar.
     */

    function Cancelar() {

        exibirModal(
            "Cancelado",
            "Geração de dieta cancelada.",
            "dashboard.php"
        );

    }

</script>


<?php if ($mensagem != "") { ?>

    <script>

        exibirModal(
            "<?php
                echo htmlspecialchars(
                    $titulo,
                    ENT_QUOTES,
                    "UTF-8"
                );
            ?>",

            "<?php
                echo htmlspecialchars(
                    $mensagem,
                    ENT_QUOTES,
                    "UTF-8"
                );
            ?>"
        );

    </script>

<?php } ?>


</body>

</html>