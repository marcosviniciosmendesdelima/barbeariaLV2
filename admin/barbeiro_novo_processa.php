<?php
session_start();
require_once "../config/db.php";

// validar admin
if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

$conn = Database::connect();

// RECEBER DADOS DO FORMULÁRIO
$nome = trim($_POST["nome"]);
$cargo = trim($_POST["cargo"]);
$especialidades = trim($_POST["especialidades"]);
$horario = trim($_POST["horario_atendimento"]);
$sobre = trim($_POST["sobre"]);

// VALIDAR CAMPOS OBRIGATÓRIOS
if (empty($nome) || empty($cargo)) {
    die("Erro: Nome e Cargo são obrigatórios.");
}

// PROCESSAR UPLOAD DE FOTO
$fotoNome = null;

if (!empty($_FILES["foto"]["name"])) {

    $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
    $permitidos = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($ext, $permitidos)) {
        die("Formato de imagem inválido. Use JPG, PNG ou WEBP.");
    }

    // Gerar nome único
    $fotoNome = uniqid("barbeiro_") . "." . $ext;

    $pastaDestino = "../assets/barbeiros/";

    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0777, true);
    }

    move_uploaded_file($_FILES["foto"]["tmp_name"], $pastaDestino . $fotoNome);
}

// INSERIR NO BANCO
$sql = "INSERT INTO barbeiros 
        (nome, cargo, foto, especialidades, sobre, horario_atendimento, ativo, data_cadastro)
        VALUES 
        (:nome, :cargo, :foto, :especialidades, :sobre, :horario, 1, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bindParam(":nome", $nome);
$stmt->bindParam(":cargo", $cargo);
$stmt->bindParam(":foto", $fotoNome);
$stmt->bindParam(":especialidades", $especialidades);
$stmt->bindParam(":sobre", $sobre);
$stmt->bindParam(":horario", $horario);

if ($stmt->execute()) {
    header("Location: barbeiros.php?ok=1");
    exit;
} else {
    die("Erro ao salvar barbeiro.");
}
