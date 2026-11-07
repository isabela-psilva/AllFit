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
    <title>AllFit - Login</title>
    <script src="script.js"></script>
    <link rel="stylesheet" href="Base_Style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <header>
        <div class="logo"><img width="100px" src="logo 1.png" alt="AllFit"></div>
        <nav>
            <a href="index.html">Início</a>
            <a href="cadastro.php">Cadastro</a>
            
        </nav>
    </header>

    <div class="espaco-header"></div>

<section class="fundo-imagem">

    <div class="card-form card-auth">

            <h1>Entrar</h1>

            <form action = "entrar.php" method = "POST">

                <input class="form-item" type="email" id="email" name="txt_email" placeholder="E-mail">
                <input class="form-item" type="password" id="senha" name="txt_senha" placeholder="Senha">

                <div class="botoes">
                    <button type="submit" class="primary-button">Entrar</button>
                </div>
                <div id="meuModal" class="modal-container" style="display: none;">
                    <div class="modal-box">
                        <h2 id="modalTitulo" style="margin-top: 0;">Título</h2>
                        <br>
                        <p id="modalTexto">Mensagem da operação.</p>
                        <input type="button" id="btnModalOk" class="primary-button" value="Ok">
                    </div>
                </div>
            </form>
            <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>    
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