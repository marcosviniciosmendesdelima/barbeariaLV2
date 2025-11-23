<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION["cliente_id"])) {
    header("Location: login.php");
    exit;
}

$conn = Database::connect();


$servico = $_GET["servico"] ?? null;
$data    = $_GET["data"] ?? null;
$hora    = $_GET["hora"] ?? null;

if (!$servico || !$data || !$hora) {
    die("<h2 style='color:white;text-align:center;margin-top:20px;'>❌ Dados incompletos!</h2>");
}

$stmt = $conn->prepare("
    SELECT id, nome, foto 
    FROM usuarios 
    WHERE tipo = 'barbeiro'
    AND status = 'ativo'
    AND id NOT IN (
        SELECT barbeiro_id 
        FROM agendamentos 
        WHERE data_agendamento = ? 
        AND hora_agendamento = ? 
        AND status <> 'cancelado'
    )
");
$stmt->execute([$data, $hora]);
$barbeiros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Escolher Barbeiro - Barbearia LV2</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
    body{
        background:#0d0d0d;
        color:white;
        font-family:Poppins,sans-serif;
        padding:40px 0;
    }

    .container-box{
        max-width:800px;
        margin:auto;
        background:rgba(255,255,255,0.05);
        padding:30px;
        border-radius:16px;
        border:1px solid rgba(255,255,255,0.1);
        box-shadow:0 0 25px rgba(255,193,7,0.15);
        backdrop-filter:blur(10px);
    }

    .title{
        text-align:center;
        color:#f1c40f;
        font-size:28px;
        font-weight:600;
        margin-bottom:20px;
    }

    .barbeiro-card{
        background:#1a1a1a;
        border:1px solid #2a2a2a;
        border-radius:14px;
        padding:15px;
        display:flex;
        align-items:center;
        gap:15px;
        transition:.25s;
    }

    .barbeiro-card:hover{
        transform:translateY(-4px);
        box-shadow:0 0 20px rgba(241,196,15,.25);
    }

    .barbeiro-foto{
        width:90px;
        height:90px;
        border-radius:50%;
        object-fit:cover;
        border:2px solid #f1c40f;
    }

    .btn-escolher{
        background:#f1c40f;
        color:#000;
        font-weight:600;
        border:none;
        padding:10px 20px;
        border-radius:8px;
        transition:.25s;
    }

    .btn-escolher:hover{
        background:#ffdd57;
        transform:translateY(-2px);
    }

    .btn-back{
        margin-top:25px;
        display:block;
        text-align:center;
        padding:12px;
        border-radius:10px;
        border:1px solid #aaa;
        color:#ccc;
        text-decoration:none;
    }

    .btn-back:hover{
        background:rgba(255,255,255,0.1);
        color:white;
    }
</style>
</head>

<body>

<div class="container container-box">

    <h2 class="title">Escolha seu Barbeiro</h2>

    <p class="text-center mb-4">
        Serviço selecionado •  
        <strong><?= htmlspecialchars($servico) ?></strong> <br>
        Dia <strong><?= date("d/m/Y", strtotime($data)) ?></strong> às <strong><?= substr($hora,0,5) ?></strong>
    </p>

    <?php if (empty($barbeiros)): ?>
        <h4 class="text-center text-warning mt-4">⚠ Nenhum barbeiro disponível neste horário.</h4>
    <?php else: ?>

        <div class="row g-4">
        <?php foreach ($barbeiros as $b): ?>
            <div class="col-md-6">
                <div class="barbeiro-card">

                    <!-- FOTO -->
                    <img src="<?= $b['foto'] ?: '../assets/img/default_barber.png' ?>"
                         class="barbeiro-foto"
                         onerror="this.src='../assets/img/default_barber.png'">

                    <div style="flex:1;">
                        <h4 style="margin:0;"><?= htmlspecialchars($b['nome']) ?></h4>
                        <p class="text-secondary" style="font-size:14px;">Disponível</p>
                    </div>

                    <!-- BOTÃO ESCOLHER -->
                    <form action="confirmar_agendamento.php" method="POST">
                        <input type="hidden" name="barbeiro_id" value="<?= $b['id'] ?>">
                        <input type="hidden" name="servico" value="<?= $servico ?>">
                        <input type="hidden" name="data" value="<?= $data ?>">
                        <input type="hidden" name="hora" value="<?= $hora ?>">

                        <button class="btn-escolher">Selecionar</button>
                    </form>

                </div>
            </div>
        <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <a href="agendar.php" class="btn-back">⬅ Voltar</a>

</div>

</body>
</html>