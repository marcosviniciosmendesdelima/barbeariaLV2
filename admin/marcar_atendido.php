<?php
session_start();
require_once "../config/db.php";

if (empty($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

$conn = Database::connect();

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: agenda.php?erro=agendamento");
    exit;
}

$stmt = $conn->prepare("UPDATE agendamentos SET status = 'concluido' WHERE id = ?");
$stmt->execute([$id]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Agendamento Concluído</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta http-equiv="refresh" content="2;URL=agenda.php">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
    body {
        background: #0d0d0d;
        color: #ffffff;
        font-family: "Poppins", sans-serif;
        padding-top: 120px;
        text-align: center;
        margin: 0;
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
        color: #cccccc;
        margin-bottom: 20px;
    }
    a {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 20px;
        background: #f1c40f;
        color: #000000;
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
