<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: barbeiro_novo.php");
    exit;
}

$conn = Database::connect();

$nome           = trim($_POST["nome"] ?? "");
$cargo          = trim($_POST["cargo"] ?? "");
$especialidades = trim($_POST["especialidades"] ?? "");
$horario        = trim($_POST["horario_atendimento"] ?? "");
$sobre          = trim($_POST["sobre"] ?? "");

if ($nome === "" || $cargo === "") {
    die("Erro: Nome e Cargo são obrigatórios.");
}

$fotoNome = null;

if (!empty($_FILES["foto"]["name"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
    $permitidos = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($ext, $permitidos, true)) {
        die("Formato de imagem inválido. Use JPG, PNG ou WEBP.");
    }

    $fotoNome = uniqid("barbeiro_", true) . "." . $ext;

    $pastaDestino = "../assets/barbeiros/";
    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0777, true);
    }

    if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $pastaDestino . $fotoNome)) {
        die("Erro ao fazer upload da imagem.");
    }
}

$sql = "
    INSERT INTO barbeiros
        (nome, cargo, foto, especialidades, sobre, horario_atendimento, ativo, data_cadastro)
    VALUES
        (:nome, :cargo, :foto, :especialidades, :sobre, :horario, 1, NOW())
";

$stmt = $conn->prepare($sql);
$ok = $stmt->execute([
    ":nome"           => $nome,
    ":cargo"          => $cargo,
    ":foto"           => $fotoNome,
    ":especialidades" => $especialidades,
    ":sobre"          => $sobre,
    ":horario"        => $horario,
]);

if ($ok) {
    header("Location: barbeiros.php?ok=1");
    exit;
}

die("Erro ao salvar barbeiro.");
