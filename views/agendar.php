<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION["cliente_id"])) {
    header("Location: ../views/login.php");
    exit;
}

$conn = Database::connect();

$servicoSelecionado = $_GET["servico_id"] ?? null;

if (!$servicoSelecionado) {
    header("Location: ../admin/servicos.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM servicos WHERE id = ? AND ativo = 1 LIMIT 1");
$stmt->execute([$servicoSelecionado]);
$servicoAtual = $stmt->fetch(PDO::FETCH_ASSOC);

$clienteNome = $_SESSION["cliente_nome"];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Agendar Serviço - Barbearia LV2</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* ----------------- BASE ----------------- */
body {
    background: #0e0e0e;
    color: #ffffff;
    font-family: "Inter", sans-serif;
    padding-top: 50px;
}

/* ----------------- CONTAINER ----------------- */
.container-box {
    max-width: 700px;
    margin: auto;
    background: #171717;
    border-radius: 15px;
    padding: 35px;
    border: 1px solid #2d2d2d;
    box-shadow: 0px 0px 35px rgba(255, 215, 0, 0.18);
}

/* ----------------- TÍTULO ----------------- */
.title {
    text-align: center;
    font-size: 32px;
    font-weight: 700;
    letter-spacing: 1px;
    color: #ffda44;
}

.subtitle {
    text-align: center;
    color: #bfbfbf;
    margin-bottom: 25px;
    font-size: 15px;
}

/* ----------------- CARD DO SERVIÇO ----------------- */
.servico-card {
    background: #1c1c1c;
    border-radius: 12px;
    padding: 16px;
    border: 1px solid #2d2d2d;
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    transition: .25s ease;
}

.servico-card:hover {
    box-shadow: 0px 0px 25px rgba(255,215,0,0.18);
    transform: translateY(-3px);
}

.servico-card img {
    width: 120px;
    height: 120px;
    border-radius: 12px;
    object-fit: cover;
    background: #2a2a2a;
}

/* ----------------- CAMPOS ----------------- */
label {
    font-weight: 600;
    margin-top: 10px;
}

select, input {
    background: #111;
    border-radius: 8px;
    border: 1px solid #333;
    color: #fff;
    padding: 12px;
    width: 100%;
    margin-bottom: 15px;
    transition: .2s;
}

select:focus, input:focus {
    border-color: #ffda44;
    box-shadow: 0px 0px 12px rgba(255,215,0,0.25);
}

/* ----------------- BOTÃO CONFIRMAR ----------------- */
.btn-confirm {
    background: #ffda44;
    color: #000;
    font-weight: 700;
    width: 100%;
    padding: 14px;
    border-radius: 10px;
    transition: .25s ease;
    border: none;
}

.btn-confirm:hover {
    background: #ffe88a;
    transform: translateY(-2px);
}

/* ----------------- BOTÃO VOLTAR ----------------- */
.btn-back {
    display: block;
    padding: 12px;
    text-align: center;
    border-radius: 10px;
    background: transparent;
    color: #fff;
    border: 1px solid #444;
    margin-top: 25px;
    transition: .25s ease;
}

.btn-back:hover {
    background: #fff;
    color: #000;
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

    <h2 class="title">Agendar Serviço</h2>
    <p class="subtitle">Cliente: <strong><?= $clienteNome ?></strong></p>

   
    <?php if ($servicoAtual): ?>
    <div class="servico-card">
        <img src="../assets/servicos/<?= $servicoAtual['img'] ?>"
             alt="<?= $servicoAtual['nome'] ?>"
             onerror="this.src='../assets/servicos/padrao.jpg'">

        <div>
            <h4 style="margin-bottom: 5px;"><?= $servicoAtual["nome"] ?></h4>
            <p class="text-secondary mb-1"><?= $servicoAtual["duracao_min"] ?> min</p>
            <h5 style="color:#ffda44;">
                R$ <?= number_format($servicoAtual["preco"], 2, ',', '.') ?>
            </h5>
        </div>
    </div>
    <?php endif; ?>


    <form action="/BarbeariaLV2/views/barbeiros_cliente.php" method="GET">

        <input type="hidden" name="servico" id="servico_id" value="<?= $servicoAtual["id"] ?>">

        <label>Selecione a Data</label>
        <input type="date" name="data" id="data" onchange="carregarHorarios()" required>

        <label>Selecione o Horário</label>
        <select name="hora" id="hora" required>
            <option value="">Escolha a data primeiro</option>
        </select>

        <button type="submit" class="btn-confirm mt-3">Escolher Barbeiro →</button>
    </form>

    <a href="../admin/servicos.php" class="btn-back">⬅ Voltar aos Serviços</a>

</div>

</body>
</html>
