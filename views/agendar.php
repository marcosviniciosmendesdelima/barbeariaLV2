<?php
session_start();
require_once "../config/db.php";

if (empty($_SESSION["cliente_id"])) {
    header("Location: ../views/login.php");
    exit;
}

$conn = Database::connect();
$servicoSelecionado = isset($_GET["servico_id"]) ? (int) $_GET["servico_id"] : 0;

if ($servicoSelecionado <= 0) {
    header("Location: ../admin/servicos.php");
    exit;
}

$stmt = $conn->prepare("
    SELECT id, nome, preco, duracao_min, img 
    FROM servicos 
    WHERE id = :id AND ativo = 1
    LIMIT 1
");
$stmt->bindValue(':id', $servicoSelecionado, PDO::PARAM_INT);
$stmt->execute();
$servicoAtual = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servicoAtual) {
    header("Location: ../admin/servicos.php");
    exit;
}

$clienteNome = $_SESSION["cliente_nome"] ?? "Cliente";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agendar Serviço - Barbearia LV2</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #0e0e0e;
            color: #ffffff;
            font-family: "Inter", sans-serif;
            padding-top: 50px;
        }

        .container-box {
            max-width: 700px;
            margin: auto;
            background: #171717;
            border-radius: 15px;
            padding: 35px;
            border: 1px solid #2d2d2d;
            box-shadow: 0 0 35px rgba(255, 215, 0, 0.18);
        }

        .title {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #ffda44;
        }

        .subtitle {
            text-align: center;
            color: #bfbfbf;
            margin-bottom: 25px;
            font-size: 15px;
        }

        .servico-card {
            background: #1c1c1c;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #2d2d2d;
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            transition: .25s ease;
        }

        .servico-card:hover {
            box-shadow: 0 0 25px rgba(255,215,0,0.18);
            transform: translateY(-3px);
        }

        .servico-card img {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            object-fit: cover;
            background: #2a2a2a;
        }

        label {
            font-weight: 600;
            margin-top: 10px;
        }

        select,
        input[type="date"] {
            background: #111;
            border-radius: 8px;
            border: 1px solid #333;
            color: #fff;
            padding: 12px;
            width: 100%;
            margin-bottom: 15px;
            transition: .2s;
        }

        select:focus,
        input[type="date"]:focus {
            border-color: #ffda44;
            box-shadow: 0 0 12px rgba(255,215,0,0.25);
            outline: none;
        }

        .btn-confirm {
            background: #ffda44;
            color: #000;
            font-weight: 700;
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            transition: .25s ease;
            border: none;
        }

        .btn-confirm:hover,
        .btn-confirm:focus-visible {
            background: #ffe88a;
            transform: translateY(-2px);
        }

        .btn-back {
            display: block;
            padding: 12px;
            text-align: center;
            border-radius: 10px;
            background: transparent;
            color: #fff;
            border: 1px solid #444;
            margin-top: 25px;
            transition: .25s ease;
            text-decoration: none;
        }

        .btn-back:hover,
        .btn-back:focus-visible {
            background: #fff;
            color: #000;
        }
    </style>

    <script>
        async function carregarHorarios() {
            const dataEl = document.getElementById("data");
            const servicoEl = document.getElementById("servico_id");
            const select = document.getElementById("hora");

            const data = dataEl.value;
            const servico = servicoEl.value;

            if (!data || !servico) {
                return;
            }

            try {
                const url = "../views/horarios_disponiveis.php?data=" 
                    + encodeURIComponent(data) 
                    + "&servico=" 
                    + encodeURIComponent(servico);

                const req = await fetch(url);
                const horarios = await req.json();

                select.innerHTML = "<option value=''>Selecione o horário</option>";

                if (!Array.isArray(horarios) || horarios.length === 0) {
                    select.innerHTML = "<option value=''>Nenhum horário disponível</option>";
                    return;
                }

                horarios.forEach(h => {
                    const opt = document.createElement("option");
                    opt.value = h;
                    opt.textContent = h;
                    select.appendChild(opt);
                });
            } catch (error) {
                console.error("Erro ao carregar horários:", error);
                select.innerHTML = "<option value=''>Erro ao carregar horários</option>";
            }
        }
    </script>
</head>

<body>

<div class="container container-box">

    <h2 class="title">Agendar Serviço</h2>
    <p class="subtitle">
        Cliente: <strong><?= htmlspecialchars($clienteNome) ?></strong>
    </p>

    <div class="servico-card">
        <img
            src="../assets/servicos/<?= htmlspecialchars($servicoAtual['img'] ?? 'padrao.jpg') ?>"
            alt="Serviço: <?= htmlspecialchars($servicoAtual['nome']) ?>"
            onerror="this.src='../assets/servicos/padrao.jpg'">
        <div>
            <h4 style="margin-bottom: 5px;">
                <?= htmlspecialchars($servicoAtual["nome"]) ?>
            </h4>
            <p class="text-secondary mb-1">
                <?= (int) $servicoAtual["duracao_min"] ?> min
            </p>
            <h5 style="color:#ffda44;">
                R$ <?= number_format((float) $servicoAtual["preco"], 2, ',', '.') ?>
            </h5>
        </div>
    </div>

    <form action="/BarbeariaLV2/views/barbeiros_cliente.php" method="GET">
        <input
            type="hidden"
            name="servico"
            id="servico_id"
            value="<?= (int) $servicoAtual["id"] ?>">

        <label for="data">Selecione a Data</label>
        <input
            type="date"
            name="data"
            id="data"
            onchange="carregarHorarios()"
            min="<?= date('Y-m-d'); ?>"
            required>

        <label for="hora">Selecione o Horário</label>
        <select name="hora" id="hora" required>
            <option value="">Escolha a data primeiro</option>
        </select>

        <button type="submit" class="btn-confirm mt-3">
            Escolher Barbeiro 
        </button>
    </form>

    <a href="../admin/servicos.php" class="btn-back">Voltar aos Serviços</a>

</div>

</body>
</html>
