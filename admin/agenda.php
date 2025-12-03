<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit;
}

$conn = Database::connect();

$filtro = $_GET["dia"] ?? "hoje";

$hoje   = date("Y-m-d");
$amanha = date("Y-m-d", strtotime("+1 day"));
$ontem  = date("Y-m-d", strtotime("-1 day"));

if ($filtro === "hoje") {
    $dataFiltro = $hoje;
    $nomeDia = "Hoje";
}
elseif ($filtro === "amanha") {
    $dataFiltro = $amanha;
    $nomeDia = "Amanhã";
}
elseif ($filtro === "ontem") {
    $dataFiltro = $ontem;
    $nomeDia = "Ontem";
}
else {
    $dataFiltro = $hoje;
    $nomeDia = "Hoje";
}

$barbeiroExpandido = isset($_GET["expandir"]) ? intval($_GET["expandir"]) : null;

$barbeiros = $conn->query("SELECT * FROM barbeiros ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);

function buscarAgendamentos($conn, $barbeiro_id, $mostrarTodos, $dataFiltro) {
    $sql = "SELECT a.*, u.nome AS cliente, s.nome AS servico
            FROM agendamentos a
            JOIN usuarios u ON u.id = a.usuario_id
            JOIN servicos s ON s.id = a.servico_id
            WHERE a.barbeiro_id = ? AND a.data_agendamento = ?
            ORDER BY a.hora_agendamento ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$barbeiro_id, $dataFiltro]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Agenda dos Barbeiros</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body {
    background:#0e0e0e;
    color:white;
    font-family:"Inter", sans-serif;
    padding:45px;
}

h1 {
    text-align:center;
    color:#ffda44;
    margin-bottom:30px;
    font-size:36px;
    font-weight:700;
    letter-spacing:1px;
}

.filtros {
    text-align:center;
    margin-bottom:35px;
}

.filtros a {
    padding:10px 20px;
    background:#171717;
    border:1px solid #333;
    color:#ffda44;
    margin:0 6px;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
    transition:.25s ease;
}

.filtros a.active {
    background:#ffda44;
    color:#000;
}

.filtros a:hover {
    background:#ffe88a;
    color:black;
}

.grid {
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:30px;
}

.card-barbeiro {
    width:340px;
    background:#171717;
    border-radius:15px;
    padding:25px;
    border:1px solid #2d2d2d;
    box-shadow:0 0 30px rgba(255,215,0,0.15);
    transition:.25s;
}

.card-barbeiro:hover {
    transform:translateY(-5px);
    box-shadow:0 0 40px rgba(255,215,0,0.25);
}

.card-barbeiro img {
    width:110px;
    height:110px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #ffda44;
    display:block;
    margin:auto;
}

.nome {
    text-align:center;
    color:#ffda44;
    font-weight:700;
    font-size:20px;
    margin-top:12px;
}

.cargo {
    text-align:center;
    color:#ccc;
    margin-bottom:18px;
}

.titulo-agenda {
    color:#ffda44;
    font-weight:600;
    text-align:center;
    margin-bottom:14px;
    border-bottom:1px solid #333;
    padding-bottom:6px;
}

.agendamento {
    background:#111;
    padding:12px;
    margin-bottom:12px;
    border-left:4px solid #ffda44;
    border-radius:8px;
}

.status {
    padding:4px 8px;
    border-radius:6px;
    font-size:12px;
    font-weight:700;
}

.pendente  { background:#777; color:white; }
.confirmado{ background:#1c4; color:white; }
.cancelado { background:#b11; color:white; }
.atendido  { background:#226cff; color:white; }

.btn-card {
    padding:6px 10px;
    border-radius:6px;
    text-decoration:none;
    font-size:13px;
    font-weight:700;
    display:inline-block;
    margin-top:6px;
}

.btn-concluir { background:#226cff; color:white; }
.btn-cancelar { background:#b71c1c; color:white; }

.btn-ver {
    display:block;
    background:#ffda44;
    text-align:center;
    padding:12px;
    margin-top:18px;
    border-radius:8px;
    font-weight:700;
    color:black;
    text-decoration:none;
    transition:.25s ease;
}

.btn-ver:hover {
    background:#ffe88a;
}

.btn-voltar {
    background:#ffda44;
    padding:14px 28px;
    border-radius:8px;
    font-weight:700;
    color:black;
    text-decoration:none;
    display:inline-block;
    margin-top:40px;
    transition:.25s ease;
}

.btn-voltar:hover {
    background:#ffe88a;
}
</style>
</head>

<body>

<h1>Agenda dos Barbeiros</h1>

<div class="filtros">
    <a href="?dia=ontem"  class="<?= $filtro=='ontem'?'active':'' ?>">Ontem</a>
    <a href="?dia=hoje"   class="<?= $filtro=='hoje'?'active':'' ?>">Hoje</a>
    <a href="?dia=amanha" class="<?= $filtro=='amanha'?'active':'' ?>">Amanhã</a>
</div>

<div class="grid">

<?php foreach ($barbeiros as $b): ?>

<?php
    $expandirCard = ($barbeiroExpandido !== null && $barbeiroExpandido === intval($b["id"]));

    $agendamentos = buscarAgendamentos(
        $conn,
        $b["id"],
        $expandirCard,
        $dataFiltro
    );

    $titulo = $expandirCard 
        ? "Todos os Agendamentos de $nomeDia" 
        : "Agendamentos de $nomeDia";
?>
    <div class="card-barbeiro">

        <img src="<?= $b["foto"] ? "../assets/barbeiros/{$b["foto"]}" : "../assets/img/user-default.png" ?>">

        <div class="nome"><?= htmlspecialchars($b["nome"]) ?></div>
        <div class="cargo"><?= htmlspecialchars($b["cargo"]) ?></div>

        <div class="titulo-agenda"><?= $titulo ?></div>

        <?php foreach ($agendamentos as $a): ?>
        <div class="agendamento">
            <strong><?= substr($a["hora_agendamento"], 0, 5) ?></strong> — 
            <?= htmlspecialchars($a["cliente"]) ?><br>

            <small style="color:#ccc">
                <?= htmlspecialchars($a["servico"]) ?>
            </small><br>

            <small style="color:#999; font-size: 13px;">
                📅 <?= date("d/m/Y", strtotime($a["data_agendamento"])) ?>
            </small><br>

            <span class="status <?= $a["status"] ?>">
                <?= ucfirst($a["status"]) ?>
            </span><br><br>

            <?php if ($a["status"] != "cancelado" && $a["status"] != "atendido"): ?>
                <a class="btn-card btn-concluir" href="marcar_atendido.php?id=<?= $a['id'] ?>">Concluir</a>
                <a class="btn-card btn-cancelar" href="cancelar.php?id=<?= $a['id'] ?>">Cancelar</a>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <?php if (!$expandirCard): ?>
            <a class="btn-ver"
                href="agenda.php?dia=<?= $filtro ?>&expandir=<?= $b['id'] ?>">
                Ver Todos
            </a>
        <?php else: ?>
            <a class="btn-ver"
                href="agenda.php?dia=<?= $filtro ?>">
                Ver Menos
            </a>
        <?php endif; ?>

    </div>

<?php endforeach; ?>

</div>

<div style="width:100%; text-align:center; margin-top:45px;">
    <a href="painel.php" class="btn-voltar">Voltar ao Painel</a>
</div>

</body>
</html>
