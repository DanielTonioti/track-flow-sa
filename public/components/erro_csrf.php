
<?php
http_response_code(403);

$paginaAnterior = $_SERVER['HTTP_REFERER'] ?? '';
$urlVoltar = 'hub.php';

// Permite voltar somente para páginas do mesmo domínio.
if ($paginaAnterior) {
    $hostAnterior = parse_url($paginaAnterior, PHP_URL_HOST);
    $hostAtual = $_SERVER['HTTP_HOST'] ?? '';

    if (
        $hostAnterior &&
        $hostAtual &&
        strcasecmp($hostAnterior, $hostAtual) === 0
    ) {
        $urlVoltar = $paginaAnterior;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Acesso bloqueado | Track Flow SA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background:
                radial-gradient(circle at top, #172c49, transparent 55%),
                linear-gradient(145deg, #07111f, #101d30);

            font-family: 'Inter', sans-serif;
            color: #fff;
        }

        .container-erro {
            width: 100%;
            max-width: 540px;
            padding: 45px 35px;
            text-align: center;

            background: rgba(15, 27, 44, 0.95);
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 22px;

            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
        }

        .icone-erro {
            width: 85px;
            height: 85px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0 auto 25px;

            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 24px;

            color: #f87171;
            font-size: 40px;
        }

        .codigo-erro {
            color: #f87171;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        h1 {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        h2 {
            color: #cbd5e1;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .descricao {
            color: #94a3b8;
            font-size: 14px;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .aviso {
            display: flex;
            align-items: flex-start;
            gap: 12px;

            padding: 17px;
            margin-bottom: 25px;

            background: rgba(37, 99, 235, 0.12);
            border: 1px solid rgba(96, 165, 250, 0.2);
            border-radius: 13px;

            text-align: left;
        }

        .aviso i {
            color: #60a5fa;
            font-size: 20px;
        }

        .aviso strong {
            display: block;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .aviso span {
            color: #94a3b8;
            font-size: 12px;
            line-height: 1.6;
        }

        .botoes {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .botao {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;

            padding: 14px;
            border-radius: 11px;

            background: #2563eb;
            color: white;

            font-size: 14px;
            font-weight: 700;
            text-decoration: none;

            transition: .2s;
        }

        .botao:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        .botao-secundario {
            background: transparent;
            border: 1px solid rgba(148, 163, 184, 0.2);
            color: #cbd5e1;
        }

        .botao-secundario:hover {
            background: rgba(148, 163, 184, 0.1);
        }

        .rodape {
            margin-top: 30px;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 3px;
        }

        @media(max-width: 480px) {
            .container-erro {
                padding: 35px 22px;
            }

            h1 {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

    <main class="container-erro">

        <div class="icone-erro">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <p class="codigo-erro">
            Proteção do sistema · Erro 403
        </p>

        <h1>Acesso bloqueado!</h1>

        <h2>Falha na validação de segurança</h2>

        <p class="descricao">
            Não foi possível validar sua solicitação.
            Por segurança, esta ação foi interrompida
            para proteger os dados do sistema.
        </p>



        <p class="rodape">
            TRACK FLOW SA · SEGURANÇA DO SISTEMA
        </p>

    </main>

</body>
</html>

<?php exit; ?>
