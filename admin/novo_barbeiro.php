<?php
session_start();
require_once "../config/db.php";

$conn = Database::connect();

// --- VALIDAR ADMIN ---
if (!isset($_SESSION["user"]) || $_SESSION["user"]["tipo"] !== "admin") {
    header("Location: login_admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");

    if ($nome === "") {
        header("Location: novo_barbeiro.php?erro=1");
        exit;
    }

    // cadastra barbeiro como usuario tipo = barbeiro
    $stmt = $conn->prepare("
        INSERT INTO usuarios (nome, email, senha, tipo)
        VALUES (?, '', '', 'barbeiro')
    ");
    $stmt->execute([$nome]);

    header("Location: painel.php?barbeiro=ok");
    exit;
}

// carregando personalização
$config = $conn->query("SELECT * FROM configuracao LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$cor  = $config["cor_primaria"] ?? "#ffc400";
$nomeBarbearia = $config["nome_barbeira"] ?? "Barbearia LV2";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Novo Barbeiro - <?= htmlspecialchars($nomeBarbearia) ?></title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
    body{
        margin:0;
        padding:40px;
        background:linear-gradient(-45deg,#000,#111,#1a1a1a,#000);
        background-size:400% 400%;
        animation:bg 14s ease infinite;
        font-family:'Poppins',sans-serif;
        color:white;
    }
    @keyframes bg{
        0%{background-position:0% 50%;}
        50%{background-position:100% 50%;}
        100%{background-position:0% 50%;}
    }

    .box{
        max-width:450px;
        margin:auto;
        background:rgba(255,255,255,0.08);
        padding:35px;
        border-radius:18px;
        border:1px solid rgba(255,255,255,0.15);
        backdrop-filter:blur(12px);
        box-shadow:0 0 25px rgba(255,193,7,.22);
        animation:fade .8s ease;
        opacity:0;
    }
    @keyframes fade{ to{opacity:1;} }

    h2{
        text-align:center;
        color:<?= $cor ?>;
        margin-bottom:20px;
        font-weight:600;
    }

    input{
        width:100%;
        padding:14px;
        border-radius:10px;
        border:1px solid rgba(255,255,255,0.22);
        background:rgba(255,255,255,0.12);
        color:#fff;
        margin-bottom:18px;
        font-size:15px;
        transition:.25s;
    }
    input:focus{
        border-color:<?= $cor ?>;
        box-shadow:0 0 12px <?= $cor ?>88;
        outline:none;
    }

    .btn{
        width:100%;
        padding:14px;
        border:none;
        border-radius:10px;
        background:<?= $cor ?>;
        font-weight:600;
        color:black;
        cursor:pointer;
        font-size:17px;
        transition:.25s;
    }
    .btn:hover{
        background:#ffdd55;
        transform:translateY(-2px);
    }

    .back{
        display:block;
        text-align:center;
        color:<?= $cor ?>;
        font-weight:bold;
        margin-top:14px;
        text-decoration:none;
    }
    .back:hover{
        text-decoration:underline;
    }

    .erro{
        background:#992222;
        padding:8px;
        text-align:center;
        border-radius:8px;
        margin-bottom:15px;
    }

</style>
</head>
<body>

<div class="box">
    <h2>Novo Barbeiro</h2>

    <?php if (isset($_GET["erro"])): ?>
        <div class="erro">Digite um nome válido.</div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nome" placeholder="Nome do barbeiro" required>
        <button class="btn">Cadastrar Barbeiro</button>
    </form>

    <a class="back" href="painel.php">← Voltar ao Painel</a>
</div>

</body>
</html>
