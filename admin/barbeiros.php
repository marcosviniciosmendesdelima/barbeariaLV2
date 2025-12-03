<?php
session_start();
require_once "../config/db.php";

$conn = Database::connect();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

$stmt = $conn->query("SELECT * FROM barbeiros ORDER BY nome");
$barbeiros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Barbeiros - Administração</title>

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
        max-width:1100px;
        margin:auto;
        background:rgba(255,255,255,0.05);
        padding:30px;
        border-radius:16px;
        border:1px solid rgba(255,255,255,0.12);
        box-shadow:0 0 25px rgba(255,193,7,0.2);
    }
    h1{
        text-align:center;
        color:#f1c40f;
        margin-bottom:30px;
        font-weight:600;
    }
    .top-actions{
        display:flex;
        justify-content:flex-end;
        margin-bottom:20px;
    }
    .btn{
        display:inline-block;
        padding:10px 18px;
        border-radius:10px;
        text-decoration:none;
        font-weight:600;
        transition:.25s;
        font-size:14px;
    }
    .btn-yellow{
        background:#f1c40f;
        color:#000;
    }
    .btn-yellow:hover{
        background:#ffdd55;
    }
    .btn-edit{
        background:#3498db;
        color:#fff;
    }
    .btn-edit:hover{
        background:#5dade2;
    }
    .btn-delete{
        background:#e74c3c;
        color:#fff;
    }
    .btn-delete:hover{
        background:#ff6f61;
    }
    table{
        width:100%;
        border-collapse:collapse;
        margin-top:10px;
    }
    th,td{
        padding:12px;
        border-bottom:1px solid rgba(255,255,255,0.1);
        text-align:left;
        vertical-align:middle;
    }
    th{
        color:#f1c40f;
        font-size:15px;
    }
    .foto{
        width:60px;
        height:60px;
        object-fit:cover;
        border-radius:50%;
        border:2px solid rgba(241,196,15,0.4);
    }
</style>
</head>

<body>

<div class="container">

    <h1>Gerenciar Barbeiros</h1>

    <div class="top-actions">
        <a href="barbeiro_novo.php" class="btn btn-yellow">Cadastrar Novo Barbeiro</a>
    </div>

    <table>
        <tr>
            <th>Foto</th>
            <th>Nome</th>
            <th>Cargo</th>
            <th>Especialidades</th>
            <th>Ações</th>
        </tr>

        <?php foreach ($barbeiros as $b): ?>
        <tr>
            <td>
                <?php if (!empty($b['foto'])): ?>
                    <img
                        src="../assets/barbeiros/<?= htmlspecialchars($b['foto'], ENT_QUOTES, 'UTF-8') ?>"
                        class="foto"
                        alt="Foto de <?= htmlspecialchars($b['nome'], ENT_QUOTES, 'UTF-8') ?>"
                        onerror="this.src='../assets/barbeiros/default.jpg'">
                <?php else: ?>
                    <img
                        src="../assets/barbeiros/default.jpg"
                        class="foto"
                        alt="Barbeiro sem foto">
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($b['nome'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($b['cargo'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($b['especialidades'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
            <td>
                <a href="barbeiro_editar.php?id=<?= (int) $b['id'] ?>" class="btn btn-edit">Editar</a>
                <a
                    href="barbeiro_excluir.php?id=<?= (int) $b['id'] ?>"
                    class="btn btn-delete"
                    onclick="return confirm('Tem certeza que deseja excluir este barbeiro?');">
                    Excluir
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div style="text-align:center;margin-top:30px;">
        <a href="painel.php" class="btn btn-yellow">Voltar ao Painel</a>
    </div>

</div>

</body>
</html>
