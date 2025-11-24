<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cadastrar Novo Item - Painel LV2</title>

<!-- FONT -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
    body{
        margin:0;
        padding:40px;
        background:linear-gradient(-45deg,#000,#111,#1a1a1a,#000);
        background-size:400% 400%;
        animation:bgMove 14s ease infinite;
        font-family:'Poppins',sans-serif;
        color:white;
    }

    @keyframes bgMove{
        0%{background-position:0% 50%;}
        50%{background-position:100% 50%;}
        100%{background-position:0% 50%;}
    }

    .box{
        max-width:600px;
        margin:auto;
        background:rgba(255,255,255,0.06);
        padding:35px;
        border-radius:18px;
        backdrop-filter:blur(12px);
        border:1px solid rgba(255,255,255,0.15);
        box-shadow:0 0 25px rgba(255,193,7,.20);
        animation:fade .7s ease;
        opacity:0;
    }

    @keyframes fade{
        to{opacity:1;}
    }

    h2{
        color:#ffc400;
        margin-bottom:15px;
        text-align:center;
        font-size:26px;
        font-weight:600;
    }

    p{
        text-align:center;
        color:#ccc;
        margin-bottom:25px;
    }

    label{
        display:block;
        margin-bottom:6px;
        font-size:14px;
        color:#eee;
    }

    input, select{
        width:100%;
        padding:14px;
        border-radius:10px;
        border:1px solid rgba(255,255,255,0.25);
        background:rgba(255,255,255,0.12);
        color:#fff;
        margin-bottom:18px;
        font-size:15px;
        transition:.25s;
    }

    input:focus, select:focus{
        border-color:#ffc400;
        box-shadow:0 0 12px rgba(255,193,7,.35);
        outline:none;
    }

    .btn{
        width:100%;
        padding:14px;
        background:#ffc400;
        color:#000;
        border:none;
        border-radius:10px;
        cursor:pointer;
        font-weight:600;
        font-size:17px;
        transition:.25s;
    }

    .btn:hover{
        background:#ffde55;
        transform:translateY(-2px);
    }

    .back{
        display:block;
        text-align:center;
        margin-top:18px;
        color:#f1c40f;
        text-decoration:none;
        font-size:15px;
    }
    .back:hover{
        text-decoration:underline;
    }

</style>
</head>

<body>

<div class="box">
    <h2>Cadastrar Novo Item</h2>
    <p>Preencha os campos abaixo para adicionar um novo registro.</p>

    <!-- FORMULÁRIO BASE (edite conforme a necessidade da página) -->
    <form method="POST" action="#">

        <label>Nome do Item</label>
        <input type="text" name="nome" placeholder="Digite o nome...">

        <label>Descrição</label>
        <input type="text" name="descricao" placeholder="Opcional">

        <button class="btn">Salvar Registro</button>
    </form>

    <a class="back" href="painel.php">← Voltar</a>
</div>

</body>
</html>
