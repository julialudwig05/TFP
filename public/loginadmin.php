<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use julia\tfp\MySQL;

// Verifica se o administrador já está logado
if (isset($_SESSION['admin'])) {
    header('Location: admin_index.php'); // Página inicial do admin
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Verificando se o campo nome e senha são preenchidos corretamente
    if (empty($nome) || empty($senha)) {
        $_SESSION['erro'] = 'Por favor, preencha todos os campos.';
        header('Location: loginadmin.php');
        exit;
    }

    // Verifica se o nome e senha são os do administrador
    if ($nome === 'admin' && $senha === 'senha123') {
        $_SESSION['admin'] = $nome; // Sessão para o admin
        header('Location: admin_index.php'); // Redireciona para o painel do administrador
        exit;
    }

    $_SESSION['erro'] = 'Nome ou senha incorretos!';
    header('Location: loginadmin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administrador</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Login - Administrador</h1>

    <?php if (isset($_SESSION['erro'])): ?>
        <p class="erro"><?= htmlspecialchars($_SESSION['erro']) ?></p>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>

    <form method="POST" action="loginadmin.php">
        <input type="text" name="nome" placeholder="Nome de Usuário" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>