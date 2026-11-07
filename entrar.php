<?php 
session_start();
include 'conexao.php';

$email = $_POST ["txt_email"];
$senha = $_POST ["txt_senha"];

if ($email == "" || $senha == "") {
    header("Location: login.php?titulo=Atenção!&mensagem=Preencha todos os campos!"); 
    exit;
}

$sql = mysql_query("Select * from usuarios
                    Where email = '$email'
                    AND senha = '$senha'");

if (!$sql) {
    die("Erro na consulta: " . mysql_error());
}

if (mysql_num_rows($sql) > 0) {
    $usuario = mysql_fetch_assoc($sql);
    $_SESSION["id_usuario"] = $usuario["id_usuario"];
    $_SESSION["nome_usuario"] = $usuario["nome"];
    $_SESSION["email_usuario"] = $usuario["email"];
    header("Location: dashboard.php"); 
    exit;
}
else {
    header("Location: login.php?titulo=Erro!&mensagem=E-mail ou senha incorretos!"); 
    exit;
}
?>