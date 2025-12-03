<?php
session_start();
require_once "../config/db.php";

if (empty($_SESSION["cliente_id"])) {
    header("Location: ../views/login.php");
    exit;
}

$conn = Database::connect();

$servico_id = isset($_GET["servico"]) ? (int) $_GET["servico"] : 0;
$data       = $_GET["data"] ?? null;
$hora       = $_GET["hora"] ?? null;

if ($servico_id <= 0 || empty($data) || empty($hora)) {
    header("Location: agendar.php");
    exit;
}

$stmt = $conn->prepare("
    SELECT nome 
    FROM servicos 
    WHERE id = :id AND ativo = 1
    LIMIT 1
");
$stmt->bindValue(':id', $servico_id, PDO::PARAM_INT);
$stmt->execute();
$servico = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servico) {
    header("Location: agendar.php");
    exit;
}

$stmt2 = $conn->query("
    SELECT id, nome, cargo, foto, especialidades 
    FROM barbeiros 
    WHERE ativo = 1 
    ORDER BY nome
");
$barbeiros = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Escolher Barbeiro - Barbearia LV2</title>

    <style>
        body {
            background: #0e0e0e;
            margin: 0;
            padding: 0;
            font-family: "Inter", sans-serif;
            color: #ffffff;
        }

        .main {
            max-width: 950px;
            margin: 50px auto;
            padding: 40px;
            background: #171717;
            border-radius: 16px;
            border: 1px solid #2d2d2d;
            box-shadow: 0 0 35px rgba(255, 215, 0, .18);
        }

        .header-info {
            text-align: center;
            margin-bottom: 35px;
        }

        .header-info h2 {
            font-size: 32px;
            color: #ffda44;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .header-info p {
            font-size: 15px;
            color: #bbbbbb;
        }

        .section-title {
            text-align: center;
            color: #ffda44;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }

        .card {
            background: #1c1c1c;
            border-radius: 12px;
            padding: 22px;
            display: flex;
            align-items: center;
            gap: 20px;
            border: 1px solid #2d2d2d;
            margin-bottom: 25px;
            transition: .25s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0px 0px 25px rgba(255, 215, 0, .25);
        }

        .photo {
            width: 95px;
            height: 95px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid rgba(255,215,0,0.45);
            flex-shrink: 0;
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .info p {
            margin: 4px 0 12px 0;
            font-size: 14px;
            color: #bbbbbb;
        }

        .tag {
            display: inline-block;
            background: #ffda44;
            color: #000;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 6px;
            margin-bottom: 4px;
        }

        .btn-agendar {
            margin-left: auto;
            background: #ffda44;
            color: #000;
            font-size: 15px;
            font-weight: 700;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: .25s ease;
            white-space: nowrap;
        }

        .btn-agendar:hover,
        .btn-agendar:focus-visible {
            background: #ffe88a;
            transform: translateY(-3px);
        }

        .btn-voltar {
            display: block;
            width: 220px;
            margin: 40px auto 0;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            background: transparent;
            color: #fff;
            text-decoration: none;
            border: 1px solid #555;
            transition: .25s ease;
        }

        .btn-voltar:hover,
        .btn-voltar:focus-visible {
            background: #fff;
            color: #000;
        }

        .no-barber {
            text-align: center;
            color: #bbbbbb;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="main">

    <div class="header-info">
        <h2><?= htmlspecialchars($servico["nome"]) ?></h2>
        <p>
            <strong>Data:</strong> <?= htmlspecialchars($data) ?>
            &nbsp; • &nbsp;
            <strong>Horário:</strong> <?= htmlspecialchars($hora) ?>
        </p>
    </div>

    <h3 class="section-title">Barbeiros Disponíveis</h3>

    <?php if (!empty($barbeiros)): ?>
        <?php foreach ($barbeiros as $b): ?>
            <?php
                $foto = !empty($b['foto']) ? $b['foto'] : 'default.jpg';
                $primeiroNome = explode(" ", trim($b["nome"]))[0] ?? $b["nome"];
                $especialidades = !empty($b["especialidades"])
                    ? explode(",", $b["especialidades"])
                    : [];
            ?>
            <div class="card">
                <div class="photo">
                    <img
                        src="../assets/barbeiros/<?= htmlspecialchars($foto) ?>"
                        alt="Foto de <?= htmlspecialchars($b['nome']) ?>"
                        onerror="this.src='../assets/barbeiros/default.jpg'">
                </div>

                <div class="info">
                    <h3><?= htmlspecialchars($b["nome"]) ?></h3>
                    <p><?= htmlspecialchars($b["cargo"] ?? '') ?></p>

                    <?php foreach ($especialidades as $t): 
                        $tag = trim($t);
                        if ($tag === '') continue;
                    ?>
                        <span class="tag"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>

                <a
                    class="btn-agendar"
                    href="finalizar_agendamento.php?barbeiro=<?= (int) $b['id'] ?>&servico=<?= (int) $servico_id ?>&data=<?= urlencode($data) ?>&hora=<?= urlencode($hora) ?>"
                >
                    Agendar com <?= htmlspecialchars($primeiroNome) ?>
                </a>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-barber">Nenhum barbeiro disponível no momento.</p>
    <?php endif; ?>

    <a href="javascript:history.back()" class="btn-voltar">Voltar</a>

</div>

</body>
</html>
