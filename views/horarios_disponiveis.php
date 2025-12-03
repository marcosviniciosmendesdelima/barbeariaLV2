<?php
require_once "../config/db.php";

header("Content-Type: application/json; charset=utf-8");

$conn = Database::connect();

$data       = isset($_GET["data"]) ? trim($_GET["data"]) : "";
$servico_id = isset($_GET["servico"]) ? (int) $_GET["servico"] : 0;

if ($servico_id <= 0 || $data === "") {
    echo json_encode([]);
    exit;
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
    SELECT duracao_min 
    FROM servicos 
    WHERE id = :id AND ativo = 1
    LIMIT 1
");
$stmt->execute([':id' => $servico_id]);
$servico = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servico) {
    echo json_encode([]);
    exit;
}

$duracao = (int) $servico["duracao_min"];

$horarios = [];
$inicio = strtotime("08:00");
$fim    = strtotime("18:00");

for ($h = $inicio; $h < $fim; $h += 30 * 60) {
    $horarios[] = date("H:i", $h);
}

$bloq = $conn->prepare("
    SELECT hora_inicio, hora_fim 
    FROM bloqueios 
    WHERE data = :data
");
$bloq->execute([':data' => $data]);
$bloqueios = $bloq->fetchAll(PDO::FETCH_ASSOC);

$diaBloqueado = false;
foreach ($bloqueios as $b) {
    if ($b["hora_inicio"] === null) {
        $diaBloqueado = true;
        break;
    }
}

if ($diaBloqueado) {
    echo json_encode([]);
    exit;
}

$ag = $conn->prepare("
    SELECT hora_agendamento 
    FROM agendamentos 
    WHERE data_agendamento = :data
      AND status <> 'cancelado'
");
$ag->execute([':data' => $data]);
$ocupados = array_column($ag->fetchAll(PDO::FETCH_ASSOC), "hora_agendamento");

$disponiveis = [];

foreach ($horarios as $h) {
    foreach ($bloqueios as $b) {
        if ($b["hora_inicio"] !== null && $b["hora_fim"] !== null) {
            $ini = substr($b["hora_inicio"], 0, 5);
            $fim = substr($b["hora_fim"], 0, 5);
            if ($h >= $ini && $h <= $fim) {
                continue 2;
            }
        }
    }

    if (in_array($h . ":00", $ocupados, true)) {
        continue;
    }

    $disponiveis[] = $h;
}

echo json_encode($disponiveis, JSON_UNESCAPED_UNICODE);
exit;
