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

// Usando a classe mysqli diretamente
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para adicionar nova pizza com imagem
if (isset($_POST['adicionar'])) {
    $nome = $_POST['nome'];
    $classificacao = $_POST['classificacao'];

    // Verificar se um arquivo de imagem foi enviado
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $imagemTemp = $_FILES['imagem']['tmp_name'];
        $imagemNome = basename($_FILES['imagem']['name']);
        $caminhoDestino = 'uploads/' . $imagemNome;

        // Criar pasta de upload se não existir
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        // Mover o arquivo para o destino
        if (move_uploaded_file($imagemTemp, $caminhoDestino)) {
            // Inserir no banco de dados
            $query = "INSERT INTO pizzas (nome, classificacoes, imagem) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sis", $nome, $classificacao, $caminhoDestino); // 's' para string e 'i' para inteiro
            $stmt->execute();

            // Verifica se a inserção foi bem-sucedida
            if ($stmt->affected_rows > 0) {
                header('Location: admin_index.php');
                exit;
            } else {
                echo "Erro ao adicionar a pizza.";
            }
        } else {
            echo "Erro ao fazer o upload da imagem.";
        }
    } else {
        echo "Erro: nenhuma imagem foi enviada ou houve um problema com o upload.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Pizza</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Painel do Administrador - Adicionar Pizza</h1>
        <nav>
            <a href="admin_index.php">Voltar</a>
        </nav>
    </header>

    <div class="container">
        <br>
        <h2>Adicionar Nova Pizza</h2>

        <form method="POST" action="adicionar_pizza.php" enctype="multipart/form-data">
            <label for="nome">Nome da Pizza:</label>
            <input type="text" name="nome" id="nome" required><br>

            <label for="classificacao">Classificação:</label>
            <input type="number" name="classificacao" id="classificacao" required><br>

            <label for="imagem">Imagem da Pizza:</label>
            <input type="file" name="imagem" id="imagem" accept="image/*" required><br>

            <button type="submit" name="adicionar">Adicionar Pizza</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Ranking de Pizzas - Todos os direitos reservados</p>
    </footer>
</body>
</html>
