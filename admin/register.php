<?php
session_start();

$erro = $_GET["error"] ?? null;
$ok   = $_GET["success"] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Criar Administrador - Barbearia LV2</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:'Inter',sans-serif;
    background:linear-gradient(-45deg,#000,#111,#1a1a1a,#000);
    background-size:400% 400%;
    animation:bgMove 14s ease infinite;
    color:#fff;
}

@keyframes bgMove{
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

/* CONTAINER PREMIUM */
.box{
    width:90%;
    max-width:430px;
    padding:40px;
    border-radius:20px;
    background:#171717;
    border:1px solid #2d2d2d;
    backdrop-filter:blur(14px);
    box-shadow:0 0 40px rgba(255,215,0,.20);
    animation:fade .9s ease forwards;
    opacity:0;
    text-align:center;
}

@keyframes fade{
    to{opacity:1;}
}

h2{
    margin-bottom:25px;
    font-size:28px;
    font-weight:700;
    color:#ffda44;
    letter-spacing:1px;
}

/* INPUTS PREMIUM */
input{
    width:100%;
    padding:14px;
    border-radius:10px;
    border:1px solid #333;
    background:#111;
    color:white;
    font-size:15px;
    margin-bottom:16px;
    transition:.25s;
}

input:focus{
    border-color:#ffda44;
    box-shadow:0 0 12px rgba(255,215,0,0.35);
    outline:none;
}

/* ALERTAS */
.alert{
    padding:12px;
    border-radius:10px;
    margin-bottom:18px;
    font-size:14px;
    animation:pop .4s ease;
}

@keyframes pop{
    from{opacity:0;transform:scale(.95);}
    to{opacity:1;transform:scale(1);}
}

.erro{
    background:rgba(200,40,40,0.35);
    border:1px solid rgba(255,0,0,0.3);
    color:#ffb3b3;
}

.sucesso{
    background:rgba(46,204,113,0.30);
    border:1px solid rgba(46,204,113,0.45);
    color:#d8ffe8;
}

/* BOTÃO PREMIUM */
.btn{
    width:100%;
    padding:14px;
    background:#ffda44;
    border:none;
    border-radius:10px;
    font-weight:700;
    font-size:17px;
    cursor:pointer;
    color:#000;
    transition:.25s;
    margin-top:10px;
}

.btn:hover{
    background:#ffe88a;
    transform:translateY(-2px);
}

/* VOLTAR */
.btn-back{
    margin-top:18px;
    background:transparent;
    border:none;
    color:#ccc;
    font-size:14px;
    cursor:pointer;
    text-decoration:none;
    transition:.25s;
    display:block;
}

.btn-back:hover{
    color:#fff;
    text-decoration:underline;
}

</style>
</head>

<body>

<div class="box">

    <h2>Criar Administrador</h2>

    <!-- ALERTAS -->
    <?php if ($erro): ?>
        <div class="alert erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <?php if ($ok): ?>
        <div class="alert sucesso"><?= htmlspecialchars($ok) ?></div>
    <?php endif; ?>

    <form method="POST" action="../controllers/UsuarioController.php?action=register_admin">

        <input type="text" name="nome" placeholder="Nome completo" required>
        <input type="email" name="email" placeholder="E-mail do administrador" required>
        <input type="password" name="senha" placeholder="Crie uma senha" required>

        <button class="btn">Criar Conta</button>
    </form>

    <a href="login_admin.php" class="btn-back">← Voltar ao login</a>

</div>

</body>
</html>
