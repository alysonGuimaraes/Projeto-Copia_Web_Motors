<?php

include('../configs/dbconnection.php');

session_start();
if (!isset($_SESSION["id_usuario"])){
    echo "<script>alert('Você não está logado! Redirecionando para login...');top.location.href='login.php';</script>";
}

if (isset($_REQUEST['botao']) && $_REQUEST['botao'] == 'Cadastrar') {
    $modelo = $_POST["modelo"];
    $marca = $_POST["marca"];
    $ano = $_POST["ano"];
    $quilometragem = $_POST["quilometragem"];
    $anunciante = $_SESSION["id_usuario"];

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imagem_nome = basename($_FILES['imagem']['name']);
        $imagem_destino = "../uploads/" . $imagem_nome;
        $rota_imagem = "uploads/" . $imagem_nome;

        // Cria pasta se não existir
        if (!file_exists("../uploads")) {
            mkdir("../uploads", 0777, true);
        }

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $imagem_destino)) {
            $query = "INSERT INTO anuncios (modelo, marca, ano, quilometragem, imagem, user_id) 
                    VALUES ('$modelo', '$marca', '$ano', $quilometragem, '$rota_imagem', $anunciante)";

            $result = mysqli_query($con, $query);

            if ($result) {
                echo "<script>alert('Veículo cadastrado com sucesso!'); window.location.href='../index.php';</script>";
            } else {
                echo "Erro ao cadastrar: " . mysqli_error($con);
            }

        } else {
            echo "Erro ao fazer upload da imagem.";
        }

    } else {
        echo "<p style='color: red;'>Imagem inválida ou não enviada.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Veículo</title>
    <link rel="stylesheet" href="../styles/cadastro_anuncio.css">
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <h2>Cadastro de Veículo</h2>

        <label for="nome">Modelo:</label>
        <input type="text" name="modelo" id="modelo" required>

        <label for="marca">Marca:</label>
        <input type="text" name="marca" id="marca" required>

        <label for="ano">Ano:</label>
        <input type="number" name="ano" id="ano" required>

        <label for="quilometragem">Quilometragem:</label>
        <input type="number" name="quilometragem" id="quilometragem" required>

        <label for="imagem">Imagem:</label>
        <input type="file" name="imagem" id="imagem" accept="image/*" required>

        <button type="submit" name="botao" value="Cadastrar" class="btn-cadastro">Cadastrar</button>
        <button type="button" onclick="window.history.back();" class="btn-voltar">Voltar</button>
    </form>
</body>
</html>
