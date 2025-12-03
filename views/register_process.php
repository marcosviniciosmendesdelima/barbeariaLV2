<?php
session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit;
}

$conn = Database::connect();

$nome  = trim($_POST["nome"]  ?? "");
$email = strtolower(trim($_POST["email"] ?? ""));
$senha = trim($_POST["senha"] ?? "");

if ($nome === "" || $email === "" || $senha === "") {
    header("Location: register.php?erro=campo_vazio");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.php?erro=email_invalido");
    exit;
}

if (strlen($senha) < 4) {
    header("Location: register.php?erro=senha_fraca");
    exit;
}

$check = $conn->prepare("
    SELECT id 
    FROM usuarios 
    WHERE email = ? 
    LIMIT 1
");
$check->execute([$email]);

if ($check->fetch()) {
    header("Location: register.php?erro=email_uso");
    exit;
}

$hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO usuarios (nome, email, senha, tipo)
    VALUES (?, ?, ?, 'cliente')
");
$ok = $stmt->execute([$nome, $email, $hash]);

if (!$ok) {
    header("Location: register.php?erro=banco");
    exit;
}

header("Location: login.php?ok=1");
exit;
