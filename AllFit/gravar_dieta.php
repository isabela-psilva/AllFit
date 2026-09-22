<?php
    include 'conexao.php';

    $nome = $_POST ["txt_nome"];
    
    // if ($senha != $confirmarSenha) {
        // header("Location: cadastro.php?titulo=Erro&mensagem=As senhas não são iguais!"); exit;
    // }
    // else {
        $sql = mysql_query("Select * from usuarios
                            where email = '$email'");

        if (!$sql) {
            die("Erro na consulta: " . mysql_error());
        }

        if (mysql_num_rows($sql) > 0) {
            header("Location: cadastro.php?titulo=Erro&mensagem=E-mail já cadastrado!"); exit;
        }
        else {
            $sql = mysql_query("Insert Into usuarios (nome, email, senha)
                                Values ('$nome', '$email', '$senha')");
        
            if (!$sql) {
                die("Erro ao Cadastrar o Usuário: " . mysql_error());
            }
            
            header("Location: login.php?titulo=Sucesso&mensagem=Usuário cadastrado com sucesso!"); exit;
        }
    // }
?>