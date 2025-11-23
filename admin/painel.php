<?php
session_start();
require_once "../config/db.php";

$conn = Database::connect();

// validar admin logado
if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

// configurações
$config = $conn->query("SELECT * FROM configuracao LIMIT 1")->fetch(PDO::FETCH_ASSOC);

$corPrimaria   = $config["cor_primaria"] ?? "#ffda44";
$nomeBarbearia = $config["nome_barbeira"] ?? "Barbearia LV2";

// estatísticas
$totalHoje = $conn->query("SELECT COUNT(*) AS t FROM agendamentos WHERE data_agendamento = CURDATE()")->fetch()["t"];
$totalClientes = $conn->query("SELECT COUNT(*) AS t FROM usuarios WHERE tipo='cliente'")->fetch()["t"];
$totalBarbeiros = $conn->query("SELECT COUNT(*) AS t FROM barbeiros")->fetch()["t"];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Painel Administrativo - <?= htmlspecialchars($nomeBarbearia) ?></title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

/* ---------- BASE ---------- */
body{
    margin:0;
    font-family:"Inter", sans-serif;
    color:white;
    min-height:100vh;
    background:#0e0e0e;
    padding:50px 20px;
}

/* ---------- CONTAINER ---------- */
.container{
    max-width:1250px;
    margin:auto;
    padding:50px;
    border-radius:20px;
    background:#171717;
    border:1px solid #2d2d2d;
    box-shadow:0 0 45px rgba(255,215,0,0.18);
}

/* ---------- TITULOS ---------- */
.hello{
    text-align:center;
    font-size:18px;
    color:#bbbbbb;
}

.title{
    font-size:38px;
    font-weight:700;
    text-align:center;
    color:<?= $corPrimaria ?>;
    margin-top:10px;
}

.subtitle{
    text-align:center;
    color:#cccccc;
    margin-bottom:40px;
    font-size:15px;
}

/* ---------- MENU ---------- */
.menu{
    display:flex;
    gap:18px;
    flex-wrap:wrap;
    justify-content:center;
    margin-bottom:45px;
}

.menu a{
    padding:14px 22px;
    background:<?= $corPrimaria ?>;
    color:#000;
    font-weight:700;
    border-radius:10px;
    text-decoration:none;
    font-size:15px;
    transition:.25s ease;
}

.menu a:hover{
    background:#ffe88a;
    transform:translateY(-3px);
}

/* ---------- ESTATÍSTICAS ---------- */
.stats{
    display:flex;
    gap:25px;
    flex-wrap:wrap;
}

.stat{
    flex:1;
    min-width:260px;
    background:#1c1c1c;
    padding:30px;
    border-radius:12px;
    border-left:6px solid <?= $corPrimaria ?>;
    transition:.25s ease;
}

.stat:hover{
    transform:translateY(-4px);
    box-shadow:0 0 20px rgba(255,215,0,0.25);
}

.stat span{
    font-size:15px;
    color:#bbbbbb;
}

.stat strong{
    font-size:36px;
    color:white;
    font-weight:700;
}

/* ---------- FOOTER ---------- */
.footer{
    margin-top:50px;
    text-align:center;
    color:#aaa;
    font-size:14px;
}

</style>
</head>

<body>

<div class="container">

    <!-- Saudação -->
    <h2 class="hello">
        Olá, <?= htmlspecialchars($_SESSION["admin_nome"]) ?>
    </h2>

    <!-- Título -->
    <h1 class="title"><?= htmlspecialchars($nomeBarbearia) ?> — Painel Administrativo</h1>
    <p class="subtitle">Gerencie barbeiros, serviços, agendamentos e configurações</p>

    <!-- MENU -->
    <div class="menu">

        <a href="agenda.php">Agendamentos</a>

        <a href="barbeiro_novo.php">Cadastrar Barbeiro</a>
        <a href="barbeiros.php">Lista de Barbeiros</a>

        <a href="servicos_listar.php">Gerenciar Serviços</a>

        <a href="logout_admin.php">Sair</a>

    </div>

    <!-- ESTATÍSTICAS -->
    <div class="stats">

        <div class="stat">
            <span>Agendamentos Hoje</span><br>
            <strong><?= $totalHoje ?></strong>
        </div>

        <div class="stat">
            <span>Total de Clientes</span><br>
            <strong><?= $totalClientes ?></strong>
        </div>

        <div class="stat">
            <span>Total de Barbeiros</span><br>
            <strong><?= $totalBarbeiros ?></strong>
        </div>

    </div>

    <p class="footer">Painel Administrativo — <?= date("Y") ?></p>

</div>

</body>
</html>
