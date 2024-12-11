<?php
require_once __DIR__ . '/../vendor/autoload.php';
use julia\tfp\Pizza;

header('Content-Type: application/json');

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método inválido.']);
    exit;
}

$pizzaId = $_POST['id'] ?? null;
if (!$pizzaId) {
    echo json_encode(['error' => 'ID inválido. Nenhum ID foi enviado.']);
    exit;
}

try {
    $pizza = Pizza::find($pizzaId);
    if ($pizza) {
        $pizza['classificacoes'] += 1;
        Pizza::update($pizzaId, $pizza);
        echo json_encode(['success' => true, 'classificacoes' => $pizza['classificacoes']]);
    } else {
        echo json_encode(['error' => 'Pizza não encontrada. ID: ' . $pizzaId]);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Erro no servidor: ' . $e->getMessage()]);
}