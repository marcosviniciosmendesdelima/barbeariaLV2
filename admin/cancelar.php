<?php
require_once "../config/db.php";
session_start();

$conn = Database::connect();

if (!isset($_SESSION["user"]) || $_SESSION["user"]["tipo"] !== "admin") {
    header("Location: login_admin.php");
    exit;
}

$id = intval($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: agenda.php?erro=id_invalido");
    exit;
}

$stmt = $conn->prepare("UPDATE agendamentos SET status = 'cancelado' WHERE id = ?");
$stmt->execute([$id]);

header("Location: agenda.php?ok=cancelado");
exit;
