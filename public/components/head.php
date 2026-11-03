<?php

$tituloPagina = isset($pageTitle) && trim((string) $pageTitle) !== ''
    ? trim((string) $pageTitle) . ' — TrackFlow'
    : 'TrackFlow — Monitoramento Ferroviário';

$descricaoPagina = isset($pageDescription) && trim((string) $pageDescription) !== ''
    ? trim((string) $pageDescription)
    : 'TrackFlow — Sistema de monitoramento ferroviário, telemetria de sensores, gestão de frota, rotas e equipe operacional.';

$caminhoCssFisico = realpath(__DIR__ . '/../../Styles/style.css');
$versaoCss = $caminhoCssFisico ? filemtime($caminhoCssFisico) : '2026';
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($descricaoPagina, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="theme-color" content="#060d17">
    <link rel="shortcut icon" href="../assets/logo/icone.ico" type="image/x-icon">
    <title><?= htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8') ?></title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=League+Spartan:wght@700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.8 & Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Estilos Globais TrackFlow -->
    <link rel="stylesheet" href="../Styles/style.css?v=<?= htmlspecialchars((string) $versaoCss, ENT_QUOTES, 'UTF-8') ?>">
