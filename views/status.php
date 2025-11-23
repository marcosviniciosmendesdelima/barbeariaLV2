<?php
session_start();

// Impede acesso sem ID
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
if ($id <= 0) {
    header("Location: agenda.php?erro=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Alterar Status - Barbearia LV2</title>

<!-- Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: "Poppins", sans-serif;
        background: linear-gradient(-45deg, #0a0a0a, #131313, #000);
        background-size: 400% 400%;
        animation: bgMove 15s ease infinite;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
    }

    @keyframes bgMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .card {
        width: 380px;
        padding: 35px;
        background: rgba(255,255,255,0.08);
        border-radius: 18px;
        border: 1px solid rgba(255,255,255,0.15);
        backdrop-filter: blur(14px);
        text-align: center;
        box-shadow: 0 0 30px rgba(255,193,7,0.15);
        animation: fade .8s ease forwards;
        opacity: 0;
    }

    @keyframes fade {
        to { opacity: 1; }
    }

    h2 {
        margin-bottom: 25px;
        font-size: 26px;
        color: #f1c40f;
        font-weight: 600;
    }

    a {
        display: block;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
        font-weight: 600;
        font-size: 16px;
        text-decoration: none;
        transition: .25s;
    }

    .confirmar {
        background: #2ecc71;
        color: #000;
        box-shadow: 0 0 12px rgba(46, 204, 113, .3);
    }

    .confirmar:hover {
        background: #43df87;
        transform: translateY(-2px);
    }

    .cancelar {
        background: #e74c3c;
        color: white;
        box-shadow: 0 0 12px rgba(231, 76, 60, .3);
    }

    .cancelar:hover {
        background: #ff6455;
        transform: translateY(-2px);
    }

    .back {
        margin-top: 20px;
        color: #f1c40f;
        font-size: 15px;
    }

    .back:hover {
        text-decoration: underline;
    }

</style>
</head>

<body>

<div class="card">

    <h2>Alterar Status</h2>

    <a class="confirmar"
       href="../../controllers/AgendamentoController.php?acao=status&id=<?= $id ?>&novo=confirmado">
        Confirmar Agendamento
    </a>

    <a class="cancelar"
       href="../../controllers/AgendamentoController.php?acao=status&id=<?= $id ?>&novo=cancelado">
        Cancelar Agendamento
    </a>

    <a href="agenda.php" class="back">⬅ Voltar</a>

</div>

</body>
</html>
