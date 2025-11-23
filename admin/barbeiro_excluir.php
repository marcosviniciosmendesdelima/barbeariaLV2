<?php
session_start();
require_once "../config/db.php";

$conn = Database::connect();

// validar admin
if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

// validar id
if (!isset($_GET["id"])) {
    die("ID inválido.");
}

$id = intval($_GET["id"]);
$sql = "SELECT foto FROM barbeiros WHERE id = :id LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$barbeiro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barbeiro) {
    die("Barbeiro não encontrado.");
}

if (!empty($barbeiro["foto"]) && file_exists("../uploads/barbeiros/" . $barbeiro["foto"])) {
    unlink("../uploads/barbeiros/" . $barbeiro["foto"]);
}

$sqlDel = "DELETE FROM barbeiros WHERE id = :id LIMIT 1";
$stmtDel = $conn->prepare($sqlDel);
$stmtDel->bindParam(":id", $id);

if ($stmtDel->execute()) {
    header("Location: barbeiros.php?delete_ok=1");
    exit;
} else {
    die("Erro ao excluir barbeiro.");
}

