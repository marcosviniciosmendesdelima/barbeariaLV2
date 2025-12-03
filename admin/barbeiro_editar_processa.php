<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["id"])) {
    header("Location: barbeiros.php");
    exit;
}

$conn = Database::connect();

$id             = (int) ($_POST["id"] ?? 0);
$nome           = trim($_POST["nome"] ?? "");
$cargo          = trim($_POST["cargo"] ?? "");
$especialidades = trim($_POST["especialidades"] ?? "");
$horario        = trim($_POST["horario_atendimento"] ?? "");
$sobre          = trim($_POST["sobre"] ?? "");
$removerFoto    = (int) ($_POST["removerFoto"] ?? 0);

$stmt = $conn->prepare("SELECT foto FROM barbeiros WHERE id = :id");
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$barberAtual = $stmt->fetch(PDO::FETCH_ASSOC);

$fotoAtual = $barberAtual["foto"] ?? null;
$novaFoto  = $fotoAtual;

$dirFotos = "../assets/barbeiros/";

if (!is_dir($dirFotos)) {
    mkdir($dirFotos, 0777, true);
}

if (!empty($_FILES["foto"]["name"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
    $permitidos = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($ext, $permitidos, true)) {
        die("Erro: Formato de imagem inválido.");
    }

    $novaFoto = uniqid("barbeiro_", true) . "." . $ext;
    $destino  = $dirFotos . $novaFoto;

    if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $destino)) {
        die("Erro ao enviar a imagem.");
    }

    if (!empty($fotoAtual)) {
        $caminhoAntigo = $dirFotos . $fotoAtual;
        if (file_exists($caminhoAntigo)) {
            unlink($caminhoAntigo);
        }
    }
}

if ($removerFoto === 1) {
    if (!empty($fotoAtual)) {
        $caminhoAntigo = $dirFotos . $fotoAtual;
        if (file_exists($caminhoAntigo)) {
            unlink($caminhoAntigo);
        }
    }
    $novaFoto = null;
}

$sqlUp = "
    UPDATE barbeiros SET
        nome = :nome,
        cargo = :cargo,
        especialidades = :especialidades,
        sobre = :sobre,
        horario_atendimento = :horario,
        foto = :foto
    WHERE id = :id
";

$stmtUp = $conn->prepare($sqlUp);
$ok = $stmtUp->execute([
    ":nome"           => $nome,
    ":cargo"          => $cargo,
    ":especialidades" => $especialidades,
    ":sobre"          => $sobre,
    ":horario"        => $horario,
    ":foto"           => $novaFoto,
    ":id"             => $id,
]);

if ($ok) {
    header("Location: barbeiros.php?edit_ok=1");
    exit;
}

die("Erro ao atualizar barbeiro.");
