<?php
session_start();
require_once "../config/db.php";

$conn = Database::connect();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

if (!isset($_GET["id"])) {
    echo "<script>alert('Agendamento inválido.'); window.location.href='agenda.php';</script>";
    exit;
}

$id = intval($_GET["id"]);

$sql = "UPDATE agendamentos SET status = 'concluido' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Status atualizado</title>
    <meta http-equiv="refresh" content="2; URL=agenda.php">

    <style>
        body {
            background:#0d0d0d;
            color:#fff;
            font-family: Poppins, Arial, sans-serif;
            padding-top:100px;
            text-align:center;
        }

        .box {
            display:inline-block;
            background:#1a1a1a;
            padding:40px 60px;
            border-radius:12px;
            border:1px solid #333;
            box-shadow:0 0 25px rgba(241,196,15,.2);
        }

        h1 {
            color:#f1c40f;
            font-size:28px;
            margin-bottom:10px;
        }

        p {
            color:#bbb;
        }

        a {
            display:inline-block;
            margin-top:20px;
            padding:10px 20px;
            background:#f1c40f;
            color:#000;
            border-radius:8px;
            text-decoration:none;
            font-weight:600;
        }
        a:hover {
            background:#ffe27c;
        }
    </style>
</head>

<body>

<div class="box">
    <h1>Agendamento Concluído</h1>
    <p>O agendamento foi marcado como concluído com sucesso.</p>
    <p>Você será redirecionado automaticamente...</p>

    <a href="agenda.php">Voltar agora</a>
</div>

</body>
</html>
