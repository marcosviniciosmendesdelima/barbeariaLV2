<?php
require_once __DIR__ . '/../config/db.php';

class Usuario {

    private PDO $pdo;
    private string $table = 'usuarios';

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function create(string $nome, string $email, string $senha, string $tipo = 'cliente'): array
    {
        $email = filter_var(trim($email), FILTER_VALIDATE_EMAIL);

        if (!$email) {
            return ['success' => false, 'message' => 'E-mail inválido'];
        }

        $stmt = $this->pdo->prepare("SELECT id FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'E-mail já cadastrado'];
        }

        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->table} (nome, email, senha, tipo)
            VALUES (:nome, :email, :senha, :tipo)
        ");

        $ok = $stmt->execute([
            ':nome'  => trim($nome),
            ':email' => $email,
            ':senha' => $hash,
            ':tipo'  => $tipo
        ]);

        if ($ok) {
            return [
                'success' => true,
                'id' => $this->pdo->lastInsertId()
            ];
        }

        return ['success' => false, 'message' => 'Erro ao salvar no banco'];
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, nome, email, senha, tipo 
            FROM {$this->table} 
            WHERE email = ? 
            LIMIT 1
        ");
        $stmt->execute([$email]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, nome, email, tipo 
            FROM {$this->table} 
            WHERE id = ? 
            LIMIT 1
        ");
        $stmt->execute([$id]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario ?: null;
    }
}
