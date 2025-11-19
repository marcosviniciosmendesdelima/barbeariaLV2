<?php
session_start();

// Se já estiver logado, redireciona
if (isset($_SESSION["cliente_id"])) {
    header("Location: agendar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login - Barbearia LV2</title>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(-45deg, #0a0a0a, #111, #1a1a1a, #000);
        background-size: 300% 300%;
        animation: bg 12s ease infinite;
        font-family: "Poppins", sans-serif;
        color: white;
    }

    @keyframes bg {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .container {
        width: 90%;
        max-width: 420px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(18px);
        padding: 45px 40px;
        border-radius: 18px;
        text-align: center;
        animation: fadeIn 0.9s ease forwards;
        opacity: 0;
    }

    @keyframes fadeIn {
        to { opacity: 1; }
    }

    /* Logo */
    .logo-box {
        width: 120px;
        height: 120px;
        margin: 0 auto 15px auto;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid rgba(255, 215, 0, 0.4);
        box-shadow: 0 0 25px rgba(255, 215, 0, 0.25);
    }

    .logo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    h2 {
        font-size: 28px;
        margin-bottom: 12px;
        color: #f1c40f;
        font-weight: 600;
    }

    p.subtitle {
        font-size: 15px;
        color: #d4d4d4;
        margin-bottom: 30px;
    }

    .input-box {
        margin-bottom: 15px;
        text-align: left;
    }

    input {
        width: 100%;
        padding: 13px 15px;
        border-radius: 8px;
        border: 1px solid transparent;
        background: rgba(255,255,255,0.09);
        color: #fff;
        font-size: 15px;
        transition: 0.2s;
    }

    input:focus {
        border: 1px solid #f1c40f;
        background: rgba(255,255,255,0.15);
    }

    button {
        width: 100%;
        background: #f1c40f;
        padding: 13px;
        border: none;
        border-radius: 8px;
        margin-top: 10px;
        font-size: 17px;
        font-weight: 600;
        cursor: pointer;
        color: #000;
        transition: 0.25s;
        letter-spacing: 1px;
    }

    button:hover {
        background: #ffdf4f;
        transform: translateY(-2px);
    }

    /* erro */
    .alert {
        background: rgba(220, 20, 20, 0.45);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 14px;
        border: 1px solid rgba(255, 0, 0, 0.3);
        backdrop-filter: blur(4px);
    }

    .register-link {
        margin-top: 18px;
        font-size: 14px;
    }

    .register-link a {
        color: #f1c40f;
        font-weight: bold;
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
        <img src="../assets/img/logo.png.png" alt="Logo da Barbearia"
             onerror="this.style.display='none'">
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
<?php
session_start();

// Se já estiver logado, redireciona
if (isset($_SESSION["cliente_id"])) {
    header("Location: agendar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login - Barbearia LV2</title>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(-45deg, #0a0a0a, #111, #1a1a1a, #000);
        background-size: 300% 300%;
        animation: bg 12s ease infinite;
        font-family: "Poppins", sans-serif;
        color: white;
    }

    @keyframes bg {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .container {
        width: 90%;
        max-width: 420px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(18px);
        padding: 45px 40px;
        border-radius: 18px;
        text-align: center;
        animation: fadeIn 0.9s ease forwards;
        opacity: 0;
    }

    @keyframes fadeIn {
        to { opacity: 1; }
    }

    /* Logo */
    .logo-box {
        width: 120px;
        height: 120px;
        margin: 0 auto 15px auto;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid rgba(255, 215, 0, 0.4);
        box-shadow: 0 0 25px rgba(255, 215, 0, 0.25);
    }

    .logo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    h2 {
        font-size: 28px;
        margin-bottom: 12px;
        color: #f1c40f;
        font-weight: 600;
    }

    p.subtitle {
        font-size: 15px;
        color: #d4d4d4;
        margin-bottom: 30px;
    }

    .input-box {
        margin-bottom: 15px;
        text-align: left;
    }

    input {
        width: 100%;
        padding: 13px 15px;
        border-radius: 8px;
        border: 1px solid transparent;
        background: rgba(255,255,255,0.09);
        color: #fff;
        font-size: 15px;
        transition: 0.2s;
    }

    input:focus {
        border: 1px solid #f1c40f;
        background: rgba(255,255,255,0.15);
    }

    button {
        width: 100%;
        background: #f1c40f;
        padding: 13px;
        border: none;
        border-radius: 8px;
        margin-top: 10px;
        font-size: 17px;
        font-weight: 600;
        cursor: pointer;
        color: #000;
        transition: 0.25s;
        letter-spacing: 1px;
    }

    button:hover {
        background: #ffdf4f;
        transform: translateY(-2px);
    }

    /* erro */
    .alert {
        background: rgba(220, 20, 20, 0.45);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 14px;
        border: 1px solid rgba(255, 0, 0, 0.3);
        backdrop-filter: blur(4px);
    }

    .register-link {
        margin-top: 18px;
        font-size: 14px;
    }

    .register-link a {
        color: #f1c40f;
        font-weight: bold;
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
        <img src="../assets/img/logo.png.png" alt="Logo da Barbearia"
             onerror="this.style.display='none'">
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
