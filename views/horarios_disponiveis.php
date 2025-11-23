<?php
require_once "../config/db.php";
$conn = Database::connect();

$data = $_GET["data"] ?? null;
$servico_id = $_GET["servico"] ?? null;

if (!$data || !$servico_id) {
    echo json_encode([]);
    exit;
}

// pegar duração do serviço
$stmt = $conn->prepare("SELECT duracao_min FROM servicos WHERE id = ?");
$stmt->execute([$servico_id]);
$servico = $stmt->fetch();

if (!$servico) {
    echo json_encode([]);
    exit;
}

$duracao = intval($servico["duracao_min"]);

// gerar lista de horários padrão (08:00 até 18:00)
$horarios = [];
$inicio = strtotime("08:00");
$fim = strtotime("18:00");

for ($h = $inicio; $h < $fim; $h += 30 * 60) {
    $horarios[] = date("H:i", $h);
}

// BUSCAR HORÁRIOS BLOQUEADOS
$bloq = $conn->prepare("
    SELECT hora_inicio, hora_fim 
    FROM bloqueios 
    WHERE data = ?
");
$bloq->execute([$data]);
$bloqueios = $bloq->fetchAll(PDO::FETCH_ASSOC);

// BUSCAR AGENDAMENTOS EXISTENTES
$ag = $conn->prepare("
    SELECT hora_agendamento 
    FROM agendamentos 
    WHERE data_agendamento = ? AND status <> 'cancelado'
");
$ag->execute([$data]);
$ocupados = array_column($ag->fetchAll(), "hora_agendamento");

// Filtrar horários
$disponiveis = [];

foreach ($horarios as $h) {

   
    $temBloqueioDia = false;
    foreach ($bloqueios as $b) {
        if ($b["hora_inicio"] === null) {
            $temBloqueioDia = true;
        }
    }
    if ($temBloqueioDia) continue;

    foreach ($bloqueios as $b) {
        if (
            $b["hora_inicio"] &&
            $h >= substr($b["hora_inicio"],0,5) &&
            $h <= substr($b["hora_fim"],0,5)
        ) {
            continue 2;
        }
    }

    
    if (in_array($h . ":00", $ocupados)) continue;

    $disponiveis[] = $h;
}

echo json_encode($disponiveis);
exit;