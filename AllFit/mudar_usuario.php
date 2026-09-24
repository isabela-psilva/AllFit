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

    $novo_nome = trim($_POST["novo_nome"]);
    $senha = $_POST["senha"];

    // Verifica tamanho do nome
    if (strlen($novo_nome) < 3) {

        $titulo = "Atenção!";
        $mensagem = "O nome deve ter pelo menos 3 caracteres!";
        $campoFocus = "novoNome";

    } elseif (strlen($novo_nome) > 20) {

        $titulo = "Atenção!";
        $mensagem = "O nome deve ter no máximo 20 caracteres!";
        $campoFocus = "novoNome";

    } else {

        // Busca os dados do usuário atual
        $sql = mysql_query("
            SELECT nome, senha
            FROM usuarios
            WHERE id_usuario = '$id_usuario'
        ");

        if (!$sql) {
            die("Erro ao consultar usuário: " . mysql_error());
        }

        $usuario = mysql_fetch_assoc($sql);

        // Verifica a senha
        if ($usuario["senha"] != $senha) {

            $titulo = "Erro!";
            $mensagem = "Senha incorreta!";
            $campoFocus = "senha";

        } else {

            // Verifica se o nome já está sendo usado por outro usuário
            $verificaNome = mysql_query("
                SELECT id_usuario
                FROM usuarios
                WHERE nome = '$novo_nome'
                AND id_usuario != '$id_usuario'
            ");

            if (!$verificaNome) {
                die("Erro ao verificar nome: " . mysql_error());
            }

            if (mysql_num_rows($verificaNome) > 0) {

                $titulo = "Erro!";
                $mensagem = "Esse nome de usuário já existe!";
                $campoFocus = "novoNome";

            } else {

                // Atualiza o nome
                $alterar = mysql_query("
                    UPDATE usuarios
                    SET nome = '$novo_nome'
                    WHERE id_usuario = '$id_usuario'
                ");

                if (!$alterar) {
                    die("Erro ao alterar nome: " . mysql_error());
                }

                // Volta para configuração
                header("Location: configuracao.php");
                exit;
            }
        }
    }
}
?>

<html lang="PT-BR">

<head>
    <meta charset="UTF-8">

    <title>Mudar Nome de Usuário</title>

    <link rel="stylesheet" href="Base_Style.css">

    <script src="script.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

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

        <h1>Mudar Nome de Usuário</h1>

        

        <form method="POST">
            <h3>Insira Seu Novo Nome de Usuário</h3>
            <input id="novoNome" name="novo_nome" class="form-item" type="text" placeholder="Nome de Usuário" required>

            <h3>Digite sua Senha</h3>

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