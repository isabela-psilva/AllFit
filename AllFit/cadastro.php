<?php
    $mensagem = "";
    $titulo = "";

    if (isset($_GET["mensagem"])) {
        $mensagem = $_GET["mensagem"];

    }

    if (isset($_GET["titulo"])) {
        $titulo = $_GET["titulo"];
    }
?>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>AllFit - Cadastro</title>
    <link rel="stylesheet" href="Base_Style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js"></script>
</head>
<body>

    <header>
        <div class="logo"><img width="100px" src="logo 1.png" alt="AllFit"></div>
        <nav>
            <a href="index.html">Início</a>
            <a href="login.php">Login</a>
        </nav>
    </header>
    
    <section class="fundo-imagem">
           
            <div class="card-form card-auth">

                <h1>Criar Conta</h1>

                <form action="gravar.php" method="POST">

                    <input class="form-item" type="text" id="nome" name="txt_nome" placeholder="Nome completo" />
                    <input class="form-item" type="email" id="email" name="txt_email" placeholder="E-mail" />
                    <input class="form-item" type="password" id="senha" name="txt_senha" placeholder="Senha" />
                    <input class="form-item" type="password" id="confirmarSenha" name="txt_confirmarSenha" placeholder="Confirmar senha" />

                    <div class="botoes">
                        <button type="submit" class="primary-button">Cadastrar</button>
                    </div>
                </form>

                <div id="meuModal" class="modal-container" style="display: none;">
                    <div class="modal-box">
                        <h2 id="modalTitulo" style="margin-top: 0;">Título</h2>
                        <br><p></p>
                        <p id="modalTexto">Mensagem da operação.</p>
                        <input type="button" id="btnModalOk" class="primary-button" value="Ok">
                    </div>
                </div>

                <p>Já tem conta? <a href="login.php">Entrar</a></p>

            </div>
        
    </section>

    <footer>
        <p>AllFit © 2026</p>
    </footer>
    
    <?php if ($mensagem != "") { ?>
        <script>
            exibirModal(
                "<?php echo $titulo; ?>",
                "<?php echo $mensagem; ?>"
            );
        </script>
    <?php } ?>
    
</body>
</html>