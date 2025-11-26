<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$conn = Database::connect();

$email = filter_var(trim($_POST["email"] ?? ""), FILTER_VALIDATE_EMAIL);
$senha = trim($_POST["senha"] ?? "");

if (!$email || empty($senha)) {
    header("Location: login.php?erro=1");
    exit;
}

$stmt = $conn->prepare("SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if ($usuario && password_verify($senha, $usuario["senha"])) {

    $_SESSION["cliente_id"]    = $usuario["id"];
    $_SESSION["cliente_nome"]  = $usuario["nome"];
    $_SESSION["cliente_email"] = $usuario["email"];
    $_SESSION["tipo"]          = $usuario["tipo"] ?? "cliente";

    if ($usuario["tipo"] === "cliente") {
        header("Location: ../admin/servicos.php");
    } else {
        header("Location: ../admin/servicos.php");
    }

    exit;
}

header("Location: login.php?erro=1");
exit;
