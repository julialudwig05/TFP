<?php

namespace julia\tfp;
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

// Incluir o arquivo necessário para trabalhar com a classe Usuario
require_once __DIR__ . '/src/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Validação básica
    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['erro'] = 'Todos os campos são obrigatórios!';
        header('Location: cadastro.php');
        exit;
    }

    // Verifica se o e-mail está no domínio permitido
    if (!preg_match('/@aluno\.feliz\.ifrs\.edu\.br$/', $email)) {
        $_SESSION['erro'] = 'O e-mail deve ser do domínio @aluno.feliz.ifrs.edu.br';
        header('Location: cadastro.php');
        exit;
    }

    // Criptografando a senha
    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

    try {
        // Cria um novo usuário
        $usuario = new \julia\tfp\Usuario($nome, $email, $senhaCriptografada);
        $usuario->save();  // Salva no banco

        // Redireciona para o login com sucesso
        $_SESSION['sucesso'] = 'Cadastro realizado com sucesso!';
        header('Location: login.php');
        exit;
    } catch (Exception $e) {
        // Se ocorrer algum erro no banco de dados
        $_SESSION['erro'] = 'Erro ao cadastrar usuário: ' . $e->getMessage();
        header('Location: cadastro.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Ranking Pizzas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Usuário</h1>
        
        <?php
        // Exibe mensagens de erro ou sucesso
        if (isset($_SESSION['erro'])) {
            echo "<p class='erro'>{$_SESSION['erro']}</p>";
            unset($_SESSION['erro']);
        }

        if (isset($_SESSION['sucesso'])) {
            echo "<p class='sucesso'>{$_SESSION['sucesso']}</p>";
            unset($_SESSION['sucesso']);
        }
        ?>

        <form action="cadastro.php" method="POST">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <button type="submit">Cadastrar</button>
        </form>

        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
    </div>
</body>
</html>
