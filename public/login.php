<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use julia\tfp\MySQL;

// Verifica se o usuário já está logado
if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $_SESSION['erro'] = 'Por favor, preencha todos os campos.';
        header('Location: login.php');
        exit;
    }

    $mysql = new MySQL();
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $resultado = $mysql->consulta($sql, [$email]);

    if ($resultado) {
        $usuario = $resultado[0];
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario;
            header('Location: index.php');
            exit;
        }
    }

    $_SESSION['erro'] = 'E-mail ou senha incorretos!';
    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ranking de Pizzas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Login</h1>

    <?php if (isset($_SESSION['erro'])): ?>
        <p><?= htmlspecialchars($_SESSION['erro']) ?></p>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>

    <p class="cadastro-link">Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
    
    <p class="admin-link">Se você for um administrador, <a href="loginadmin.php">faça login aqui</a>.</p>
</body>
</html>
