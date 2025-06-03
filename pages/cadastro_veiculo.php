<?php

session_start();
if (!isset($_SESSION["id_usuario"])){
    echo "<script>alert('Você não está logado! Redirecionando para login...');top.location.href='login.php';</script>";
}

// $nome = $_POST['nome'];
// $marca = $_POST['marca'];
// $ano = $_POST['ano'];
// $quilometragem = $_POST['quilometragem'];

// if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
//     $imgNome = basename($_FILES['imagem']['name']);
//     $imgPath = 'uploads/' . $imgNome;

//     if (move_uploaded_file($_FILES['imagem']['tmp_name'], $imgPath)) {
//         $stmt = $conn->prepare("INSERT INTO veiculos (nome, marca, ano, quilometragem, imagem) VALUES (?, ?, ?, ?, ?)");
//         $stmt->bind_param("ssiss", $nome, $marca, $ano, $quilometragem, $imgPath);

//         if ($stmt->execute()) {
//             echo "Veículo cadastrado com sucesso!";
//         } else {
//             echo "Erro ao cadastrar: " . $stmt->error;
//         }
//         $stmt->close();
//     } else {
//         echo "Erro ao mover imagem.";
//     }
// } else {
//     echo "Imagem inválida.";
// }
// $conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Veículo</title>
    <link rel="stylesheet" href="../styles/cadastro_veiculo.css">
</head>
<body>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <h2>Cadastro de Veículo</h2>

        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>

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
