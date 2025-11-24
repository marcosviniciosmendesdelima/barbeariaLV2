<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Barbearia LV2'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* HEADER PREMIUM */
        .lv-header {
            background: #111;
            border-bottom: 1px solid #2d2d2d;
            padding: 18px 0;
            box-shadow: 0 0 25px rgba(255,215,0,0.12);
        }

        .lv-header .brand-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        /* LOGO REDONDO */
        .lv-logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid rgba(255,215,0,0.45);
            box-shadow: 0 0 12px rgba(255,215,0,0.20);
            margin-bottom: 6px;
        }

        .lv-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* NOME DA BARBEARIA */
        .lv-title {
            color: #ffda44;
            font-weight: 700;
            font-size: 20px;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>

<nav class="lv-header">
    <div class="container d-flex justify-content-center">
        
        <div class="brand-box">
            
            <div class="lv-logo">
                <img src="../assets/img/logob.png.png" alt="Logo Barbearia LV2">
            </div>

            <div class="lv-title">
                Barbearia LV2
            </div>

        </div>

    </div>
</nav>
