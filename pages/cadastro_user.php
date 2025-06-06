<?php

require('../configs/dbconnection.php');

if (isset($_REQUEST['botao']) && $_REQUEST['botao'] == 'Cadastrar') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $phone = $_POST['telefone'];
    $email = $_POST['email'];
    $gender = $_POST['genero'];
    $hashed_password = md5($password);

    $query = "INSERT INTO users (username, password, phone, email, gender, level) VALUES ('$username', '$hashed_password', '$phone', '$email', '$gender', 'USR')";
    $result = mysqli_query($con, $query);

    if ($result) {
        echo "<script>alert('Usuário cadastrado com sucesso! Redirecionando para login...'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Ocorreu um erro ao cadastrar o usuário! Tente novamente mais tarde.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastrar</title>
  <link rel="stylesheet" href="../styles/cadastro_user.css">
</head>
<body>

  <div class="header"></div>

  <div class="login-container">
    <form method="POST" action="" class="login-box">
      <div class="email-login">
        <h2>Cadastro de Usuário</h2>

        <input type="text" placeholder="Nome de usuário" name="username" required />
        <input type="password" placeholder="Senha" name="password" required />
        <input type="tel" placeholder="Telefone" name="telefone" required />
        <input type="email" placeholder="E-mail" name="email" required />

        <select name="genero" required>
          <option value="" disabled selected>Selecione o Gênero</option>
          <option value="masculino">Masculino</option>
          <option value="feminino">Feminino</option>
          <option value="outro">Outro</option>
        </select>

        <button type="submit" class="login-btn" name="botao" value="Cadastrar">Cadastrar</button>
        <button type="button" onclick="window.history.back();" class="btn-voltar">Voltar</button>

        <p class="register">Já tem uma conta? <a href="login.php">Entre agora</a></p>
      </div>
    </form>
  </div>

</body>
</html>