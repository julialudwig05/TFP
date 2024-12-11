<?php

namespace julia\tfp;
require_once __DIR__ . '/../../vendor/autoload.php';

abstract class Classificacao implements ActiveRecord {

    private $id;
    private $usuario_id;
    private $pizza_id;
    private $avaliacao;
    private $mysql;

    public function __construct($usuario_id, $pizza_id, $avaliacao, $id = null) {
        $this->mysql = new MySQL();
        $this->id = $id;
        $this->usuario_id = $usuario_id;
        $this->pizza_id = $pizza_id;
        $this->avaliacao = $avaliacao;
    }

    public function save(): bool {
        if ($this->id) {
            // Atualizar
            $sql = "UPDATE classificacoes SET usuario_id = ?, pizza_id = ?, avaliacao = ? WHERE id = ?";
            return $this->mysql->executa($sql, [$this->usuario_id, $this->pizza_id, $this->avaliacao, $this->id]);
        } else {
            // Inserir
            $sql = "INSERT INTO classificacoes (usuario_id, pizza_id, avaliacao) VALUES (?, ?, ?)";
            return $this->mysql->executa($sql, [$this->usuario_id, $this->pizza_id, $this->avaliacao]);
        }
    }

    public function delete(): bool {
        if ($this->id) {
            $sql = "DELETE FROM classificacoes WHERE id = ?";
            return $this->mysql->executa($sql, [$this->id]);
        }
        return false;
    }

    public static function find($id): object {
        $mysql = new MySQL();
        $sql = "SELECT * FROM classificacoes WHERE id = ?";
        $resultado = $mysql->consulta($sql);
        return new self($resultado[0]['usuario_id'], $resultado[0]['pizza_id'], $resultado[0]['avaliacao'], $id);
    }

    public static function findAll(): array {
        $mysql = new MySQL();
        $sql = "SELECT * FROM classificacoes";
        $resultado = $mysql->consulta($sql);

        $classificacoes = [];
        foreach ($resultado as $row) {
            $classificacoes[] = new self($row['usuario_id'], $row['pizza_id'], $row['avaliacao'], $row['id']);
        }
        return $classificacoes;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function getPizzaId() {
        return $this->pizza_id;
    }

    public function getAvaliacao() {
        return $this->avaliacao;
    }
    public function setAvaliacao($avaliacao) {
        $this->avaliacao = $avaliacao;
    }
}

?>
