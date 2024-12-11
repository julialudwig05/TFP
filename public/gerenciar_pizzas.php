<?php
namespace julia\tfp;
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

// Incluir o arquivo necessário para trabalhar com a classe Pizza
require_once __DIR__ . '/src/Pizza.php';

// Verifica se o usuário é um gerente (exemplo de login de gerente com e-mail e senha fixos)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['email'] != 'admin@aluno.feliz.ifrs.edu.br') {
    $_SESSION['erro'] = 'Acesso restrito a gerentes!';
    header('Location: login.php');
    exit;
}

// Ação de excluir pizza
if (isset($_GET['excluir'])) {
    $pizzaId = (int)$_GET['excluir'];
    try {
        $pizza = \julia\tfp\Pizza::find($pizzaId);
        $pizza->delete(); // Exclui a pizza do banco de dados
        $_SESSION['sucesso'] = 'Pizza excluída com sucesso!';
    } catch (Exception $e) {
        $_SESSION['erro'] = 'Erro ao excluir pizza: ' . $e->getMessage();
    }
    header('Location: gerenciar_pizzas.php');
    exit;
}

// Ação de adicionar ou editar pizza
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $imagem = $_POST['imagem'] ?? '';

    // Validação básica
    if (empty($nome) || empty($descricao) || empty($imagem)) {
        $_SESSION['erro'] = 'Todos os campos são obrigatórios!';
        header('Location: gerenciar_pizzas.php');
        exit;
    }

    try {
        if ($id) {
            // Editando pizza existente
            $pizza = \julia\tfp\Pizza::find($id);
            $pizza->setNome($nome);
            $pizza->setDescricao($descricao);
            $pizza->setImagem($imagem);
            $pizza->save(); // Salva a pizza editada
            $_SESSION['sucesso'] = 'Pizza editada com sucesso!';
        } else {
            // Adicionando nova pizza
            $pizza = new \julia\tfp\Pizza($nome, $descricao, $imagem);
            $pizza->save(); // Salva a nova pizza
            $_SESSION['sucesso'] = 'Pizza adicionada com sucesso!';
        }
    } catch (Exception $e) {
        $_SESSION['erro'] = 'Erro ao salvar pizza: ' . $e->getMessage();
    }

    header('Location: gerenciar_pizzas.php');
    exit;
}

// Carregar todas as pizzas para exibição
$pizzas = \julia\tfp\Pizza::findAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Pizzas - Ranking Pizzas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Gerenciar Pizzas</h1>

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

        <!-- Formulário para adicionar ou editar pizza -->
        <h2>Adicionar/Editar Pizza</h2>
        <form action="gerenciar_pizzas.php" method="POST">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" required></textarea>
            </div>

            <div class="form-group">
                <label for="imagem">URL da Imagem</label>
                <input type="text" id="imagem" name="imagem" required>
            </div>

            <input type="hidden" name="id" id="id">

            <button type="submit">Salvar Pizza</button>
        </form>

        <!-- Lista de pizzas cadastradas -->
        <h2>Lista de Pizzas</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pizzas as $pizza): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pizza['nome']); ?></td>
                    <td><?php echo htmlspecialchars($pizza['descricao']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($pizza['imagem']); ?>" alt="Pizza" width="100"></td>
                    <td>
                        <a href="gerenciar_pizzas.php?editar=<?php echo $pizza['id']; ?>">Editar</a>
                        <a href="gerenciar_pizzas.php?excluir=<?php echo $pizza['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta pizza?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Função para preencher o formulário de edição quando clicar em "Editar"
        <?php if (isset($_GET['editar'])): ?>
        const pizzaId = <?php echo $_GET['editar']; ?>;
        const pizza = <?php echo json_encode($pizzas); ?>.find(p => p.id === pizzaId);
        document.getElementById('nome').value = pizza.nome;
        document.getElementById('descricao').value = pizza.descricao;
        document.getElementById('imagem').value = pizza.imagem;
        document.getElementById('id').value = pizza.id;
        <?php endif; ?>
    </script>
</body>
</html>
