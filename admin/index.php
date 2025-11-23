<?php
session_start();
require_once "../config/db.php";

$conn = Database::connect();

// já logado? vai para painel
if (isset($_SESSION["user"]) && $_SESSION["user"]["tipo"] === "admin") {
    header("Location: painel.php");
    exit;
}

// carregar configurações
$config = $conn->query("SELECT * FROM configuracao LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$nomeBarbearia = $config["nome_barbeira"] ?? "Barbearia LV2";
$corPrimaria  = $config["cor_primaria"] ?? "#ffc400";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Admin - <?= htmlspecialchars($nomeBarbearia) ?></title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
    body {
        margin: 0;
        height: 100vh;
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(-45deg,#000,#111,#1a1a1a,#000);
        background-size: 400% 400%;
        animation: bg 15s ease infinite;
        color: #fff;
    }

    @keyframes bg {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .box {
        width: 420px;
        padding: 40px;
        text-align: center;
        border-radius: 18px;
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,0.18);
        box-shadow: 0 0 28px rgba(255,193,7,.22);
        animation: fade .8s ease;
        opacity: 0;
    }

    @keyframes fade {
        to { opacity: 1; }
    }

    .title {
        font-size: 28px;
        margin-bottom: 20px;
        font-weight: 600;
        color: <?= $corPrimaria ?>;
    }

    .btn {
        display: block;
        padding: 14px;
        font-weight: bold;
        text-decoration: none;
        border-radius: 12px;
        margin-top: 12px;
        font-size: 15px;
        transition: .25s;
    }

    .btn-login {
        background: <?= $corPrimaria ?>;
        color: #000;
    }
    .btn-login:hover {
        background: #ffdd55;
        transform: translateY(-2px);
    }

    .btn-sec {
        background: rgba(255,255,255,0.10);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.15);
    }
    .btn-sec:hover {
        background: rgba(255,255,255,0.18);
        transform: translateY(-2px);
    }
</style>

</head>
<body>

<div class="box">

    <div class="title">Área Administrativa</div>

    <a href="login_admin.php" class="btn btn-login">Entrar como Administrador</a>
    <a href="register.php" class="btn btn-sec">Criar Novo Administrador</a>

</div>

</body>
</html>
