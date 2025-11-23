<?php
session_start();
require_once "../config/db.php";

$conn = Database::connect();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

if (!isset($_POST["id"])) {
    die("ID inválido.");
}

$id = intval($_POST["id"]);

// receber dados
$nome = trim($_POST["nome"]);
$cargo = trim($_POST["cargo"]);
$especialidades = trim($_POST["especialidades"]);
$horario = trim($_POST["horario"]);
$sobre = trim($_POST["sobre"]);
$removerFoto = intval($_POST["removerFoto"]);

// buscar barbeiro atual
$sql = "SELECT foto FROM barbeiros WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$barberAtual = $stmt->fetch(PDO::FETCH_ASSOC);

$fotoAtual = $barberAtual["foto"] ?? null;

$novaFoto = $fotoAtual;

if (!empty($_FILES["foto"]["name"])) {

    $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
    $permitidos = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($ext, $permitidos)) {
        die("Erro: Formato de imagem inválido.");
    }

    $novaFoto = uniqid("barbeiro_") . "." . $ext;
    $destino = "../uploads/barbeiros/" . $novaFoto;

 
    move_uploaded_file($_FILES["foto"]["tmp_name"], $destino);

    if (!empty($fotoAtual) && file_exists("../uploads/barbeiros/" . $fotoAtual)) {
        unlink("../uploads/barbeiros/" . $fotoAtual);
    }
}

if ($removerFoto == 1) {
    if (!empty($fotoAtual) && file_exists("../uploads/barbeiros/" . $fotoAtual)) {
        unlink("../uploads/barbeiros/" . $fotoAtual);
    }
    $novaFoto = null;
}

$sqlUp = "UPDATE barbeiros SET
            nome = :nome,
            cargo = :cargo,
            especialidades = :especialidades,
            sobre = :sobre,
            horario_atendimento = :horario,
            foto = :foto
          WHERE id = :id";

$stmtUp = $conn->prepare($sqlUp);
$stmtUp->bindParam(":nome", $nome);
$stmtUp->bindParam(":cargo", $cargo);
$stmtUp->bindParam(":especialidades", $especialidades);
$stmtUp->bindParam(":sobre", $sobre);
$stmtUp->bindParam(":horario", $horario);
$stmtUp->bindParam(":foto", $novaFoto);
$stmtUp->bindParam(":id", $id);

if ($stmtUp->execute()) {
    header("Location: barbeiros.php?edit_ok=1");
    exit;
} else {
    die("Erro ao atualizar barbeiro.");
}