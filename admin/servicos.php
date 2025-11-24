<?php
session_start();

if (!isset($_SESSION["cliente_id"])) {
    header("Location: ../views/login.php");
    exit;
}

$header_exists = file_exists('header.php');
if ($header_exists) {
    include 'header.php';
}

require_once '../config/db.php';

$sql = "SELECT * FROM servicos WHERE ativo = 1 ORDER BY nome ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
body {
    background: #0e0e0e;
    color: #ffffff;
    font-family: "Inter", sans-serif;
}

/* ---------------- HEADER ---------------- */
.page-title {
    text-align: center;
    margin-top: 40px;
    font-size: 40px;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: 1px;
}

.page-subtitle {
    text-align: center;
    color: #bfbfbf;
    font-size: 16px;
    margin-top: -5px;
    margin-bottom: 50px;
}

/* ---------------- LOGO ---------------- */
.logo-box {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    overflow: hidden;
    margin: 25px auto 10px;
    border: 2px solid rgba(255,215,0,0.6);
    box-shadow: 0 0 16px rgba(255,215,0,0.35);
}
.logo-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ---------------- CARDS ---------------- */
.servico-card {
    background: #1c1c1c;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #2d2d2d;
    transition: all .3s ease;
}

.servico-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 0 30px rgba(255,215,0,0.25);
}

/* Imagem */
.servico-img,
.img-placeholder {
    width: 100%;
    height: 190px;
    object-fit: cover;
}

.img-placeholder {
    background: #2d2d2d;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffda44;
    font-size: 14px;
}

/* Conteúdo do Card */
.servico-card h4 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 5px;
}

.servico-card p {
    color: #bfbfbf;
}

.servico-card .preco {
    color: #ffda44;
    font-size: 20px;
    font-weight: 700;
}

/* Botão */
.btn-agendar {
    width: 100%;
    background: #ffda44;
    border: none;
    padding: 12px;
    font-weight: 700;
    color: #000;
    border-radius: 8px;
    margin-top: 10px;
    transition: .25s ease;
}

.btn-agendar:hover {
    background: #ffe88a;
    transform: translateY(-2px);
}

/* Botão voltar */
.btn-voltar {
    color: #fff;
    border: 1px solid #fff;
    padding: 10px 22px;
    border-radius: 6px;
    margin-top: 40px;
    transition: .25s ease;
}

.btn-voltar:hover {
    background: #fff;
    color: #000;
}
</style>

<div class="container">

    <div class="logo-box">
        <img src="../assets/img/logob.png.png" alt="Logo">
    </div>

    <h1 class="page-title">Nossos Serviços</h1>
    <p class="page-subtitle">Experimente qualidade, tradição e profissionalismo.</p>

    <div class="row g-4">

        <?php if (count($servicos) > 0): ?>
            <?php foreach ($servicos as $s): ?>

                <div class="col-md-4">
                    <div class="servico-card">

                        <?php if (!empty($s['img'])): ?>
                            <img src="../assets/servicos/<?= $s['img'] ?>" class="servico-img">
                        <?php else: ?>
                            <div class="img-placeholder">Sem imagem</div>
                        <?php endif; ?>

                        <div class="p-3">
                            <h4><?= htmlspecialchars($s['nome']) ?></h4>

                            <p><?= $s['duracao_min'] ?> minutos</p>

                            <div class="preco">
                                R$ <?= number_format($s['preco'], 2, ',', '.') ?>
                            </div>

                            <a href="../views/agendar.php?servico_id=<?= $s['id'] ?>">
                                <button class="btn-agendar">Agendar Agora</button>
                            </a>
                        </div>

                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>

            <div class="col-12 text-center mt-5">
                Nenhum serviço disponível no momento.
            </div>

        <?php endif; ?>
    </div>

    <div class="text-center">
        <a href="../index.html" class="btn-voltar">Voltar</a>
    </div>

</div>

<?php if (file_exists('footer.php')) include 'footer.php'; ?>