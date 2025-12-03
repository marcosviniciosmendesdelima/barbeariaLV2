<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastrar Barbeiro</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
    background:#0e0e0e;
    color:#fff;
    font-family:"Inter",sans-serif;
    padding:50px 18px;
}
.container-box{
    max-width:750px;
    margin:auto;
    background:#171717;
    padding:40px;
    border-radius:18px;
    border:1px solid #2d2d2d;
    box-shadow:0 0 45px rgba(255,215,0,0.18);
}
h1{
    text-align:center;
    color:#ffda44;
    margin-bottom:30px;
    font-weight:700;
    font-size:30px;
    letter-spacing:1px;
}
label{
    font-weight:600;
    margin-top:20px;
    display:block;
    color:#ffda44;
}
input,textarea{
    width:100%;
    padding:13px;
    margin-top:6px;
    border-radius:10px;
    border:1px solid #333;
    background:#111;
    color:#fff;
    outline:none;
    font-size:15px;
    transition:.25s;
}
input:focus,textarea:focus{
    border-color:#ffda44;
    box-shadow:0 0 12px rgba(255,215,0,0.35);
}
textarea{
    height:110px;
    resize:none;
}
.btn{
    margin-top:30px;
    width:100%;
    padding:15px;
    border-radius:10px;
    font-weight:700;
    border:none;
    cursor:pointer;
    transition:.25s;
    font-size:16px;
}
.btn-yellow{
    background:#ffda44;
    color:#000;
}
.btn-yellow:hover{
    background:#ffe88a;
    transform:translateY(-2px);
}
.btn-back{
    background:#444;
    color:#fff;
    margin-top:12px;
}
.btn-back:hover{
    background:#555;
}
.preview{
    margin-top:20px;
    text-align:center;
}
.preview img{
    width:140px;
    height:140px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid rgba(255,215,0,0.45);
    box-shadow:0 0 18px rgba(255,215,0,0.25);
    display:none;
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
</script>
</head>

<body>

<div class="container-box">

    <h1>Cadastrar Novo Barbeiro</h1>

    <form action="barbeiro_novo_processa.php" method="POST" enctype="multipart/form-data">

        <label>Nome do Barbeiro</label>
        <input type="text" name="nome" required>

        <label>Cargo</label>
        <input type="text" name="cargo" placeholder="Ex: Barbeiro Profissional, Especialista em Fade..." required>

        <label>Especialidades (separadas por vírgulas)</label>
        <input type="text" name="especialidades" placeholder="Ex: Degradê, Navalhado, Barba, Tesoura" required>

        <label>Horário de Atendimento</label>
        <input type="text" name="horario_atendimento" placeholder="Ex: Seg à Sáb — 09h às 18h" required>

        <label>Sobre o Barbeiro</label>
        <textarea name="sobre" placeholder="Escreva uma descrição breve..."></textarea>

        <label>Foto (opcional)</label>
        <input type="file" name="foto" accept="image/*" onchange="previewFoto(event)">

        <div class="preview">
            <img id="fotoPreview" alt="Preview da foto">
        </div>

        <button class="btn btn-yellow" type="submit">Salvar Barbeiro</button>

        <a href="barbeiros.php">
            <button type="button" class="btn btn-back">Voltar</button>
        </a>

    </form>

</div>

</body>
</html>
