<?php
require_once '../config/db.php';

$conn = Database::connect();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: servicos_listar.php?erro=1");
    exit;
}

$stmt = $conn->prepare("SELECT img FROM servicos WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$servico = $stmt->fetch(PDO::FETCH_ASSOC);

if ($servico && !empty($servico['img'])) {
    $caminho = "../assets/servicos/" . $servico['img'];

    if (is_file($caminho) && file_exists($caminho)) {
        @unlink($caminho);
    }
}

$stmt = $conn->prepare("DELETE FROM servicos WHERE id = ?");
$stmt->execute([$id]);

header("Location: servicos_listar.php?excluido=1");
exit;
