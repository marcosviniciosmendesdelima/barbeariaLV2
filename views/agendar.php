<?php
session_start();
require_once "../config/db.php";

// Validar cliente
if (!isset($_SESSION["cliente_id"])) {
    header("Location: login.php");
    exit;
}

$conn = Database::connect();

// Serviço escolhido
$servicoSelecionado = $_GET["servico_id"] ?? null;


$servicoAtual = null;

if ($servicoSelecionado) {
    $stmt = $conn->prepare("SELECT * FROM servicos WHERE id = ? AND ativo = 1 LIMIT 1");
    $stmt->execute([$servicoSelecionado]);
    $servicoAtual = $stmt->fetch(PDO::FETCH_ASSOC);
}


$clienteNome = $_SESSION["cliente_nome"];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Agendar Serviço - Barbearia LV2</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #0d0d0d;
        color: #f5ededf4;
        font-family: Poppins, sans-serif;
        padding-top: 40px;
    }

    .container-box {
        max-width: 650px;
        margin: auto;
        background: rgba(255,255,255,0.05);
        border-radius: 18px;
        padding: 30px;
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: 0 0 30px rgba(255, 193, 7, 0.20);
        backdrop-filter: blur(12px);
    }

    .servico-card {
        background: #1a1a1a;
        border-radius: 14px;
        padding: 14px;
        border: 1px solid #2a2a2a;
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .servico-card img {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        object-fit: cover;
        background: #2a2a2a;
    }

    select, input {
        background: rgba(255,255,255,0.12);
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.15);
        color: white;
        padding: 12px;
        width: 100%;
        margin-bottom: 15px;
    }

    select:focus, input:focus {
        border-color: #f1c40f;
        box-shadow: 0 0 10px rgba(241,196,15,0.3);
        outline: none;
    }

    .btn-confirm {
        background: #f1c40f;
        color: #000;
        font-weight: 600;
        width: 100%;
        padding: 14px;
        border-radius: 10px;
        transition: 0.2s;
        border: none;
    }

    .btn-confirm:hover {
        background: #ffdd57;
        transform: translateY(-2px);
    }

    .btn-back {
        text-decoration: none;
        display: block;
        padding: 12px;
        text-align: center;
        border-radius: 10px;
        background: rgba(8, 2, 2, 0.1);
        color: #0c0a0aff;
        border: 1px solid #555;
        margin-top: 20px;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.20);
        color: #1f1c1cff;
    }
</style>

<script>

async function carregarHorarios() {
    let data = document.getElementById("data").value;
    let servico = document.getElementById("servico_id").value;

    if (!data || !servico) return;

    let req = await fetch("../views/horarios_disponiveis.php?data=" + data + "&servico=" + servico);
    let horarios = await req.json();

    let select = document.getElementById("hora");
    select.innerHTML = "<option value=''>Selecione o horário</option>";

    horarios.forEach(h => {
        select.innerHTML += `<option value="${h}">${h}</option>`;
    });
}
</script>
</head>

<body>

<div class="container container-box">

    <h2 class="text-center mb-3" style="color:#f1c40f;">Agendar Serviço</h2>

    <p class="text-center text-secondary mb-4">Cliente: <strong><?= $clienteNome ?></strong></p>

   
    <?php if ($servicoAtual): ?>
    <div class="servico-card">
        <img src="../assets/img/<?= $servicoAtual['img'] ?>"
             alt="<?= $servicoAtual['nome'] ?>"
             onerror="this.src='../assets/img/padrao.jpg'">

        <div>
            <h4><?= $servicoAtual["nome"] ?></h4>
            <p class="text-secondary mb-1"><?= $servicoAtual["duracao_min"] ?> min</p>
            <h5 class="text-success">R$ <?= number_format($servicoAtual["preco"], 2, ',', '.') ?></h5>
        </div>
    </div>
    <?php endif; ?>

    
    <form action="/BarbeariaLV2/views/barbeiros_cliente.php" method="GET">

    <input type="hidden" name="servico" id="servico_id" value="<?= $servicoAtual["id"] ?>">

    <label>Selecione a Data</label>
    <input type="date" name="data" id="data" onchange="carregarHorarios()" required>

    <label class="mt-3">Selecione o Horário</label>
    <select name="hora" id="hora" required>
        <option value="">Escolha a data primeiro</option>
    </select>

    <button type="submit" class="btn-confirm mt-3">Escolher Barbeiro →</button>
</form>

    <a href="../admin/servicos.php" class="btn-back">⬅ Voltar aos Serviços</a>

    

</div>

</body>
</html>