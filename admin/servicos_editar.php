<?php
include 'header.php';
require_once '../config/db.php';

// Verificar ID
if (!isset($_GET['id'])) {
    header("Location: servicos_listar.php");
    exit;
}

$id = intval($_GET['id']);

// Buscar serviço
$sql = "SELECT * FROM servicos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$servico = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servico) {
    echo "<p class='text-center text-danger mt-5'>Serviço não encontrado.</p>";
    exit;
}

// Diretório de uploads
$upload_dir = "../assets/servicos/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Atualizar serviço
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = $_POST["nome"];
    $preco = $_POST["preco"];
    $duracao = $_POST["duracao"];
    $duracao_min = $_POST["duracao_min"];
    $ativo = isset($_POST["ativo"]) ? 1 : 0;

    // Manter imagem antiga
    $img_name = $servico['img'];

    // Se enviar nova imagem
    if (!empty($_FILES["img"]["name"])) {
        $ext = pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);
        $img_name = time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES["img"]["tmp_name"], $upload_dir . $img_name);
    }

    // Atualizar banco
    $sql = "UPDATE servicos 
            SET nome=?, preco=?, duracao=?, duracao_min=?, img=?, ativo=? 
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $nome, $preco, $duracao, $duracao_min, $img_name, $ativo, $id
    ]);

    header("Location: servicos_listar.php?editado=1");
    exit;
}
?>

<style>
body { background:#0d0d0d; color:#fff; }

.form-control, .form-select {
    background:#111;
    border:1px solid #333;
    color:#fff;
}

.form-control:focus, .form-select:focus {
    border-color:#f1c40f;
    box-shadow:0 0 0 0.2rem rgba(241,196,15,.25);
}

.btn-lv {
    background:#f1c40f;
    border:none;
    color:#000;
    font-weight:600;
    padding:10px;
    border-radius:8px;
}

.btn-lv:hover {
    background:#ffdd57;
}

.card-form {
    background:#1a1a1a;
    border:1px solid #2a2a2a;
    border-radius:14px;
    padding:30px;
}
</style>

<div class="container py-5">

    <h1 class="text-center mb-4" style="color:#f1c40f;">Editar Serviço</h1>

    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card-form">

                <form method="POST" enctype="multipart/form-data">

                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control mb-3"
                        value="<?= htmlspecialchars($servico['nome']) ?>" required>

                    <label class="form-label">Preço (R$)</label>
                    <input type="number" step="0.01" name="preco" class="form-control mb-3"
                        value="<?= $servico['preco'] ?>" required>

                    <label class="form-label">Duração (min)</label>
                    <input type="number" name="duracao" class="form-control mb-3"
                        value="<?= $servico['duracao'] ?>" required>

                    <label class="form-label">Duração média (min)</label>
                    <input type="number" name="duracao_min" class="form-control mb-3"
                        value="<?= $servico['duracao_min'] ?>" required>

                    <label class="form-label">Imagem atual</label><br>

                    <?php if (!empty($servico['img'])): ?>
                        <img src="../assets/servicos/<?= $servico['img'] ?>" 
                             width="140" class="rounded mb-3">
                    <?php else: ?>
                        <p class="text-secondary">Nenhuma imagem cadastrada</p>
                    <?php endif; ?>

                    <label class="form-label">Nova imagem (opcional)</label>
                    <input type="file" name="img" class="form-control mb-3">

                    <label class="form-label">Ativo</label><br>
                    <input type="checkbox" name="ativo" <?= $servico['ativo'] ? 'checked' : '' ?>>
                    <span>Serviço disponível</span>

                    <button type="submit" class="btn-lv w-100 mt-4">
                        Salvar Alterações
                    </button>
                </form>

            </div>

        </div>
    </div>

    <div class="text-center mt-4">
        <a href="servicos_listar.php" class="btn btn-outline-light">Voltar</a>
    </div>

</div>

<?php include 'footer.php'; ?>
