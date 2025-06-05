<?php
include('../configs/dbconnection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_usuario'])) {
    $id = $_POST['id'];
    $id_usuario = $_SESSION['id_usuario'];

    // Só pode excluir o que é dele
    $query = "DELETE FROM veiculos WHERE id = $id AND user_id = $id_usuario";
    mysqli_query($con, $query);
}

header("Location: ../index.php");
exit;
