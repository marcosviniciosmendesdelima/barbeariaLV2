<?php
require_once "../config/db.php";
session_start();

$conn = Database::connect();

// --- VALIDAR ADMIN ---
if (!isset($_SESSION["user"]) || $_SESSION["user"]["tipo"] !== "admin") {
    header("Location: login_admin.php");
    exit;
}

// --- VALIDAR ID ---
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($id <= 0) {
    header("Location: agenda.php?erro=id_invalido");
    exit;
}

// --- CANCELAR AGENDAMENTO ---
$stmt = $conn->prepare("
    UPDATE agendamentos 
    SET status = 'cancelado' 
    WHERE id = ?
");
$stmt->execute([$id]);

header("Location: agenda.php?ok=cancelado");
exit;
