<?php
namespace julia\tfp;
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// Inclui a classe de usuário
require_once '/src/Usuario.php'; // Verifique o caminho correto

// Captura os dados do formulário
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Verifique se ambos os campos foram preenchidos
if (empty($email) || empty($senha)) {
    $_SESSION['erro'] = 'E-mail e senha são obrigatórios.';
    header('Location: login.php');
    exit;
}

// Tenta buscar o usuário pelo e-mail
$usuario = \julia\tfp\Usuario::findByEmail($email);

if ($usuario && password_verify($senha, $usuario->getSenha())) {
    // Senha correta, inicia a sessão do usuário
    $_SESSION['usuario'] = $usuario;
    header('Location: dashboard.php'); // Redireciona para a página principal do usuário
    exit;
} else {
    // Senha ou e-mail incorretos
    $_SESSION['erro'] = 'E-mail ou senha incorretos.';
    header('Location: login.php');
    exit;
}
?>
