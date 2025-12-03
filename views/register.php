<?php
session_start();

if (isset($_SESSION["cliente_id"])) {
    header("Location: agendar.php");
    exit;
}

$erro = isset($_GET["erro"]) ? trim($_GET["erro"]) : null;
$ok   = isset($_GET["ok"]) ? trim($_GET["ok"]) : null;

$mensagens = [
    "campo_vazio"    => "Preencha todos os campos.",
    "email_invalido" => "Digite um e-mail válido.",
    "senha_fraca"    => "A senha deve ter pelo menos 4 caracteres.",
    "email_uso"      => "Este e-mail já está cadastrado.",
    "banco"          => "Erro ao salvar. Tente novamente."
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Criar Conta - Barbearia LV2</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }
    body {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(-45deg, #000, #121212, #1a1a1a, #000);
        background-size: 400% 400%;
        animation: bgMove 12s ease infinite;
        color: #ffffff;
        padding: 20px;
    }
    @keyframes bgMove {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .container {
        width: 100%;
        max-width: 420px;
        padding: 40px;
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 18px;
        backdrop-filter: blur(14px);
        box-shadow: 0 0 25px rgba(255,193,7,0.18);
        text-align: center;
        animation: fadeIn .9s ease forwards;
        opacity: 0;
    }
    @keyframes fadeIn {
        to { opacity: 1; }
    }
    h2 {
        font-size: 28px;
        font-weight: 600;
        color: #f1c40f;
        margin-bottom: 25px;
    }
    .alert {
        padding: 14px;
        border-radius: 10px;
        margin-bottom: 18px;
        font-size: 14px;
        animation: alertPop .5s ease;
    }
    @keyframes alertPop {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .erro {
        background: rgba(200, 40, 40, 0.4);
        border: 1px solid rgba(255,0,0,0.3);
        color: #ffb3b3;
        backdrop-filter: blur(8px);
    }
    .sucesso {
        background: rgba(46, 204, 113, 0.4);
        border: 1px solid rgba(46,204,113,0.3);
        color: #d6ffe8;
        backdrop-filter: blur(8px);
    }
    input {
        width: 100%;
        padding: 14px;
        margin-bottom: 16px;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.2);
        background: rgba(255,255,255,0.12);
        color: #ffffff;
        font-size: 16px;
        transition: .25s;
    }
    input:focus {
        border-color: #f1c40f;
        box-shadow: 0 0 10px rgba(241,196,15,0.4);
        outline: none;
    }
    button {
        width: 100%;
        padding: 14px;
        margin-top: 10px;
        background: #f1c40f;
        color: #000000;
        border: none;
        border-radius: 10px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        transition: .25s;
    }
    button:hover {
        background: #ffdf55;
        transform: translateY(-2px);
    }
    .link {
        display: block;
        margin-top: 18px;
        text-decoration: none;
        color: #f1c40f;
        font-weight: 600;
    }
    .link:hover {
        text-decoration: underline;
    }
</style>
</head>

<body>

<div class="container">
    <h2>Criar Conta</h2>

    <?php if ($erro && isset($mensagens[$erro])): ?>
        <div class="alert erro">
            <?= htmlspecialchars($mensagens[$erro], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if ($ok): ?>
        <div class="alert sucesso">
            Conta criada com sucesso! Faça login.
        </div>
    <?php endif; ?>

    <form action="register_process.php" method="POST" novalidate>
        <input type="text" name="nome" placeholder="Nome completo" required>
        <input type="email" name="email" placeholder="Seu e-mail" required>
        <input type="password" name="senha" placeholder="Crie sua senha" required>
        <button type="submit">Cadastrar</button>
    </form>

    <a href="login.php" class="link">Já tenho conta</a>
</div>

</body>
</html>
