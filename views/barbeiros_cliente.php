<?php
session_start();
require_once "../config/db.php";

$conn = Database::connect();

// Recebendo dados
$servico_id = $_GET["servico"] ?? null;
$data = $_GET["data"] ?? null;
$hora = $_GET["hora"] ?? null;

// Buscar serviço
$stmt = $conn->prepare("SELECT nome FROM servicos WHERE id = ?");
$stmt->execute([$servico_id]);
$servico = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar barbeiros disponíveis
$stmt2 = $conn->query("SELECT * FROM barbeiros WHERE ativo = 1 ORDER BY nome");
$barbeiros = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Escolher Barbeiro - Barbearia LV2</title>

<style>
/* BASE */
body {
    background: #0e0e0e;
    margin: 0;
    padding: 0;
    font-family: "Inter", sans-serif;
    color: #ffffff;
}

/* CONTAINER */
.main {
    max-width: 950px;
    margin: 50px auto;
    padding: 40px;
    background: #171717;
    border-radius: 16px;
    border: 1px solid #2d2d2d;
    box-shadow: 0 0 35px rgba(255, 215, 0, .18);
}

/* HEADER */
.header-info {
    text-align: center;
    margin-bottom: 35px;
}

.header-info h2 {
    font-size: 32px;
    color: #ffda44;
    font-weight: 700;
    letter-spacing: 1px;
}

.header-info p {
    font-size: 15px;
    color: #bbbbbb;
}

/* SEÇÃO TÍTULO */
.section-title {
    text-align: center;
    color: #ffda44;
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 25px;
    letter-spacing: 1px;
}

/* CARD */
.card {
    background: #1c1c1c;
    border-radius: 12px;
    padding: 22px;
    display: flex;
    align-items: center;
    gap: 20px;
    border: 1px solid #2d2d2d;
    margin-bottom: 25px;
    transition: .25s ease;
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0px 0px 25px rgba(255, 215, 0, .25);
}

/* FOTO */
.photo {
    width: 95px;
    height: 95px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid rgba(255,215,0,0.45);
}

.photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* INFO BARBEIRO */
.info h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
}

.info p {
    margin: 4px 0 12px 0;
    font-size: 14px;
    color: #bbbbbb;
}

/* TAGS */
.tag {
    display: inline-block;
    background: #ffda44;
    color: #000;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: bold;
    margin-right: 6px;
}

/* BOTÃO AGENDAR */
.btn-agendar {
    margin-left: auto;
    background: #ffda44;
    color: #000;
    font-size: 15px;
    font-weight: 700;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    transition: .25s ease;
}

.btn-agendar:hover {
    background: #ffe88a;
    transform: translateY(-3px);
}

/* BOTÃO VOLTAR */
.btn-voltar {
    display: block;
    width: 220px;
    margin: 40px auto 0;
    text-align: center;
    padding: 12px;
    border-radius: 8px;
    background: transparent;
    color: #fff;
    text-decoration: none;
    border: 1px solid #555;
    transition: .25s ease;
}

.btn-voltar:hover {
    background: #fff;
    color: #000;
}
</style>
</head>

<body>

<div class="main">

    <!-- Cabeçalho do Serviço -->
    <div class="header-info">
        <h2><?= htmlspecialchars($servico["nome"]) ?></h2>
        <p><strong>Data:</strong> <?= $data ?> &nbsp; • &nbsp; <strong>Horário:</strong> <?= $hora ?></p>
    </div>

    <h3 class="section-title">Barbeiros Disponíveis</h3>

    <?php foreach ($barbeiros as $b): ?>
        <div class="card">

            <!-- Foto -->
            <div class="photo">
                <img src="../assets/barbeiros/<?= $b['foto'] ?: 'default.jpg' ?>" 
                     onerror="this.src='../assets/barbeiros/default.jpg'">
            </div>

            <!-- Informações -->
            <div class="info">
                <h3><?= htmlspecialchars($b["nome"]) ?></h3>
                <p><?= htmlspecialchars($b["cargo"]) ?></p>

                <!-- Especialidades -->
                <?php 
                $tags = explode(",", $b["especialidades"]);
                foreach ($tags as $t): ?>
                    <span class="tag"><?= trim($t) ?></span>
                <?php endforeach; ?>
            </div>

            <!-- Botão -->
            <a class="btn-agendar" 
               href="finalizar_agendamento.php?barbeiro=<?= $b['id'] ?>&servico=<?= $servico_id ?>&data=<?= $data ?>&hora=<?= $hora ?>">
               Agendar com <?= explode(" ", $b["nome"])[0] ?>
            </a>

        </div>
    <?php endforeach; ?>

    <a href="javascript:history.back()" class="btn-voltar">⬅ Voltar</a>

</div>

</body>
</html>
