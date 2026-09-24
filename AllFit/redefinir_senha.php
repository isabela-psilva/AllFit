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

    $senha_atual = $_POST["senha_atual"];
    $nova_senha = $_POST["nova_senha"];
    $confirmar_senha = $_POST["confirmar_senha"];


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


    // Verifica se a senha atual está correta
    if ($usuario["senha"] != $senha_atual) {

        $titulo = "Erro!";
        $mensagem = "Senha atual incorreta!";
        $campoFocus = "senhaAtual";

    }

    // Verifica tamanho mínimo
    elseif (strlen($nova_senha) < 8) {

        $titulo = "Atenção!";
        $mensagem = "A senha deve ter no mínimo 8 caracteres!";
        $campoFocus = "novaSenha";

    }

    // Verifica tamanho máximo
    elseif (strlen($nova_senha) > 25) {

        $titulo = "Atenção!";
        $mensagem = "A senha deve ter no máximo 25 caracteres!";
        $campoFocus = "novaSenha";

    }

    // Verifica se possui letras e números
    elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d).+$/", $nova_senha)) {

        $titulo = "Atenção!";
        $mensagem = "Senha fraca! Use letras e números.";
        $campoFocus = "novaSenha";

    }

    // Verifica se as senhas coincidem
    elseif ($nova_senha != $confirmar_senha) {

        $titulo = "Atenção!";
        $mensagem = "As senhas não coincidem!";
        $campoFocus = "confirmarSenha";

    }

    else {

        // Atualiza a senha no banco
        $alterar = mysql_query("
            UPDATE usuarios
            SET senha = '$nova_senha'
            WHERE id_usuario = '$id_usuario'
        ");

        if (!$alterar) {
            die("Erro ao alterar senha: " . mysql_error());
        }

        // Volta para configuração
        header("Location: configuracao.php");
        exit;
    }
}

?>

<html lang="PT-BR">

<head>

    <meta charset="UTF-8">

    <title>Redefinir Senha</title>

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

        <h1>Redefinir Senha</h1>


        <form method="POST">

            <h3>Insira Sua Senha Atual</h3>

            <input
                id="senhaAtual"
                name="senha_atual"
                class="form-item"
                type="password"
                placeholder="Senha atual"
                required
            >


            <h3>Insira sua Nova Senha</h3>

            <input
                id="novaSenha"
                name="nova_senha"
                class="form-item"
                type="password"
                placeholder="Nova senha"
                required
            >


            <h3>Repita sua Nova Senha</h3>

            <input
                id="confirmarSenha"
                name="confirmar_senha"
                class="form-item"
                type="password"
                placeholder="Repita a senha"
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