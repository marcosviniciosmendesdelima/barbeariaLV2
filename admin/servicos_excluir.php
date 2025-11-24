<?php
require_once '../config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: servicos_listar.php?erro=1");
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT img FROM servicos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$servico = $stmt->fetch(PDO::FETCH_ASSOC);

if ($servico && !empty($servico['img'])) {
    $caminho = "../assets/servicos/" . $servico['img'];

    if (file_exists($caminho)) {
        unlink($caminho); 
    }
}


$sql = "DELETE FROM servicos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);


header("Location: servicos_listar.php?excluido=1");
exit;
?>