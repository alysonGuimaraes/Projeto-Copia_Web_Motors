<?php
include('../configs/dbconnection.php');
session_start();

if (isset($_REQUEST['botao']) && $_REQUEST['botao'] == 'Entrar') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_encrypted = md5($password);

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password_encrypted'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($linha = mysqli_fetch_array($result)) {
            $_SESSION["id_usuario"] = $linha["id"];
            $_SESSION["nome_usuario"] = $linha["username"];
            $_SESSION["nivelUsuario"] = $linha["level"];

            header("Location: ../index.php");
            exit;
        }
    } else {
        echo "<script>alert('Usuário não existe ou senha incorreta! Tente novamente.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link rel="stylesheet" href="../styles/login.css">
</head>
<body>

  <div class="header"></div>

  <div class="login-container">
    <form method="POST" action="" class="login-box">
      <div class="email-login">
        <h2>Digite o seu nome de usuário e senha</h2>
        <input type="text" placeholder="Usuário" name="username" required />
        <input type="password" placeholder="Senha" name="password" required />
        <button type="submit" class="login-btn" name="botao" value="Entrar">Entrar</button>
        <button type="button" class="btn-voltar" onclick="window.history.back();">Voltar</button>
        <p class="register">Não tem uma conta? <a href="cadastro_user.php">Crie a sua</a></p>
      </div>
    </form>
  </div>

</body>
</html>
