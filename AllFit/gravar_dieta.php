<?php

session_start();

include 'conexao.php';

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

$valor = $_POST["txt_valor"];
$grupo = $_POST["grupo_alimentar"];
$calorias = $_POST["txt_calorias"];

$hora_cafe = $_POST["hora_cafe"];
$hora_almoco = $_POST["hora_almoco"];
$hora_janta = $_POST["hora_janta"];

$ciclo = $_POST["ciclo"];

$usa_suplemento = $_POST["usa_suplemento"];

$suplemento = "";

if ($usa_suplemento == "sim") {
    $suplemento = $_POST["suplemento"];
}

$alimentos = $_POST["alimentos_nao_consumidos"];

$restricoes = array();

if (isset($_POST["restricoes"])) {
    $restricoes = $_POST["restricoes"];
}


/*
 * Validação
 */

if (
    $valor == "" ||
    $grupo == "" ||
    $calorias == "" ||
    $hora_cafe == "" ||
    $hora_almoco == "" ||
    $hora_janta == "" ||
    $ciclo == "" ||
    $usa_suplemento == ""
) {

    header(
        "Location: dieta.php?titulo=Atenção!&mensagem=Preencha todos os campos!"
    );

    exit;
}


/*
 * Converte o valor do radio para 0 ou 1.
 */

if ($usa_suplemento == "sim") {
    $usa_suplemento = 1;
}
else {
    $usa_suplemento = 0;
}


/*
 * Insere a dieta.
 */

$sql = mysql_query("
    INSERT INTO dietas
    (
        id_usuario,
        valor_maximo,
        grupo_alimentar,
        meta_calorias,
        hora_cafe,
        hora_almoco,
        hora_janta,
        ciclo,
        usa_suplemento,
        suplemento,
        alimentos_nao_consumidos
    )
    VALUES
    (
        '$id_usuario',
        '$valor',
        '$grupo',
        '$calorias',
        '$hora_cafe',
        '$hora_almoco',
        '$hora_janta',
        '$ciclo',
        '$usa_suplemento',
        '$suplemento',
        '$alimentos'
    )
");


if (!$sql) {

    die(
        "Erro ao salvar a dieta: "
        . mysql_error()
    );

}


/*
 * Pega o ID da dieta recém-criada.
 */

$id_dieta = mysql_insert_id();


/*
 * Salva as restrições da dieta.
 */

foreach ($restricoes as $id_restricao) {

    $sql = mysql_query("
        INSERT INTO dieta_restricao
        (
            id_dieta,
            id_restricao
        )
        VALUES
        (
            '$id_dieta',
            '$id_restricao'
        )
    ");


    if (!$sql) {

        die(
            "Erro ao salvar a restrição: "
            . mysql_error()
        );

    }

}


/*
 * Tudo certo.
 */

header(
    "Location: rotina_dieta.php?titulo=Sucesso!&mensagem=Dieta salva com sucesso!"
);

exit;

?>