<?php
session_start();

if (isset($_SESSION["cliente_id"])) {
    header("Location: ../admin/servicos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login - Barbearia LV2</title>

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
        animation: bg 12s ease infinite;
        font-family: "Inter", sans-serif;
        color: white;
    }

    @keyframes bg {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* CONTAINER PREMIUM */
    .container {
        width: 90%;
        max-width: 430px;
        background: #171717;
        border: 1px solid #2d2d2d;
        box-shadow: 0 0 35px rgba(255,215,0,0.18);
        padding: 50px 40px;
        border-radius: 18px;
        text-align: center;
        animation: fadeIn 0.9s ease forwards;
        opacity: 0;
    }

    @keyframes fadeIn {
        to { opacity: 1; }
    }

    /* LOGO REDONDA PREMIUM */
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

    h2 {
        font-size: 30px;
        margin-bottom: 10px;
        color: #ffda44;
        font-weight: 700;
        letter-spacing: 1px;
    }

    p.subtitle {
        font-size: 15px;
        color: #cfcfcf;
        margin-bottom: 28px;
    }

    /* INPUTS PREMIUM */
    .input-box {
        margin-bottom: 16px;
        text-align: left;
    }

    input {
        width: 100%;
        padding: 14px 15px;
        border-radius: 10px;
        border: 1px solid #333;
        background: #111;
        color: #fff;
        font-size: 15px;
        transition: 0.2s;
    }

    input:focus {
        border-color: #ffda44;
        box-shadow: 0 0 12px rgba(255,215,0,0.35);
    }

    /* BOTÃO PREMIUM */
    button {
        width: 100%;
        background: #ffda44;
        padding: 14px;
        border: none;
        border-radius: 10px;
        margin-top: 10px;
        font-size: 17px;
        font-weight: 700;
        cursor: pointer;
        color: #000;
        transition: 0.25s;
        letter-spacing: 1px;
    }

    button:hover {
        background: #ffe88a;
        transform: translateY(-2px);
    }

    /* ALERTA DE ERRO */
    .alert {
        background: rgba(220, 20, 20, 0.45);
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 18px;
        font-size: 14px;
        border: 1px solid rgba(255, 0, 0, 0.3);
        backdrop-filter: blur(4px);
    }

    /* LINK REGISTRO */
    .register-link {
        margin-top: 18px;
        font-size: 14px;
    }

    .register-link a {
        color: #ffda44;
        font-weight: 600;
        text-decoration: none;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

</style>
</head>

<body>

<div class="container">

    <!-- LOGO -->
    <div class="logo-box">
        <img src="../assets/img/logob.png.png" alt="Logo da Barbearia">
    </div>

    <h2>Acesso do Cliente</h2>
    <p class="subtitle">Entre para agendar seu horário</p>

    <?php if (isset($_GET["erro"])): ?>
        <div class="alert">E-mail ou senha incorretos!</div>
    <?php endif; ?>

    <form action="login_process.php" method="POST">

        <div class="input-box">
            <input type="email" name="email" placeholder="Seu e-mail" required>
        </div>

        <div class="input-box">
            <input type="password" name="senha" placeholder="Sua senha" required>
        </div>

        <button type="submit">Entrar</button>
    </form>

    <p class="register-link">
        Não tem conta? <a href="register.php">Criar conta</a>
    </p>

</div>

</body>
</html>
