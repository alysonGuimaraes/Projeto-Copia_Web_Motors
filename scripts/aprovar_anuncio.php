<?php
include('../configs/dbconnection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['nivelUsuario'] === 'ADM') {
    $id = $_POST['id'];
    $acao = $_POST['acao'];

    $flag = ($acao === 'aprovar') ? 1 : 0;

    $query = "UPDATE anuncios SET flg_situacao = $flag WHERE id = $id";
    mysqli_query($con, $query);
}

header("Location: ../index.php");
exit;
