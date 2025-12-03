<?php
include 'header.php';
require_once '../config/db.php';

$conn = Database::connect();

$sql = "SELECT id, nome, preco, duracao_min, img FROM servicos ORDER BY nome ASC";
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
.container {
    max-width: 1300px;
}
h1 {
    font-weight: 700;
    letter-spacing: 1px;
}
.card-servico {
    background: #171717;
    border: 1px solid #2d2d2d;
    border-radius: 16px;
    transition: .3s ease-in-out;
    padding: 20px;
    box-shadow: 0 0 18px rgba(255,215,0,0.10);
    display: flex;
    flex-direction: column;
}
.card-servico:hover {
    transform: translateY(-6px);
    box-shadow: 0 0 35px rgba(255,215,0,0.22);
}
.img-box {
    width: 100%;
    height: 190px;
    border-radius: 12px;
    overflow: hidden;
    background: #2d2d2d;
    margin-bottom: 12px;
    border: 2px solid rgba(255,215,0,0.35);
}
.img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.btn-lv {
    background: #ffda44;
    border: none;
    color: #000;
    font-weight: 700;
    border-radius: 10px;
    padding: 10px 20px;
    transition: .25s ease;
}
.btn-lv:hover {
    background: #ffe88a;
    transform: translateY(-2px);
}
.btn-mini {
    padding: 7px 14px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
}
.btn-outline-light {
    border: 1px solid #ccc !important;
    transition: .25s;
}
.btn-outline-light:hover {
    background: #fff !important;
    color: #000 !important;
}
.btn-outline-danger {
    border: 1px solid #ff5c5c !important;
    color: #ff5c5c !important;
}
.btn-outline-danger:hover {
    background: #ff5c5c !important;
    color: #000 !important;
}
h4 {
    font-weight: 600;
    color: #ffda44;
}
.text-secondary {
    color: #bfbfbf !important;
}
</style>

<div class="container py-5">

    <h1 class="text-center mb-4" style="color:#ffda44;">Gerenciar Serviços</h1>

    <div class="text-end mb-4">
        <a href="servicos_cadastrar.php" class="btn btn-lv">Cadastrar Novo Serviço</a>
    </div>

    <div class="row g-4">

        <?php foreach ($servicos as $s): ?>
            <div class="col-md-4">
                <div class="card-servico h-100">

                    <div class="img-box">
                        <?php if (!empty($s['img'])): ?>
                            <img
                                src="../assets/servicos/<?= htmlspecialchars($s['img'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="Imagem do serviço <?= htmlspecialchars($s['nome'], ENT_QUOTES, 'UTF-8') ?>">
                        <?php else: ?>
                            <img
                                src="../assets/img/sem-foto.png"
                                alt="Serviço sem imagem">
                        <?php endif; ?>
                    </div>

                    <h4><?= htmlspecialchars($s['nome'], ENT_QUOTES, 'UTF-8') ?></h4>

                    <p class="text-secondary mb-1">
                        <strong>Preço:</strong>
                        R$ <?= number_format((float) $s['preco'], 2, ',', '.') ?>
                    </p>

                    <p class="text-secondary">
                        <strong>Duração:</strong>
                        <?= (int) $s['duracao_min'] ?> min
                    </p>

                    <div class="d-flex justify-content-between mt-auto pt-3">
                        <a
                            href="servicos_editar.php?id=<?= (int) $s['id'] ?>"
                            class="btn btn-outline-light btn-mini">
                            Editar
                        </a>

                        <a
                            href="servicos_excluir.php?id=<?= (int) $s['id'] ?>"
                            class="btn btn-outline-danger btn-mini"
                            onclick="return confirm('Tem certeza que deseja excluir este serviço?');">
                            Excluir
                        </a>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>

        <?php if (count($servicos) === 0): ?>
            <p class="text-center text-secondary mt-5">Nenhum serviço cadastrado.</p>
        <?php endif; ?>

    </div>

</div>

<?php include 'footer.php'; ?>
