
<?php

session_start();

include 'conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

// Receber os dados do formulário
$tipo = $_POST["tipoExercicio"];
$hrInicio = $_POST["inicio"];
$hrFim = $_POST["fim"];
$duracao = $_POST["duracao"];
$local = $_POST["local"];
$diasTotal = $_POST["dias_total"];

// Dias da semana selecionados
$dias = isset($_POST["dias"]) ? $_POST["dias"] : array();

// Dificuldades selecionadas
$dificuldades = isset($_POST["dif_mobilidade"]) 
    ? $_POST["dif_mobilidade"] 
    : array();


// Transformar os dias em texto
$diasTreino = implode(", ", $dias);


// Verificar se o tipo de exercício foi preenchido
if ($tipo == "") {
    header("Location: exercicio.php?titulo=Atenção!&mensagem=Escolha o tipo de exercício.");
    exit;
}


// Verificar se o usuário já possui um treino
$sqlTreino = mysql_query("
    SELECT id_treino
    FROM treinos
    WHERE id_usuario = '$id_usuario'
");

if (!$sqlTreino) {
    die("Erro ao consultar treino: " . mysql_error());
}


// Se já existe treino, atualizar
if (mysql_num_rows($sqlTreino) > 0) {

    $dadosTreino = mysql_fetch_array($sqlTreino);
    $id_treino = $dadosTreino["id_treino"];

    $sqlAtualizar = mysql_query("
        UPDATE treinos
        SET tipo_exercicio = '$tipo',
            horario_inicio = '$hrInicio',
            horario_fim = '$hrFim',
            duracao = '$duracao',
            local = '$local',
            dias_treino = '$diasTreino',
            dias_total = '$diasTotal'
        WHERE id_treino = '$id_treino'
    ");

    if (!$sqlAtualizar) {
        die("Erro ao atualizar treino: " . mysql_error());
    }

} else {

    // Criar novo treino
    $sqlInserir = mysql_query("
        INSERT INTO treinos
        (
            id_usuario,
            tipo_exercicio,
            horario_inicio,
            horario_fim,
            duracao,
            local,
            dias_treino,
            dias_total
        )
        VALUES
        (
            '$id_usuario',
            '$tipo',
            '$hrInicio',
            '$hrFim',
            '$duracao',
            '$local',
            '$diasTreino',
            '$diasTotal'
        )
    ");

    if (!$sqlInserir) {
        die("Erro ao cadastrar treino: " . mysql_error());
    }

    // Pegar o ID do treino criado
    $id_treino = mysql_insert_id();
}


// Apagar dificuldades antigas desse treino
$sqlDelete = mysql_query("
    DELETE FROM treino_dificuldade
    WHERE id_treino = '$id_treino'
");

if (!$sqlDelete) {
    die("Erro ao atualizar dificuldades: " . mysql_error());
}


// Inserir as novas dificuldades
if (!empty($dificuldades)) {

    foreach ($dificuldades as $id_dificuldade) {

        $sqlDificuldade = mysql_query("
            INSERT INTO treino_dificuldade
            (
                id_treino,
                id_dificuldade
            )
            VALUES
            (
                '$id_treino',
                '$id_dificuldade'
            )
        ");

        if (!$sqlDificuldade) {
            die("Erro ao cadastrar dificuldade: " . mysql_error());
        }
    }
}


// Voltar para a página do treino
header("Location: rotina_exercicios.php");
exit;

?>

