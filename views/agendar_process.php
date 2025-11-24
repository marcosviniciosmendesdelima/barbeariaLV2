<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION["cliente_id"])) {
    header("Location: login.php");
    exit;
}

$conn = Database::connect();


$cliente = $_SESSION["cliente_id"];
$servico = intval($_POST["servico_id"] ?? 0);
$data    = trim($_POST["data"] ?? "");
$hora    = trim($_POST["hora"] ?? "");


if ($servico <= 0 || empty($data) || empty($hora)) {
    header("Location: agendar.php?erro=1");
    exit;
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data) ||
    !preg_match('/^\d{2}:\d{2}$/', $hora)) {
    header("Location: agendar.php?erro=1");
    exit;
}


$hoje = date("Y-m-d");
if ($data < $hoje) {
    header("Location: agendar.php?data_invalida=1");
    exit;
}



$checkBloqueio = $conn->prepare("
    SELECT *
    FROM bloqueios
    WHERE data = ?
      AND (
            hora_inicio IS NULL 
            OR (hora_inicio <= ? AND hora_fim >= ?)
      )
    LIMIT 1
");

$checkBloqueio->execute([$data, $hora, $hora]);
$bloqueio = $checkBloqueio->fetch();

if ($bloqueio) {
    // Motivo opcional
    $desc = $bloqueio["descricao"] ?: "Horário indisponível";
    header("Location: agendar.php?bloqueado=1&motivo=" . urlencode($desc));
    exit;
}


// ==================================================
// 2) ENCONTRAR UM BARBEIRO LIVRE NESSE HORÁRIO
// ==================================================
$sql = $conn->prepare("
    SELECT b.id
    FROM barbeiros b
    WHERE b.status = 'ativo'
    AND NOT EXISTS (
        SELECT 1
        FROM agendamentos a
        WHERE a.barbeiro_id = b.id
          AND a.data_agendamento = ?
          AND a.hora_agendamento = ?
          AND a.status <> 'cancelado'
    )
    ORDER BY b.id ASC
    LIMIT 1
");

$sql->execute([$data, $hora]);
$barbeiro_id = $sql->fetchColumn();

if (!$barbeiro_id) {
    header("Location: agendar.php?ocupado=1");
    exit;
}


// ==================================================
// 3) REGISTRAR AGENDAMENTO
// ==================================================
$stmt = $conn->prepare("
    INSERT INTO agendamentos
    (usuario_id, barbeiro_id, servico_id, data_agendamento, hora_agendamento, status)
    VALUES (?, ?, ?, ?, ?, 'pendente')
");

$ok = $stmt->execute([$cliente, $barbeiro_id, $servico, $data, $hora]);

if (!$ok) {
    header("Location: agendar.php?erro_registro=1");
    exit;
}


// ==================================================
// 4) REDIRECIONAR PARA CONFIRMAÇÃO (E WHATSAPP)
// ==================================================
header("Location: agendar.php?ok=1&data={$data}&hora={$hora}&servico_id={$servico}");
exit;
