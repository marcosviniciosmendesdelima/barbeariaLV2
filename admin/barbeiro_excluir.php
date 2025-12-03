<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

$conn = Database::connect();

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: barbeiros.php?erro=id_invalido");
    exit;
}

$stmt = $conn->prepare("SELECT foto FROM barbeiros WHERE id = :id LIMIT 1");
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$barbeiro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barbeiro) {
    header("Location: barbeiros.php?erro=nao_encontrado");
    exit;
}

$diretorio = "../assets/barbeiros/";

if (!empty($barbeiro["foto"])) {
    $caminho = $diretorio . $barbeiro["foto"];
    if (file_exists($caminho)) {
        unlink($caminho);
    }
}

$stmtDel = $conn->prepare("DELETE FROM barbeiros WHERE id = :id LIMIT 1");
$stmtDel->bindValue(":id", $id, PDO::PARAM_INT);

if ($stmtDel->execute()) {
    header("Location: barbeiros.php?delete_ok=1");
    exit;
}

header("Location: barbeiros.php?erro=delete");
exit;
