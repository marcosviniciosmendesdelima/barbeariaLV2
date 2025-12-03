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
    die("ID inválido.");
}

$stmt = $conn->prepare("SELECT * FROM barbeiros WHERE id = :id LIMIT 1");
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$barbeiro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barbeiro) {
    die("Barbeiro não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Barbeiro</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
    body{
        background:#0d0d0d;
        color:#fff;
        font-family:'Poppins',sans-serif;
        padding:40px;
        margin:0;
    }
    .container{
        max-width:700px;
        margin:auto;
        background:rgba(255,255,255,0.06);
        padding:30px;
        border-radius:14px;
        border:1px solid rgba(255,255,255,0.1);
        box-shadow:0 0 25px rgba(255,193,7,0.25);
    }
    h1{
        text-align:center;
        color:#f1c40f;
        margin-bottom:25px;
    }
    label{
        font-weight:600;
        margin-top:15px;
        display:block;
    }
    input,textarea{
        width:100%;
        padding:12px;
        margin-top:5px;
        border-radius:8px;
        border:none;
        background:#1a1a1a;
        color:#fff;
        outline:none;
        font-size:14px;
    }
    textarea{
        height:110px;
        resize:none;
    }
    .btn{
        margin-top:25px;
        width:100%;
        padding:14px;
        border-radius:10px;
        font-weight:600;
        border:none;
        cursor:pointer;
        transition:.25s;
        font-size:15px;
        text-align:center;
        display:inline-block;
    }
    .btn-yellow{
        background:#f1c40f;
        color:#000;
    }
    .btn-yellow:hover{
        background:#ffdd55;
    }
    .btn-back{
        background:#444;
        color:#fff;
        margin-top:10px;
    }
    .btn-back:hover{
        background:#555;
    }
    .preview{
        margin-top:10px;
        text-align:center;
    }
    .preview img{
        width:130px;
        height:130px;
        border-radius:50%;
        object-fit:cover;
        border:3px solid rgba(241,196,15,0.5);
    }
    .delete-foto{
        color:#ff4444;
        font-size:14px;
        cursor:pointer;
        text-decoration:underline;
        display:block;
        text-align:center;
        margin-top:8px;
    }
</style>

<script>
function previewFoto(event){
    const imagem = document.getElementById("fotoPreview");
    if (event.target.files && event.target.files[0]) {
        imagem.src = URL.createObjectURL(event.target.files[0]);
        imagem.style.display = "block";
    }
}
function removerFoto(){
    if (confirm("Deseja remover a foto atual?")) {
        document.getElementById("removerFoto").value = "1";
        const atual = document.getElementById("fotoAtual");
        if (atual) {
            atual.style.display = "none";
        }
    }
}
</script>
</head>

<body>

<div class="container">

    <h1>Editar Barbeiro</h1>

    <form action="barbeiro_editar_processa.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?= (int)$barbeiro['id'] ?>">
        <input type="hidden" id="removerFoto" name="removerFoto" value="0">

        <label>Nome do Barbeiro</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($barbeiro['nome'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Cargo</label>
        <input type="text" name="cargo" value="<?= htmlspecialchars($barbeiro['cargo'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Especialidades</label>
        <input type="text" name="especialidades" value="<?= htmlspecialchars($barbeiro['especialidades'], ENT_QUOTES, 'UTF-8') ?>">

        <label>Horário de Atendimento</label>
        <input type="text" name="horario_atendimento" value="<?= htmlspecialchars($barbeiro['horario_atendimento'], ENT_QUOTES, 'UTF-8') ?>">

        <label>Sobre</label>
        <textarea name="sobre"><?= htmlspecialchars($barbeiro['sobre'], ENT_QUOTES, 'UTF-8') ?></textarea>

        <label>Foto Atual</label>
        <div class="preview">
            <?php if (!empty($barbeiro['foto'])): ?>
                <img id="fotoAtual" src="../assets/barbeiros/<?= htmlspecialchars($barbeiro['foto'], ENT_QUOTES, 'UTF-8') ?>" alt="Foto atual">
                <span class="delete-foto" onclick="removerFoto()">Remover foto</span>
            <?php else: ?>
                <p style="color:#aaa;">Nenhuma foto cadastrada</p>
            <?php endif; ?>
        </div>

        <label>Nova Foto (opcional)</label>
        <input type="file" name="foto" accept="image/*" onchange="previewFoto(event)">

        <div class="preview">
            <img id="fotoPreview" style="display:none;" alt="Pré-visualização da foto">
        </div>

        <button class="btn btn-yellow" type="submit">Salvar Alterações</button>
        <a href="barbeiros.php" class="btn btn-back">Voltar</a>

    </form>

</div>

</body>
</html>
