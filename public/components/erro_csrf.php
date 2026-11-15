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

$caminhoCssFisico = realpath(__DIR__ . '/../../Styles/style.css');
$versaoCss = $caminhoCssFisico ? filemtime($caminhoCssFisico) : '2026';
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
    <link rel="stylesheet" href="../Styles/style.css?v=<?= htmlspecialchars((string) $versaoCss, ENT_QUOTES, 'UTF-8') ?>">
</head>

<body class="ops-csrf-error-body">

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
