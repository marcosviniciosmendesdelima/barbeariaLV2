<?php
session_start();
require_once "../config/db.php";

$conn = Database::connect();

// Aceita somente requisição POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login_admin.php");
    exit;
}

$email = trim($_POST["email"] ?? "");
$senha = trim($_POST["senha"] ?? "");

// Buscar admin na TABELA CORRETA (usuarios)
$stmt = $conn->prepare("
    SELECT * 
    FROM usuarios 
    WHERE email = ? 
      AND tipo = 'admin'
    LIMIT 1
");
$stmt->execute([$email]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Validação
if ($admin && password_verify($senha, $admin["senha"])) {

    // Cria sessão padronizada LV2
    $_SESSION["user"] = [
        "id"    => $admin["id"],
        "nome"  => $admin["nome"],
        "email" => $admin["email"],
        "tipo"  => $admin["tipo"]
    ];

    header("Location: painel.php");
    exit;
}

// Login falhou
header("Location: login_admin.php?erro=1");
exit;
