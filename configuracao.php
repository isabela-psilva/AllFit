<?php
    session_start();

    include 'conexao.php';

    // Verifica se o usuário está logado
    if (!isset($_SESSION["id_usuario"])) {
        header("Location: login.php");
        exit;
    }

    $id_usuario = $_SESSION["id_usuario"];

    // Busca o nome do usuário
    $sql = mysql_query("
        SELECT nome
        FROM usuarios
        WHERE id_usuario = '$id_usuario'
    ");

    if (!$sql) {
        die("Erro ao buscar usuário: " . mysql_error());
    }

    $usuario = mysql_fetch_assoc($sql);

    $nome_usuario = $usuario["nome"];
?>


<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Configuração de Conta</title>

    <link rel="stylesheet" href="Base_Style.css">
    <link rel="stylesheet" href="dashboardStyle.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>

<section class="dashboard">

    <aside class="sidebar">

        <img
            class="logo-dash"
            src="logo 1.png"
            alt="AllFit">

        <nav class="menu-dash">

            <a href="dashboard.php">

                <img
                    src="home.png"
                    alt="">

                Início

            </a>


            <a href="perfil.php">

                <img
                    src="profile.png"
                    alt="">

                Meu Perfil

            </a>


            <a href="#" onclick="abrirDieta()">

                <img
                    src="diet.png"
                    alt="">

                Alimentação

            </a>


            <a href="#" onclick="abrirExercicios()">

                <img
                    src="dumbbell.png"
                    alt="">

                Exercícios

            </a>

        </nav>


        <a
            class="sair-dash"
            href="index.html">

            <img
                src="logout.png"
                alt="">

            Sair

        </a>

    </aside>


    <div class="page-config">

        <main class="conteudo-dash">

            <div class="topo-dash">

                <div>

                    <h1>
                        Olá, <?php echo $nome_usuario; ?>!
                    </h1>

                    <p>
                        Configure aqui sua conta.
                    </p>

                </div>

            </div>


            <section class="cards-config">


                <!-- ALTERAR NOME -->

                <a
                    href="mudar_usuario.php"
                    class="config-card">

                    <img
                        src="profile.png"
                        alt="">

                    <h2>
                        Alterar Nome de Usuário
                    </h2>

                    <p>
                        Altere seu nome de usuário cadastrado.
                    </p>

                </a>



                <!-- REDEFINIR SENHA -->

                <a
                    href="redefinir_senha.php"
                    class="config-card">

                    <img
                        src="lock.png"
                        alt="">

                    <h2>
                        Redefinir Senha
                    </h2>

                    <p>
                        Altere sua senha atual para uma mais segura.
                    </p>

                </a>



                <!-- DELETAR CONTA -->

                <a
                    href="deletar_conta.php"
                    class="config-card perigo">

                    <img
                        src="logout.png"
                        alt="">

                    <h2>
                        Deletar Conta
                    </h2>

                    <p>
                        Delete sua conta permanentemente do sistema.
                    </p>

                </a>

            </section>

        </main>

    </div>

</section>


<footer class="footer-dash">

    <p>
        AllFit © 2026
    </p>

</footer>


<script>

    function abrirDieta() {

        window.location.href = "rotina_dieta.php";

    }


    function abrirExercicios() {

        window.location.href = "rotina_exercicios.php";

    }

</script>

</body>

</html>