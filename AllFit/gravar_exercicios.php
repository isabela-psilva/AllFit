<?php 
    session_start();

    include 'conexao.php';

    $id_usuario = $_SESSION["id_usuario"];

    $tipo = $_POST["tipoExercicio"];
    $hrInicio = $_POST["inicio"];
    $hrFim = $_POST["fim"];
    $duracao = $_POST["duracao"];
    $local = $_POST["local"];
    $diasTreino = $_POST["diasDisponiveis"];
    $diasTotal = $_POST["dias_total"];
    $dificuldades = isset($_POST['dif_mobilidade']) ? $_POST['dif_mobilidade'] : array();

    if ($tipo == "") {
        header("Location: rotinaExercicio.php?titulo=Atenção!&mensagem=Preencha todos os campos!");
        exit;
    }

    // Tabela treinos

    $sqlTreinos = mysql_query("Select * from treinos
                        Where id_usuario = '$id_usuario'");

    if (!$sqlTreinos) {
        die("Erro na consulta: " . mysql_error());
    }

    if (mysql_num_rows($sqlTreinos) > 0) {
        $sqlTreinos = mysql_query("Update treinos
                            Set tipo_exercicio = '$tipo',
                                horario_inicio = '$hrInicio',
                                horario_fim = '$hrFim',
                                duracao = '$duracao',
                                local = '$local',
                                dias_treino = '$diasTreino',
                                dias_total = '$diasTotal'
                            Where id_usuario = '$id_usuario'");
        
        if (!$sqlTreinos) {
            die("Erro ao atualizar a rotina de treinos: " . mysql_error());
        }
    }
    else {
        $sqlTreinos = mysql_query("Insert Into treinos
                            (id_usuario, tipo_exercicio, horario_inicio, horario_fim,
                             duracao, local, dias_treino, dias_total)
                            Values
                            ('$id_usuario', '$tipo', '$hrInicio', '$hrFim',
                            '$duracao', '$local', '$diasTreino', '$diasTotal')");
            
        if (!$sqlTreinos) {
            die("Erro ao salvar a rotina de exercícios: " . mysql_error());
        }                    
    }

    // Buscar id_treino do usuário

    $sqlBuscaTreino = mysql_query("Select id_treino from treinos Where id_usuario = '$id_usuario'");
    $dadosTreino = mysql_fetch_array($sqlBuscaTreino);
    $id_treino = $dadosTreino['id_treino'];

    // Tabela treino_dificuldade

    $sqlDelete = mysql_query("Delete from treino_dificuldade Where id_treino = '$id_treino'");   
    if (!$sqlDelete) {
        die("Erro ao limpar dificuldades antigas: " . mysql_error());
    }

    if (!empty($dificuldades)) {
        foreach ($dificuldades as $id_dificuldade) {
            $sqlDif = mysql_query("Insert Into treino_dificuldade (id_treino, id_dificuldade) 
                                         Values ('$id_treino', '$id_dificuldade')");
            
            if (!$sqlDif) {
                die("Erro ao salvar a dificuldade: " . mysql_error());
            }
        }
    }

    header("Location: dashboard.php?titulo=Sucesso!&mensagem=Rotina de exercícios salva com sucesso!");
    exit;
?>