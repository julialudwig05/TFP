<?php
// Exibir erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['admin'])) {
    header('Location: loginadmin.php'); // Redireciona para o login caso não esteja logado
    exit;
}

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ranking_pizzas";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o ID foi passado pela URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin_index.php'); // Redireciona se o ID não for válido
    exit;
}

$id = intval($_GET['id']); // Garante que o ID seja um número inteiro

// Recuperar dados da pizza
$query = $conn->prepare("SELECT * FROM pizzas WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $pizza = $result->fetch_assoc();
} else {
    header('Location: admin_index.php'); // Redireciona se a pizza não for encontrada
    exit;
}

// Processar formulário de edição de pizza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $classificacao = trim($_POST['classificacao']);

    // Verificação de upload de imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagemTmpPath = $_FILES['imagem']['tmp_name'];
        $imagemNome = basename($_FILES['imagem']['name']);
        $imagemDiretorio = 'uploads/';
        $imagemCaminho = $imagemDiretorio . $imagemNome;

        // Move a imagem para o diretório de upload
        if (move_uploaded_file($imagemTmpPath, $imagemCaminho)) {
            $imagemPath = $imagemCaminho;
        } else {
            $erro = "Erro ao fazer upload da imagem.";
        }
    } else {
        $imagemPath = $pizza['imagem']; // Mantém a imagem atual se nenhuma nova for enviada
    }

    if (!empty($nome)) {
        // Atualizar pizza no banco de dados
        $updateQuery = $conn->prepare("UPDATE pizzas SET nome = ?, classificacoes = ?, imagem = ? WHERE id = ?");
        $updateQuery->bind_param("sssi", $nome, $classificacao, $imagemPath, $id);

        if ($updateQuery->execute()) {
            header('Location: admin_index.php'); // Redireciona para o painel de administração após editar
            exit;
        } else {
            $erro = "Erro ao editar a pizza: " . $conn->error;
        }
    } else {
        $erro = "Preencha os campos corretamente.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item - Ranking de Pizzas</title>
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
        <h2>Editar Pizza</h2>

        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form action="editar_pizzas.php?id=<?php echo $pizza['id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome da Pizza:</label>
                <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($pizza['nome']); ?>">
            </div>
            <div class="form-group">
                <label for="classificacao">Classificação:</label>
                <input type="text" name="classificacao" id="classificacao" value="<?php echo htmlspecialchars($pizza['classificacoes']); ?>">
            </div>
            <div class="form-group">
                <label for="imagem">Imagem da Pizza:</label>
                <input type="file" name="imagem" id="imagem">
                <?php if (!empty($pizza['imagem'])): ?>
                    <p>Imagem atual:</p>
                    <img src="<?php echo htmlspecialchars($pizza['imagem']); ?>" alt="Imagem da Pizza" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <button type="submit">Salvar Alterações</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Ranking de Pizzas - Todos os direitos reservados</p>
    </footer>
</body>
</html>
