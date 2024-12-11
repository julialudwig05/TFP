<?php

namespace julia\tfp;
require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ . '/ActiveRecord.php';
require_once __DIR__ . '/MySQL.php';

class Pizza implements ActiveRecord
{
    public $id;
    public $nome;
    public $descricao;

    public static function findAll($ordenacao = 'desc')
{
    // Conexão usando a classe global mysqli
    $conn = new \mysqli("localhost", "root", "", "ranking_pizzas");

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Ordenação segura para evitar SQL Injection
    $ordem = ($ordenacao === 'asc') ? 'ASC' : 'DESC';

    $query = "SELECT id, nome, imagem, classificacoes FROM pizzas ORDER BY classificacoes $ordem";
    $result = $conn->query($query);

    $pizzas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pizzas[] = $row;
        }
    }

    $conn->close();
    return $pizzas;
}
    

    public static function findById($id)
    {
        $mysql = new MySQL();
        $sql = "SELECT * FROM pizzas WHERE id = ?";
        $resultados = $mysql->consulta($sql, [$id]);
        return $resultados[0] ?? null;
    }

    public function save()
    {
        $mysql = new MySQL();
        if ($this->id) {
            $sql = "UPDATE pizzas SET nome = ?, descricao = ? WHERE id = ?";
            $mysql->consulta($sql, [$this->nome, $this->descricao, $this->id]);
        } else {
            $sql = "INSERT INTO pizzas (nome, descricao) VALUES (?, ?)";
            $mysql->consulta($sql, [$this->nome, $this->descricao]);
        }
    }

    public function delete()
    {
        if ($this->id) {
            $mysql = new MySQL();
            $sql = "DELETE FROM pizzas WHERE id = ?";
            $mysql->consulta($sql, [$this->id]);
        }
    }
}
