<?php

$tituloPagina = isset($pageTitle) && trim((string) $pageTitle) !== ''
    ? trim((string) $pageTitle) . ' · TrackFlow'
    : 'TrackFlow · Monitoramento Ferroviário';

$descricaoPagina = isset($pageDescription) && trim((string) $pageDescription) !== ''
    ? trim((string) $pageDescription)
    : 'TrackFlow - Sistema de telemetria e monitoramento ferroviário.';

$caminhoCssFisico = realpath(__DIR__ . '/../../Styles/style.css');
$versaoCss = $caminhoCssFisico ? filemtime($caminhoCssFisico) : '2026';
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($descricaoPagina, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#081220">
    <link rel="icon" type="image/png" href="../assets/logo/logo-track-flow.png">
    <link rel="shortcut icon" href="../assets/logo/icone.ico" type="image/x-icon">
    <title><?= htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8') ?></title>

    <script>
    (function () {
        var root = document.documentElement;
        var meta = document.querySelector('meta[name="color-scheme"]');
        var storedTheme = null;
        try { storedTheme = localStorage.getItem('color-scheme'); } catch (_) {}
        var isLoginPage = /\/Login\.php$/i.test(location.pathname);
        var scheme = (!isLoginPage && (storedTheme === 'light' || storedTheme === 'dark' || storedTheme === 'light dark'))
            ? storedTheme
            : 'dark';
        if (meta) meta.content = scheme;
        var resolvedTheme = scheme === 'light dark'
            ? (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark')
            : scheme;
        root.setAttribute('data-theme', resolvedTheme);

        // Detecção automática e silenciosa de hardware (PC fraco) e rede (2G/3G/Save-Data)
        var conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
        var effType = conn && conn.effectiveType ? String(conn.effectiveType) : '';
        var slowNet = Boolean(
            (conn && conn.saveData) ||
            effType === 'slow-2g' ||
            effType === '2g' ||
            effType === '3g' ||
            (conn && typeof conn.downlink === 'number' && conn.downlink > 0 && conn.downlink < 2.5) ||
            (conn && typeof conn.rtt === 'number' && conn.rtt > 200)
        );
        var cores = navigator.hardwareConcurrency || 4;
        var mem = navigator.deviceMemory || null;
        var reducedMotion = Boolean(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
        var weakDevice = reducedMotion || slowNet || cores <= 4 || (mem !== null && mem <= 4);
        root.setAttribute('data-perf', weakDevice ? 'low' : 'high');

        // Só carrega fontes externas se a rede e o computador forem fortes (após o carregamento inicial)
        if (!weakDevice) {
            window.addEventListener('load', function () {
                var gf = document.createElement('link');
                gf.rel = 'stylesheet';
                gf.href = 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap';
                document.head.appendChild(gf);
            }, { once: true });
        }
    })();
    </script>

    <!-- Folhas de estilo centralizadas -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../Styles/style.css?v=<?= htmlspecialchars((string) $versaoCss, ENT_QUOTES, 'UTF-8') ?>">
