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
$campoFocus = "";

if (isset($_POST["confirmar"])) {

    $confirmacao = isset($_POST["confirmacao"]);
    $senha = $_POST["senha"];


    // Verifica se o usuário confirmou a exclusão
    if (!$confirmacao) {

        $titulo = "Atenção!";
        $mensagem = "Você precisa confirmar que deseja deletar a conta!";

    } else {

        // Busca a senha atual do usuário
        $sql = mysql_query("
            SELECT senha
            FROM usuarios
            WHERE id_usuario = '$id_usuario'
        ");

        if (!$sql) {
            die("Erro ao consultar usuário: " . mysql_error());
        }

        $usuario = mysql_fetch_assoc($sql);


        // Verifica a senha
        if (!$usuario || $usuario["senha"] != $senha) {

            $titulo = "Erro!";
            $mensagem = "Senha incorreta!";
            $campoFocus = "senha";

        } else {

            /*
             * Antes de apagar o usuário,
             * apagamos os dados relacionados a ele.
             */


            // Busca as dietas do usuário
            $dietas = mysql_query("
                SELECT id_dieta
                FROM dietas
                WHERE id_usuario = '$id_usuario'
            ");

            if (!$dietas) {
                die("Erro ao buscar dietas: " . mysql_error());
            }


            // Apaga as restrições das dietas
            while ($dieta = mysql_fetch_assoc($dietas)) {

                $id_dieta = $dieta["id_dieta"];

                $apagarRestricoes = mysql_query("
                    DELETE FROM dieta_restricao
                    WHERE id_dieta = '$id_dieta'
                ");

                if (!$apagarRestricoes) {
                    die("Erro ao apagar restrições da dieta: " . mysql_error());
                }
            }


            // Apaga as dietas
            $apagarDietas = mysql_query("
                DELETE FROM dietas
                WHERE id_usuario = '$id_usuario'
            ");

            if (!$apagarDietas) {
                die("Erro ao apagar dietas: " . mysql_error());
            }


            // Busca os treinos do usuário
            $treinos = mysql_query("
                SELECT id_treino
                FROM treinos
                WHERE id_usuario = '$id_usuario'
            ");

            if (!$treinos) {
                die("Erro ao buscar treinos: " . mysql_error());
            }


            // Apaga as dificuldades dos treinos
            while ($treino = mysql_fetch_assoc($treinos)) {

                $id_treino = $treino["id_treino"];

                $apagarDificuldades = mysql_query("
                    DELETE FROM treino_dificuldade
                    WHERE id_treino = '$id_treino'
                ");

                if (!$apagarDificuldades) {
                    die("Erro ao apagar dificuldades do treino: " . mysql_error());
                }
            }


            // Apaga os treinos
            $apagarTreinos = mysql_query("
                DELETE FROM treinos
                WHERE id_usuario = '$id_usuario'
            ");

            if (!$apagarTreinos) {
                die("Erro ao apagar treinos: " . mysql_error());
            }


            /*
             * Agora podemos apagar o usuário.
             */

            $apagarUsuario = mysql_query("
                DELETE FROM usuarios
                WHERE id_usuario = '$id_usuario'
            ");

            if (!$apagarUsuario) {
                die("Erro ao apagar usuário: " . mysql_error());
            }


            // Encerra a sessão
            session_destroy();


            // Redireciona para a página inicial
            header("Location: index.html");
            exit;
        }
    }
}

?>

<html lang="PT-BR">

<head>

    <meta charset="UTF-8">

    <title>Deletar Conta</title>

    <link rel="stylesheet" href="Base_Style.css">

    <script src="script.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>

        .card-config-form form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card-config-form form .form-item {
            width: 100%;
            box-sizing: border-box;
        }

        .card-config-form .botoes {
            display: flex;
            justify-content: center;
            gap: 10px;
            width: 100%;
        }

    </style>

</head>


<body>

<header>

    <div class="logo">
        <img width="100px" src="logo 1.png" alt="AllFit">
    </div>

    <nav>
        <a href="dashboard.php">Início</a>
        <a href="index.html">Sair</a>
    </nav>

</header>

<div class="espaco-header"></div>


<section class="fundo-imagem fundo-config">

    <div class="card-form card-config-form">

        <h1>Deletar Conta</h1>

        <h3>
            Você tem certeza de que quer deletar sua conta?
        </h3>


        <form method="POST">

            <label>
                <input
                    id="confirmacao"
                    name="confirmacao"
                    type="checkbox"
                >
                Sim
            </label>


            <h3>Insira Sua Senha</h3>

            <input
                id="senha"
                name="senha"
                class="form-item"
                type="password"
                placeholder="Senha"
                required
            >


            <div class="botoes">

                <button
                    type="submit"
                    name="confirmar"
                    class="primary-button"
                >
                    Confirmar
                </button>

                <button
                    type="button"
                    class="primary-button"
                    onclick="cancelar()"
                >
                    Cancelar
                </button>

            </div>

        </form>


        <div id="meuModal" class="modal-container" style="display: none;">

            <div class="modal-box">

                <h2 id="modalTitulo" style="margin-top: 0;">
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

    </div>

</section>


<footer>

    <p>AllFit © 2026</p>

</footer>


<script>

function cancelar() {

    let confirmar = confirm("Deseja cancelar a operação?");

    if (confirmar) {
        window.location.href = "configuracao.php";
    }

}


<?php if ($mensagem != "") { ?>

exibirModal(
    "<?php echo $titulo; ?>",
    "<?php echo $mensagem; ?>",
    null,
    "<?php echo $campoFocus; ?>"
);

<?php } ?>

</script>


</body>

</html>