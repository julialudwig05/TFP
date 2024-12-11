<?php

namespace julia\tfp;

class MySQL
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = new \mysqli('localhost', 'root', '', 'ranking_pizzas');
        if ($this->conexao->connect_error) {
            throw new \Exception('Erro na conexão: ' . $this->conexao->connect_error);
        }
    }

    // Método para realizar consultas SELECT
    public function consulta($sql, $params = [])
    {
        $stmt = $this->conexao->prepare($sql);
        if ($stmt === false) {
            throw new \Exception('Erro na preparação da consulta: ' . $this->conexao->error);
        }

        if (!empty($params)) {
            $tipos = str_repeat('s', count($params));  // Assumindo que os parâmetros são do tipo string
            $stmt->bind_param($tipos, ...$params);
        }

        if (!$stmt->execute()) {
            throw new \Exception('Erro na execução da consulta: ' . $stmt->error);
        }

        $resultado = $stmt->get_result();
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Método para executar INSERT, UPDATE, DELETE
    public function executa($sql, $params = [])
    {
        $stmt = $this->conexao->prepare($sql);
        if ($stmt === false) {
            throw new \Exception('Erro na preparação da consulta: ' . $this->conexao->error);
        }

        if (!empty($params)) {
            $tipos = str_repeat('s', count($params));  // Assumindo que os parâmetros são do tipo string
            $stmt->bind_param($tipos, ...$params);
        }

        if (!$stmt->execute()) {
            throw new \Exception('Erro na execução da consulta: ' . $stmt->error);
        }

        return true;
    }
}
?>