<?php

namespace julia\tfp;

require_once __DIR__ . '/MySQL.php'; // Incluindo a classe MySQL

class Usuario implements ActiveRecord
{
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $mysql;

    public function __construct($nome, $email, $senha, $id = null) {
        $this->mysql = new MySQL();  // Instância da classe MySQL
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
    }

    // Método para salvar (inserir ou atualizar) o usuário
    public function save(): bool {
        if ($this->id) {
            // Se o usuário já tem um ID, atualiza
            $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?";
            return $this->mysql->executa($sql, [$this->nome, $this->email, $this->senha, $this->id]);
        } else {
            // Se não, insere um novo usuário
            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
            return $this->mysql->executa($sql, [$this->nome, $this->email, $this->senha]);
        }
    }

    // Método para deletar o usuário
    public function delete(): bool {
        if ($this->id) {
            $sql = "DELETE FROM usuarios WHERE id = ?";
            return $this->mysql->executa($sql, [$this->id]);
        }
        return false;
    }

    // Método estático para buscar um usuário por e-mail
    public static function findByEmail($email): ?self {
        $mysql = new MySQL();
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $resultado = $mysql->consulta($sql, [$email]);
        
        if (empty($resultado)) {
            return null;  // Retorna null se não encontrar nenhum usuário com esse e-mail
        }
        
        $row = $resultado[0];
        return new self($row['nome'], $row['email'], $row['senha'], $row['id']);
    }

    // Método estático para buscar um usuário por ID
    public static function find($id): ?self {
        $mysql = new MySQL();
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $resultado = $mysql->consulta($sql, [$id]);
        
        if (empty($resultado)) {
            return null;  // Retorna null se não encontrar nenhum usuário com esse ID
        }

        return new self($resultado[0]['nome'], $resultado[0]['email'], $resultado[0]['senha'], $id);
    }

    // Método estático para buscar um usuário por ID (método da interface ActiveRecord)
    public static function findById($id): ?self {
        return self::find($id);  // Retorna o mesmo comportamento do método find()
    }

    // Método estático para buscar todos os usuários
    public static function findAll(): array {
        $mysql = new MySQL();
        $sql = "SELECT * FROM usuarios";
        $resultado = $mysql->consulta($sql);

        $usuarios = [];
        foreach ($resultado as $row) {
            $usuarios[] = new self($row['nome'], $row['email'], $row['senha'], $row['id']);
        }
        return $usuarios;
    }

    // Métodos auxiliares para acessar as propriedades do usuário
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSenha() {
        return $this->senha;
    }
}


?>
