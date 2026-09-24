<?php 
    session_start();

    include 'conexao.php';

    $id_usuario = $_SESSION["id_usuario"];

    $idade = $_POST["txt_idade"];
    $altura = $_POST["txt_altura"];
    $peso = $_POST["txt_peso"];

    if ($idade == "" || $altura == "" || $peso == "") {
        header("Location: perfil.php?titulo=Atenção!&mensagem=Preencha todos os campos!");
        exit;
    }

    $sql = mysql_query("Select * from perfis
                        Where id_usuario = '$id_usuario'");

    if (!$sql) {
        die("Erro na consulta: " . mysql_error());
    }

    if (mysql_num_rows($sql) > 0) {
        $sql = mysql_query("Update perfis
                            Set idade = '$idade',
                                altura = '$altura',
                                peso = '$peso'
                            Where id_usuario = '$id_usuario'");
        
        if (!$sql) {
            die("Erro ao atualizar o perfil: " . mysql_error());
        }
    }
    else {
        $sql = mysql_query("Insert Into perfis
                            (id_usuario, idade, altura, peso)
                            Values
                            ('$id_usuario', '$idade', '$altura', '$peso')");
            
        if (!$sql) {
            die("Erro ao salvar o perfil: " . mysql_error());
        }                    
    }

    header("Location: dashboard.php?titulo=Sucesso!&mensagem=Perfil salvo com sucesso!");
    exit;

    ?>