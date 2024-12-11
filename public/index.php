<?php

require_once __DIR__ . '/../vendor/autoload.php';

use julia\tfp\Pizza;
session_start();
$usuarioLogado = $_SESSION['usuario'] ?? null;

// Obtém todas as pizzas do banco
$pizzas = Pizza::findAll();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking de Itens</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>Ranking de Itens</h1>
        </div>
        <nav>
            <a href="index.php">Início</a>
            <a href="logout.php">Sair</a>
            <span>Bem-vindo, <?= htmlspecialchars($usuarioLogado['nome']) ?>!</span>
        </nav>
    </header>

    <div class="container">
        <br>
        <h2>Itens Mais Classificados</h2>

        <section class="pizza-list">
            <?php foreach ($pizzas as $pizza): ?>
                <div class="pizza-card">
                    <!-- Exibe a imagem com caminho absoluto ou relativo -->
                    <img src="<?= htmlspecialchars($pizza['imagem']) ?>" 
                         alt="<?= htmlspecialchars($pizza['nome']) ?>" 
                         onerror="this.src='assets/img/placeholder.png';">
                    <div class="pizza-info">
                        <h3><?= htmlspecialchars($pizza['nome']) ?></h3>
                        <p>Classificação: <?= htmlspecialchars($pizza['classificacoes']) ?></p>
                        <button class="classificar-btn" data-id="<?= $pizza['id'] ?>">Classificar</button> 
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </div>

    <footer>
        <p>&copy; 2024 Sistema de Ranking de Itens | <a href="mailto:suporte@exemplo.com">Suporte</a></p>
    </footer>
</body>
</html>
