<?php
session_start();
require_once "../config/db.php";

$conn = Database::connect();

// Apenas POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login_admin.php");
    exit;
}

$email = trim($_POST["email"] ?? "");
$senha = trim($_POST["senha"] ?? "");

// Busca admin na tabela USUARIOS
$stmt = $conn->prepare("
    SELECT * FROM usuarios 
    WHERE email = ? 
      AND tipo = 'admin'
    LIMIT 1
");
$stmt->execute([$email]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar senha
if ($admin && password_verify($senha, $admin["senha"])) {

    // Mantém a sessão padrão
    $_SESSION["user"] = [
        "id"    => $admin["id"],
        "nome"  => $admin["nome"],
        "email" => $admin["email"],
        "tipo"  => $admin["tipo"]
    ];

    // Sessão específica usada pelo painel
    $_SESSION["admin_id"]   = $admin["id"];
    $_SESSION["admin_nome"] = $admin["nome"];
    $_SESSION["admin_email"]= $admin["email"];

    header("Location: painel.php");
    exit;
}

// Se falhar login
header("Location: login_admin.php?erro=1");
exit;
