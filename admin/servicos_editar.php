<?php
include 'header.php';
require_once '../config/db.php';

$conn = Database::connect();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    header("Location: servicos_listar.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM servicos WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$servico = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servico) {
    echo "<p class='text-center text-danger mt-5'>Serviço não encontrado.</p>";
    include 'footer.php';
    exit;
}

$upload_dir = "../assets/servicos/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome        = trim($_POST["nome"] ?? "");
    $preco       = isset($_POST["preco"]) ? (float) $_POST["preco"] : 0;
    $duracao     = isset($_POST["duracao"]) ? (int) $_POST["duracao"] : 0;
    $duracao_min = isset($_POST["duracao_min"]) ? (int) $_POST["duracao_min"] : 0;
    $ativo       = isset($_POST["ativo"]) ? 1 : 0;

    if ($nome === "" || $preco <= 0 || $duracao <= 0 || $duracao_min <= 0) {
        header("Location: servicos_editar.php?id={$id}&erro=1");
        exit;
    }

    $img_name = $servico['img'];

    if (!empty($_FILES["img"]["name"]) && is_uploaded_file($_FILES["img"]["tmp_name"])) {

        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        $mime    = mime_content_type($_FILES["img"]["tmp_name"]);

        if (in_array($mime, $allowed, true)) {
            $ext = strtolower(pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION));
            $new_name = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
            $destino  = $upload_dir . $new_name;

            if (move_uploaded_file($_FILES["img"]["tmp_name"], $destino)) {
                if (!empty($img_name)) {
                    $old_path = $upload_dir . $img_name;
                    if (is_file($old_path) && file_exists($old_path)) {
                        @unlink($old_path);
                    }
                }
                $img_name = $new_name;
            }
        }
    }

    $sql = "
        UPDATE servicos 
           SET nome = :nome,
               preco = :preco,
               duracao = :duracao,
               duracao_min = :duracao_min,
               img = :img,
               ativo = :ativo
         WHERE id = :id
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nome'        => $nome,
        ':preco'       => $preco,
        ':duracao'     => $duracao,
        ':duracao_min' => $duracao_min,
        ':img'         => $img_name,
        ':ativo'       => $ativo,
        ':id'          => $id,
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
                    <input
                        type="text"
                        name="nome"
                        class="form-control mb-3"
                        value="<?= htmlspecialchars($servico['nome'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                    <label class="form-label">Preço (R$)</label>
                    <input
                        type="number"
                        step="0.01"
                        name="preco"
                        class="form-control mb-3"
                        value="<?= htmlspecialchars($servico['preco'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                    <label class="form-label">Duração (min)</label>
                    <input
                        type="number"
                        name="duracao"
                        class="form-control mb-3"
                        value="<?= (int) $servico['duracao'] ?>"
                        required
                    >

                    <label class="form-label">Duração média (min)</label>
                    <input
                        type="number"
                        name="duracao_min"
                        class="form-control mb-3"
                        value="<?= (int) $servico['duracao_min'] ?>"
                        required
                    >

                    <label class="form-label">Imagem atual</label><br>

                    <?php if (!empty($servico['img'])): ?>
                        <img
                            src="../assets/servicos/<?= htmlspecialchars($servico['img'], ENT_QUOTES, 'UTF-8') ?>"
                            width="140"
                            class="rounded mb-3"
                            alt="Imagem do serviço"
                        >
                    <?php else: ?>
                        <p class="text-secondary">Nenhuma imagem cadastrada</p>
                    <?php endif; ?>

                    <label class="form-label">Nova imagem (opcional)</label>
                    <input type="file" name="img" class="form-control mb-3" accept="image/*">

                    <label class="form-label">Ativo</label><br>
                    <input
                        type="checkbox"
                        name="ativo"
                        <?= !empty($servico['ativo']) ? 'checked' : '' ?>
                    >
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
