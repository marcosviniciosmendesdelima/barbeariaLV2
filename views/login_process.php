<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

// Conexão segura PDO
$conn = Database::connect();

// Sanitização
$email = filter_var(trim($_POST["email"] ?? ""), FILTER_VALIDATE_EMAIL);
$senha = trim($_POST["senha"] ?? "");

// Se faltar informação → erro
if (!$email || empty($senha)) {
    header("Location: login.php?erro=1");
    exit;
}

// Buscar usuário no banco
$stmt = $conn->prepare("SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if ($usuario) {

    // Verificar senha hash
    if (password_verify($senha, $usuario["senha"])) {

        // Registra sessão do cliente
        $_SESSION["cliente_id"]    = $usuario["id"];
        $_SESSION["cliente_nome"]  = $usuario["nome"];
        $_SESSION["cliente_email"] = $usuario["email"];
        $_SESSION["tipo"]          = $usuario["tipo"] ?? "cliente";

        // Redirecionamento inteligente:
        if ($usuario["tipo"] === "cliente") {
            header("Location: agendar.php");
        } elseif ($usuario["tipo"] === "barbeiro") {
            header("Location: ../barbeiro/painel.php");
        } elseif ($usuario["tipo"] === "admin") {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: agendar.php");
        }

        exit;
    }
}

// Se chegou aqui é porque deu erro
header("Location: login.php?erro=1");
exit;
