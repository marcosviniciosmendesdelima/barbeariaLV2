<?php
session_start();

// Se já estiver logado como admin, manda para o painel
if (isset($_SESSION["admin_id"])) {
    header("Location: painel.php");
    exit;
}

$erro    = isset($_GET["erro"]);
$created = isset($_GET["created"]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login Admin - Barbearia LV2</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

body {
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(-45deg, #000, #111, #1a1a1a, #0c0c0c);
    background-size: 400% 400%;
    animation: bgMove 12s ease infinite;
    font-family: "Inter", sans-serif;
    color: white;
}

@keyframes bgMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Container premium */
.container {
    width: 90%;
    max-width: 430px;
    background: #171717;
    border: 1px solid #2d2d2d;
    backdrop-filter: blur(14px);
    padding: 50px 40px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 0 40px rgba(255,215,0,0.20);
    animation: fadeIn 1s ease forwards;
    opacity: 0;
}

@keyframes fadeIn {
    to { opacity: 1; }
}

/* Logo redonda dourada */
.logo-box {
    width: 130px;
    height: 130px;
    margin: 0 auto 18px auto;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid rgba(255,215,0,0.45);
    box-shadow: 0 0 22px rgba(255,215,0,0.30);
}

.logo-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Títulos */
h2 {
    font-size: 30px;
    margin-bottom: 10px;
    color: #ffda44;
    font-weight: 700;
    letter-spacing: 1px;
}

.subtitle {
    color: #cfcfcf;
    margin-bottom: 30px;
}

/* Inputs */
.input-box {
    margin-bottom: 16px;
    text-align: left;
}

input {
    width: 100%;
    padding: 14px;
    border-radius: 10px;
    border: 1px solid #333;
    background: #111;
    color: #fff;
    font-size: 15px;
    transition: 0.25s;
}

input:focus {
    border-color: #ffda44;
    box-shadow: 0 0 12px rgba(255,215,0,0.35);
}

/* Botão admin */
button {
    width: 100%;
    background: #ffda44;
    padding: 14px;
    border: none;
    border-radius: 10px;
    font-size: 17px;
    font-weight: 700;
    cursor: pointer;
    color: #000;
    transition: .25s;
}

button:hover {
    background: #ffe88a;
    transform: translateY(-2px);
}

/* Alertas */
.alert {
    background: rgba(200, 30, 30, 0.45);
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 16px;
    border: 1px solid rgba(200, 30, 30, 0.4);
}

.alert-success {
    background: rgba(25, 220, 120, 0.30);
    border: 1px solid rgba(25, 220, 120, 0.45);
    color: #d3ffe7;
}

/* Links */
.link-btn {
    margin-top: 18px;
    display: block;
    padding: 12px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: .25s;
    font-size: 15px;
}

.link-create {
    background: rgba(255,255,255,0.12);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.18);
}
.link-create:hover {
    background: rgba(255,255,255,0.22);
    transform: translateY(-2px);
}

.link-back {
    margin-top: 14px;
    font-size: 14px;
    color: #aaa;
}
.link-back:hover {
    color: #fff;
    text-decoration: underline;
}

</style>
</head>

<body>

<div class="container">

    <div class="logo-box">
        <img src="../assets/img/logob.png.png" alt="Logo da Barbearia">
    </div>

    <h2>Acesso Administrativo</h2>
    <p class="subtitle">Entre para gerenciar o sistema</p>

    <?php if ($erro): ?>
        <div class="alert">E-mail ou senha incorretos.</div>
    <?php endif; ?>

    <?php if ($created): ?>
        <div class="alert alert-success">Administrador criado com sucesso!</div>
    <?php endif; ?>

    <form method="POST" action="login_admin_process.php">

        <div class="input-box">
            <input type="email" name="email" placeholder="E-mail do administrador" required>
        </div>

        <div class="input-box">
            <input type="password" name="senha" placeholder="Senha" required>
        </div>

        <button type="submit">Entrar</button>
    </form>

    <a href="register.php" class="link-btn link-create">Criar Conta de Administrador</a>
    <a href="../index.html" class="link-back">← Voltar ao início</a>

</div>

</body>
</html>
