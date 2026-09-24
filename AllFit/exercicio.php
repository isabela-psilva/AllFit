<?php

session_start();

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

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Rotina de Exercícios</title>

    <link rel="stylesheet" href="Base_Style.css">

    <script src="script.js"></script>

</head>


<body>

    <header>

        <div class="logo">
            <img width="100px" src="logo 1.png" alt="AllFit">
        </div>

        <nav>

            <a href="dashboard.php">Início</a>

            <a href="configuracao.php">Configuração</a>

            <a href="index.html">Sair</a>

        </nav>

    </header>


    <section class="banner" id="main">

        <h1>
            Crie uma rotina de exercícios
            <i>
                <span class="title-glow">personalizada</span>
            </i>
        </h1>

        <p>Treinos feitos sob medida para você.</p>

    </section>


    <section class="fundo-exercicio">

        <div class="form-exercicio">

            <div class="formulario">

                <h2>Questionário de Customização</h2>


                <form method="POST" action="gravar_exercicios.php">


                    <!-- MOBILIDADE -->

                    <label>
                        Possuí alguma dificuldade de mobilidade?
                    </label>

                    <br>

                    <input
                        type="radio"
                        name="tem_difMobilidade"
                        value="sim"
                    >

                    Sim


                    <input
                        type="radio"
                        name="tem_difMobilidade"
                        value="nao"
                    >

                    Não

                    <br>


                    <!-- LISTA DE DIFICULDADES -->

                    <section
                        class="center-form"
                        id="listaDificuldades"
                        style="display: none;"
                    >

                        <label>

                            <input
                                type="checkbox"
                                name="dif_mobilidade[]"
                                value="1"
                            >

                            Dor no joelho

                        </label>

                        <br>


                        <label>

                            <input
                                type="checkbox"
                                name="dif_mobilidade[]"
                                value="2"
                            >

                            Problemas na coluna

                        </label>

                        <br>


                        <label>

                            <input
                                type="checkbox"
                                name="dif_mobilidade[]"
                                value="3"
                            >

                            Lesão no ombro

                        </label>

                        <br>


                        <label>

                            <input
                                type="checkbox"
                                name="dif_mobilidade[]"
                                value="4"
                            >

                            Dificuldade de equilíbrio

                        </label>

                        <br>

                    </section>


                    <br>


                    <!-- TIPO DE EXERCÍCIO -->

                    <label>
                        Qual tipo de exercício quer fazer?
                    </label>

                    <br>
                    <br>


                    <select
                        class="form-item"
                        name="tipoExercicio"
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

                        <option value="Yoga">
                            Yoga
                        </option>

                        <option value="Caminhada/Corrida">
                            Caminhada/Corrida
                        </option>

                        <option value="Aeróbico">
                            Aeróbico
                        </option>

                        <option value="Anaeróbico">
                            Anaeróbico
                        </option>

                        <option value="Calistenia">
                            Calistenia
                        </option>

                    </select>


                    <br>
                    <br>


                    <!-- DIAS DISPONÍVEIS -->

                    <label>
                        Dias disponíveis
                    </label>

                    <br>


                    <section class="center-form">

                        <input
                            type="checkbox"
                            name="dias[]"
                            value="segunda"
                        >

                        Segunda

                        <br>


                        <input
                            type="checkbox"
                            name="dias[]"
                            value="terca"
                        >

                        Terça

                        <br>


                        <input
                            type="checkbox"
                            name="dias[]"
                            value="quarta"
                        >

                        Quarta

                        <br>


                        <input
                            type="checkbox"
                            name="dias[]"
                            value="quinta"
                        >

                        Quinta

                        <br>


                        <input
                            type="checkbox"
                            name="dias[]"
                            value="sexta"
                        >

                        Sexta

                        <br>

                        <br>

                    </section>


                    <!-- HORÁRIO -->

                    <label>
                        Horário disponível:
                    </label>

                    <br>
                    <br>


                    De:

                    <input
                        style="width: 120px;"
                        class="form-item"
                        type="time"
                        name="inicio"
                    >


                    <br>


                    Até:

                    <input
                        style="width: 120px;"
                        class="form-item"
                        type="time"
                        name="fim"
                    >


                    <br>
                    <br>


                    <!-- DURAÇÃO -->

                    <label>
                        Duração das sessões:
                    </label>

                    <br>


                    <section class="center-form">

                        <input
                            type="radio"
                            name="duracao"
                            value="30"
                        >

                        30 min

                        <br>


                        <input
                            type="radio"
                            name="duracao"
                            value="60"
                        >

                        1 hora

                        <br>


                        <input
                            type="radio"
                            name="duracao"
                            value="120"
                        >

                        2 horas

                        <br>

                        <br>

                    </section>


                    <br>


                    <!-- LOCAL -->

                    <label>
                        Local para execução dos exercícios:
                    </label>

                    <br>
                    <br>


                    <select
                        class="form-item"
                        name="local"
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

                        <option value="casa">
                            Em casa
                        </option>

                        <option value="academia">
                            Academia
                        </option>

                        <option value="ar_livre">
                            Ao ar livre
                        </option>

                    </select>


                    <br>
                    <br>


                    <!-- DURAÇÃO DO CICLO -->

                    <label>
                        Duração do ciclo:
                    </label>

                    <br>
                    <br>


                    <input
                        style="width: 140px;"
                        class="form-item"
                        type="number"
                        name="dias_total"
                        min="1"
                        max="7"
                        placeholder="Total de dias"
                    >


                    <br>


                    <small>
                        Ex: 3 dias selecionados em um ciclo de 7 dias.
                    </small>


                    <br>
                    <br>


                    <!-- BOTÃO -->

                    <button
                        type="submit"
                        class="primary-button"
                    >
                        Gerar treino
                    </button>


                    <!-- MODAL -->

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


                </form>

            </div>

        </div>

    </section>


    <footer>

        <p>
            AllFit © 2026
        </p>

    </footer>


    <!-- JAVASCRIPT -->

    <script>


        // Pegamos o formulário

        const formulario = document.querySelector("form");


        // Pegamos os dois radios de mobilidade

        const radiosMobilidade = document.querySelectorAll(
            'input[name="tem_difMobilidade"]'
        );


        // Pegamos a área que contém as dificuldades

        const listaDificuldades = document.getElementById(
            "listaDificuldades"
        );


        // Quando clicar em Sim ou Não

        radiosMobilidade.forEach(function(radio) {

            radio.addEventListener("change", function() {


                // Se escolheu SIM

                if (radio.value === "sim") {

                    listaDificuldades.style.display = "block";

                }


                // Se escolheu NÃO

                else {

                    listaDificuldades.style.display = "none";


                    // Desmarca todas as dificuldades

                    const dificuldades = document.querySelectorAll(
                        'input[name="dif_mobilidade[]"]'
                    );


                    dificuldades.forEach(function(item) {

                        item.checked = false;

                    });

                }

            });

        });


        // VALIDAÇÃO DO FORMULÁRIO

        formulario.addEventListener("submit", function(event) {


            // Mobilidade

            let mobilidade = document.querySelector(
                'input[name="tem_difMobilidade"]:checked'
            );


            // Tipo de exercício

            let tipoExercicio = document.querySelector(
                'select[name="tipoExercicio"]'
            );


            // Dias selecionados

            let diasSelecionados = document.querySelectorAll(
                'input[name="dias[]"]:checked'
            );


            // Horário inicial

            let horaInicio = document.querySelector(
                'input[name="inicio"]'
            );


            // Horário final

            let horaFim = document.querySelector(
                'input[name="fim"]'
            );


            // Duração

            let duracao = document.querySelector(
                'input[name="duracao"]:checked'
            );


            // Local

            let local = document.querySelector(
                'select[name="local"]'
            );


            // Total de dias do ciclo

            let diasTotal = document.querySelector(
                'input[name="dias_total"]'
            );


            // -----------------------------
            // MOBILIDADE
            // -----------------------------

            if (!mobilidade) {

                event.preventDefault();

                alert(
                    "Informe se você possui alguma dificuldade de mobilidade."
                );

                return;

            }


            // Se possui dificuldade,
            // precisa escolher pelo menos uma

            if (mobilidade.value === "sim") {


                let dificuldades = document.querySelectorAll(
                    'input[name="dif_mobilidade[]"]:checked'
                );


                if (dificuldades.length === 0) {

                    event.preventDefault();

                    alert(
                        "Selecione pelo menos uma dificuldade de mobilidade."
                    );

                    listaDificuldades.scrollIntoView();

                    return;

                }

            }


            // -----------------------------
            // TIPO DE EXERCÍCIO
            // -----------------------------

            if (tipoExercicio.value === "") {

                event.preventDefault();

                alert(
                    "Escolha o tipo de exercício."
                );

                tipoExercicio.focus();

                return;

            }


            // -----------------------------
            // DIAS
            // -----------------------------

            if (diasSelecionados.length === 0) {

                event.preventDefault();

                alert(
                    "Selecione pelo menos um dia disponível."
                );

                return;

            }


            // -----------------------------
            // HORÁRIO INICIAL
            // -----------------------------

            if (horaInicio.value === "") {

                event.preventDefault();

                alert(
                    "Informe o horário inicial."
                );

                horaInicio.focus();

                return;

            }


            // -----------------------------
            // HORÁRIO FINAL
            // -----------------------------

            if (horaFim.value === "") {

                event.preventDefault();

                alert(
                    "Informe o horário final."
                );

                horaFim.focus();

                return;

            }


            // -----------------------------
            // COMPARAÇÃO DOS HORÁRIOS
            // -----------------------------

            if (horaInicio.value >= horaFim.value) {

                event.preventDefault();

                alert(
                    "O horário final deve ser maior que o horário inicial."
                );

                horaFim.focus();

                return;

            }


            // -----------------------------
            // DURAÇÃO
            // -----------------------------

            if (!duracao) {

                event.preventDefault();

                alert(
                    "Escolha a duração das sessões."
                );

                return;

            }


            // -----------------------------
            // LOCAL
            // -----------------------------

            if (local.value === "") {

                event.preventDefault();

                alert(
                    "Escolha o local para execução dos exercícios."
                );

                local.focus();

                return;

            }


            // -----------------------------
            // TOTAL DE DIAS
            // -----------------------------

            if (diasTotal.value === "") {

                event.preventDefault();

                alert(
                    "Informe o total de dias do ciclo."
                );

                diasTotal.focus();

                return;

            }


            // O total deve estar entre 1 e 7

            if (
                Number(diasTotal.value) < 1 ||
                Number(diasTotal.value) > 7
            ) {

                event.preventDefault();

                alert(
                    "Total de dias deve estar entre 1 e 7."
                );

                diasTotal.focus();

                return;

            }


            // A quantidade de dias selecionados
            // não pode ser maior que o ciclo

            if (
                diasSelecionados.length >
                Number(diasTotal.value)
            ) {

                event.preventDefault();

                alert(
                    "A quantidade de dias selecionados não pode ser maior que o total de dias do ciclo."
                );

                return;

            }


        });

    </script>

</body>

</html>