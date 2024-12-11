<?php
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['admin'])) {
    header('Location: loginadmin.php'); // Redireciona para o login caso não esteja logado
    exit;
}

$admin = $_SESSION['admin']; // Obter os dados do administrador logado

// Conectar ao banco de dados com MySQLi
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ranking_pizzas";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obter itens classificados
$order = isset($_GET['order']) ? $_GET['order'] : 'desc'; // Verifica a ordem
$orderQuery = $order === 'asc' ? 'ASC' : 'DESC';

$query = "SELECT * FROM pizzas ORDER BY classificacoes $orderQuery";
$result = $conn->query($query);

// Função para excluir pizza
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $deleteQuery = "DELETE FROM pizzas WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header('Location: admin_index.php');
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador - Ranking de Pizzas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Painel do Administrador</h1>
        <nav>
            <a href="admin_index.php">Início</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <div class="container">
        <br>
        <h2>Bem-vindo, Administrador!</h2>

        <h3>Visualizar Ranking de Pizzas</h3>

        <form method="GET" action="admin_index.php">
            <label for="order">Ordenar por classificação:</label>
            <select name="order" id="order" onchange="this.form.submit()">
                <option value="desc" <?php echo $order === 'desc' ? 'selected' : ''; ?>>Decrescente</option>
                <option value="asc" <?php echo $order === 'asc' ? 'selected' : ''; ?>>Crescente</option>
            </select>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Classificação</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pizza = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pizza['nome']); ?></td>
                        <td><?php echo htmlspecialchars($pizza['classificacoes']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($pizza['imagem']); ?>" alt="Imagem da Pizza" width="100"></td>
                        <td>
                            <a href="editar_pizzas.php?id=<?php echo $pizza['id']; ?>" class="btn">Editar</a>
                            <form method="POST" action="admin_index.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $pizza['id']; ?>">
                                <button type="submit" name="delete" class="btn">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="adicionar_pizza.php" class="admin-option">Adicionar Novo Item</a>
    </div>

    <footer>
        <p>&copy; 2024 Ranking de Pizzas - Todos os direitos reservados</p>
    </footer>
</body>
</html>