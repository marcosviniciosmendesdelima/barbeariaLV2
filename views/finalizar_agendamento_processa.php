<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION["cliente_id"])) {
    header("Location: login.php");
    exit;
}

$conn = Database::connect();

// Receber dados
$cliente_id  = $_POST["cliente_id"];
$servico_id  = $_POST["servico_id"];
$barbeiro_id = $_POST["barbeiro_id"];
$data        = $_POST["data"];
$hora        = $_POST["hora"];

$status = "confirmado";

$sql = "
INSERT INTO agendamentos (usuario_id, servico_id, barbeiro_id, data_agendamento, hora_agendamento, status)
VALUES (:cliente, :servico, :barbeiro, :data_ag, :hora_ag, :status)
";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ":cliente" => $cliente_id,
    ":servico" => $servico_id,
    ":barbeiro" => $barbeiro_id,
    ":data_ag" => $data,
    ":hora_ag" => $hora,
    ":status" => $status
]);

header("Location: agendamento_sucesso.php");
exit;
