<?php
session_start();
require_once "../config/db.php";

$conn = Database::connect();

// validar admin
if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

// Buscar barbeiros
$sql = "SELECT * FROM barbeiros ORDER BY nome";
$stmt = $conn->prepare($sql);
$stmt->execute();
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
        color:white;
        font-family:Poppins, sans-serif;
        padding:40px;
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
        transition:0.25s;
        font-size:14px;
    }

    .btn-yellow{
        background:#f1c40f;
        color:black;
    }
    .btn-yellow:hover{
        background:#ffdd55;
    }

    .btn-edit{
        background:#3498db;
        color:white;
    }
    .btn-edit:hover{ background:#5dade2; }

    .btn-delete{
        background:#e74c3c;
        color:white;
    }
    .btn-delete:hover{ background:#ff6f61; }

    table{
        width:100%;
        border-collapse:collapse;
        margin-top:10px;
    }

    th, td{
        padding:12px;
        border-bottom:1px solid rgba(255,255,255,0.1);
        text-align:left;
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

        <?php foreach($barbeiros as $b): ?>
        <tr>
            <td>
                <?php if (!empty($b['foto'])): ?>
                    <img src="../assets/barbeiros/<?= $b['foto'] ?>" 
                         class="foto"
                         onerror="this.src='../assets/barbeiros/default.jpg'">
                <?php else: ?>
                    <img src="../assets/barbeiros/default.jpg" class="foto">
                <?php endif; ?>
            </td>

            <td><?= htmlspecialchars($b['nome']) ?></td>
            <td><?= htmlspecialchars($b['cargo']) ?></td>
            <td><?= htmlspecialchars($b['especialidades']) ?></td>

            <td>
                <a href="barbeiro_editar.php?id=<?= $b['id'] ?>" class="btn btn-edit">Editar</a>
                <a href="barbeiro_excluir.php?id=<?= $b['id'] ?>" 
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
