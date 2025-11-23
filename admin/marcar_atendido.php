<?php
session_start();
require_once "../config/db.php";

// Verifica admin logado
if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

$conn = Database::connect();

// Verifica ID recebido
if (!isset($_GET["id"])) {
    echo "<script>alert('ID inválido.'); window.location.href='agenda.php';</script>";
    exit;
}

$id = intval($_GET["id"]);

// Atualizar status para CONCLUIDO
$sql = "UPDATE agendamentos SET status = 'concluido' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Agendamento Concluído</title>

<!-- Redirecionar após 2 segundos -->
<meta http-equiv="refresh" content="2;URL=agenda.php">

<style>
    body {
        background: #0d0d0d;
        color: white;
        font-family: Poppins, sans-serif;
        padding-top: 120px;
        text-align: center;
    }

    .box {
        display: inline-block;
        background: #1a1a1a;
        padding: 40px 60px;
        border-radius: 14px;
        border: 1px solid #333;
        box-shadow: 0 0 20px rgba(241,196,15,0.20);
    }

    h1 {
        color: #f1c40f;
        font-size: 28px;
        margin-bottom: 10px;
    }

    p {
        color: #ccc;
        margin-bottom: 20px;
    }

    a {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 20px;
        background: #f1c40f;
        color: #000;
        font-weight: bold;
        text-decoration: none;
        border-radius: 8px;
        transition: .25s;
    }

    a:hover {
        background: #ffdd57;
    }
</style>
</head>

<body>

<div class="box">
    <h1>Agendamento Concluído</h1>
    <p>Status atualizado com sucesso.</p>
    <p>Você será redirecionado automaticamente...</p>

    <a href="agenda.php">Voltar agora</a>
</div>

</body>
</html>
