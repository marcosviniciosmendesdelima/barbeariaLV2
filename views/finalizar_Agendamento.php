<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION["cliente_id"])) {
    header("Location: login.php");
    exit;
}

$conn = Database::connect();

$cliente_id = $_SESSION["cliente_id"];
$barbeiro_id = $_GET["barbeiro"] ?? null;
$servico_id  = $_GET["servico"]  ?? null;
$data        = $_GET["data"]     ?? null;
$hora        = $_GET["hora"]     ?? null;

if (!$barbeiro_id || !$servico_id || !$data || !$hora) {
    echo "<h2 style='color:red; text-align:center; margin-top:40px;'>Erro: Dados incompletos para finalizar o agendamento.</h2>";
    exit;
}

// Serviço
$stmt = $conn->prepare("SELECT nome, preco, duracao_min FROM servicos WHERE id = ?");
$stmt->execute([$servico_id]);
$servico = $stmt->fetch(PDO::FETCH_ASSOC);

// Barbeiro
$stmt = $conn->prepare("SELECT nome, especialidades FROM barbeiros WHERE id = ?");
$stmt->execute([$barbeiro_id]);
$barbeiro = $stmt->fetch(PDO::FETCH_ASSOC);

$clienteNome = $_SESSION["cliente_nome"];

// Verificar horário ocupado
$stmt = $conn->prepare("
    SELECT COUNT(*) AS t 
    FROM agendamentos 
    WHERE barbeiro_id = ? AND data_agendamento = ? AND hora_agendamento = ?
");
$stmt->execute([$barbeiro_id, $data, $hora]);
$verifica = $stmt->fetch();

if ($verifica["t"] > 0) {
    $erro_ocupado = true;
} else {
    $insert = $conn->prepare("
        INSERT INTO agendamentos (usuario_id, barbeiro_id, servico_id, data_agendamento, hora_agendamento, status)
        VALUES (?, ?, ?, ?, ?, 'confirmado')
    ");
    $insert->execute([$cliente_id, $barbeiro_id, $servico_id, $data, $hora]);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Finalizar Agendamento - Barbearia LV2</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* ---------- BASE ---------- */
body{
    background:#0e0e0e;
    color:white;
    font-family:"Inter", sans-serif;
    padding:50px 20px;
}

/* ---------- CONTAINER ---------- */
.box{
    max-width:750px;
    margin:auto;
    padding:40px;
    background:#171717;
    border-radius:16px;
    border:1px solid #2d2d2d;
    box-shadow:0 0 35px rgba(255,215,0,0.18);
}

/* ---------- TÍTULO ---------- */
h1{
    color:#ffda44;
    text-align:center;
    margin-bottom:25px;
    font-size:32px;
    font-weight:700;
    letter-spacing:1px;
}

/* ---------- INFO BOX ---------- */
.info-box{
    background:#1c1c1c;
    padding:20px;
    border-radius:12px;
    margin-bottom:20px;
    border:1px solid #2d2d2d;
}

.info-box p{
    margin:6px 0;
    font-size:15px;
    color:#e0e0e0;
}

.info-box strong{
    color:#ffda44;
}

/* ---------- ERRO ---------- */
.erro-msg{
    text-align:center;
    color:#ff5252;
    background:#2a0e0e;
    padding:15px;
    border-radius:10px;
    border:1px solid #611a1a;
    margin-bottom:25px;
    font-weight:600;
}

/* ---------- BOTÃO FINALIZAR ---------- */
.btn-ok{
    background:#ffda44;
    color:#000;
    font-weight:700;
    padding:14px;
    width:100%;
    border-radius:10px;
    border:none;
    font-size:16px;
    transition:.25s ease;
}

.btn-ok:hover{
    background:#ffe88a;
    transform:translateY(-2px);
}

/* ---------- BOTÃO VOLTAR ---------- */
.btn-voltar{
    text-decoration:none;
    display:block;
    text-align:center;
    padding:12px;
    border:1px solid #555;
    border-radius:10px;
    color:#fff;
    margin-top:20px;
    transition:.25s;
}

.btn-voltar:hover{
    background:#fff;
    color:#000;
}

</style>
</head>

<body>

<div class="box">

    <?php if (!empty($erro_ocupado)): ?>
        
        <h1>Horário Indisponível</h1>

        <p class="erro-msg">
            O barbeiro escolhido já possui um agendamento neste horário.
        </p>

        <a href="javascript:history.back()" class="btn-voltar">⬅ Escolher Outro Horário</a>

    <?php else: ?>
    
        <h1>Agendamento Confirmado</h1>

        <div class="info-box">
            <p><strong>Barbeiro:</strong> <?= htmlspecialchars($barbeiro["nome"]) ?></p>
            <p><strong>Especialidades:</strong> <?= htmlspecialchars($barbeiro["especialidades"]) ?></p>
        </div>

        <div class="info-box">
            <p><strong>Cliente:</strong> <?= htmlspecialchars($clienteNome) ?></p>
            <p><strong>Serviço:</strong> <?= htmlspecialchars($servico["nome"]) ?></p>
            <p><strong>Duração:</strong> <?= $servico["duracao_min"] ?> min</p>
            <p><strong>Data:</strong> <?= date("d/m/Y", strtotime($data)) ?></p>
            <p><strong>Horário:</strong> <?= $hora ?></p>
        </div>

        <button class="btn-ok" onclick="window.location.href='agendamento_sucesso.php'">
            ✔ Finalizar
        </button>

        <a href="../index.html" class="btn-voltar">⬅ Voltar ao Início</a>

    <?php endif; ?>

</div>

</body>
</html>
