<?php
session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login_admin.php");
    exit;
}

$conn = Database::connect();

$email = filter_var(trim($_POST["email"] ?? ""), FILTER_VALIDATE_EMAIL);
$senha = trim($_POST["senha"] ?? "");

if (!$email || $senha === "") {
    header("Location: login_admin.php?erro=1");
    exit;
}

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
        "id"    => (int) $admin["id"],
        "nome"  => $admin["nome"],
        "email" => $admin["email"],
        "tipo"  => $admin["tipo"]
    ];

    $_SESSION["admin_id"]    = (int) $admin["id"];
    $_SESSION["admin_nome"]  = $admin["nome"];
    $_SESSION["admin_email"] = $admin["email"];

    header("Location: painel.php");
    exit;
}

header("Location: login_admin.php?erro=1");
exit;
