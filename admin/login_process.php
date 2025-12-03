<?php
session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login_admin.php");
    exit;
}

$conn = Database::connect();

$email = trim($_POST["email"] ?? "");
$senha = trim($_POST["senha"] ?? "");

$stmt = $conn->prepare("
    SELECT id, nome, email, senha, tipo
    FROM usuarios
    WHERE email = ?
      AND tipo = 'admin'
    LIMIT 1
");
$stmt->execute([$email]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin && password_verify($senha, $admin["senha"])) {

    session_regenerate_id(true);

    $_SESSION["user"] = [
        "id"    => $admin["id"],
        "nome"  => $admin["nome"],
        "email" => $admin["email"],
        "tipo"  => $admin["tipo"]
    ];

    header("Location: painel.php");
    exit;
}

header("Location: login_admin.php?erro=1");
exit;
