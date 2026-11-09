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

    <!-- Critical CSS Inline para LCP imediato (< 300ms mesmo em 3G) -->
    <style id="tf-critical-css">
    :root{color-scheme:light dark;--Fonte-padrao:"Inter",system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;--Fonte-titulo:"Space Grotesk",system-ui,-apple-system,"Segoe UI",sans-serif;--Fonte-mono:"JetBrains Mono",ui-monospace,Consolas,monospace;--ops-ground:#081220;--ops-surface:#0d1b2e;--ops-surface-elevated:#162a42;--ops-surface-active:#1e2f47;--ops-border:rgba(255,255,255,0.10);--ops-border-strong:rgba(82,113,255,0.30);--ops-text-main:#f4f7fa;--ops-text-secondary:#b8c7d9;--ops-text-muted:#7c91a6;--ops-accent:#5271ff;--ops-radius:8px;--ops-radius-sm:6px;--ops-radius-md:8px}
    html[data-theme="light"]{color-scheme:light;background-color:#f1f5f9!important;--ops-ground:#f1f5f9;--ops-surface:#ffffff;--ops-surface-elevated:#f8fafc;--ops-surface-active:#e2e8f0;--ops-border:rgba(15,23,42,0.12);--ops-border-strong:rgba(82,113,255,0.35);--ops-text-main:#0f172a;--ops-text-secondary:#334155;--ops-text-muted:#64748b}
    *,*::before,*::after{box-sizing:border-box;font-family:var(--Fonte-padrao)}
    html,body{margin:0;padding:0;min-height:100vh;background-color:var(--ops-ground)!important;color:var(--ops-text-main)!important;-webkit-font-smoothing:antialiased;overflow-x:hidden}
    .d-none{display:none!important}.d-block{display:block!important}.d-flex{display:flex!important}.d-inline-flex{display:inline-flex!important}.flex-column{flex-direction:column!important}.flex-wrap{flex-wrap:wrap!important}.align-items-center{align-items:center!important}.align-items-baseline{align-items:baseline!important}.justify-content-between{justify-content:space-between!important}.gap-1{gap:.25rem!important}.gap-2{gap:.5rem!important}.gap-3{gap:1rem!important}.me-1{margin-right:.25rem!important}.me-2{margin-right:.5rem!important}.mb-0{margin-bottom:0!important}.mb-1{margin-bottom:.25rem!important}.mb-2{margin-bottom:.5rem!important}.mb-3{margin-bottom:1rem!important}.p-0{padding:0!important}.p-3{padding:1rem!important}.px-2{padding-left:.5rem!important;padding-right:.5rem!important}.px-3{padding-left:1rem!important;padding-right:1rem!important}.py-2{padding-top:.5rem!important;padding-bottom:.5rem!important}.py-3{padding-top:1rem!important;padding-bottom:1rem!important}.w-100{width:100%!important}.h-100{height:100%!important}.text-end{text-align:right!important}.text-white{color:#fff!important}.text-steel{color:#b8c7d9!important}.text-volt{color:#c1ff72!important}.text-cyan{color:#67a4ff!important}.small{font-size:.875em}.fw-bold{font-weight:700!important}.fw-semibold{font-weight:600!important}.fs-4{font-size:1.25rem!important}.lh-1{line-height:1!important}.lh-sm{line-height:1.25!important}.position-relative{position:relative!important}.container-fluid{width:100%;padding-right:.75rem;padding-left:.75rem;margin-right:auto;margin-left:auto}.row{display:flex;flex-wrap:wrap;margin-right:-.375rem;margin-left:-.375rem}.col-12{flex:0 0 auto;width:100%;padding-right:.375rem;padding-left:.375rem}.table-responsive{overflow-x:auto;-webkit-overflow-scrolling:touch}
    @media(min-width:768px){.d-md-flex{display:flex!important}.d-md-none{display:none!important}}
    @media(min-width:576px){.d-sm-inline{display:inline!important}}
    @media(min-width:992px){.col-lg-6{flex:0 0 auto;width:50%}}
    .offcanvas{position:fixed;bottom:0;z-index:1045;display:flex;flex-direction:column;max-width:100%;visibility:hidden;background-color:#0d1b2e;background-clip:padding-box;outline:0;top:0;left:0;width:320px;transform:translateX(-100%)}.offcanvas.show:not(.hiding),.offcanvas.showing{transform:none;visibility:visible}.modal{display:none;position:fixed;top:0;left:0;z-index:1055;width:100%;height:100%;overflow-x:hidden;overflow-y:auto;outline:0}
    .menu-nav{position:sticky;top:0;z-index:1030;background-color:var(--ops-surface);border-bottom:1px solid var(--ops-border);padding:.55rem .75rem;display:flex;align-items:center;justify-content:space-between}
    .menu-nav>.container-fluid{display:flex;align-items:center;justify-content:space-between;width:100%}
    .navbar-brand{text-decoration:none}
    .ops-brand-mark{width:38px;height:38px;border-radius:8px;background-color:#0d1b2e;border:1px solid rgba(82,113,255,.45);display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;position:relative}
    .hub-icon{max-height:2.3rem;width:auto;filter:brightness(0) invert(1)}
    .ops-nav-toggler{width:42px;height:42px;border:1px solid var(--ops-border-strong);border-radius:8px;background-color:var(--ops-surface);color:var(--ops-text-main);display:inline-flex;align-items:center;justify-content:center;padding:0}
    .menu-nav .perfil{background:transparent;border:0;padding:2px;border-radius:50%;display:inline-flex;cursor:pointer}
    .menu-nav .perfil .imgCx{position:relative;width:40px;height:40px;background:#112238;border:1px solid rgba(82,113,255,.4);border-radius:50%;overflow:hidden;display:block}
    .menu-nav .imgCx img{position:absolute;width:100%;height:100%;top:0;left:0;object-fit:cover}
    .menu-nav .menu{position:absolute;right:0;top:115%;width:12rem;z-index:1040;background:var(--ops-surface);border:1px solid var(--ops-border);border-radius:12px;opacity:0;visibility:hidden}
    .ops-shell{width:100%;max-width:1440px;margin:0 auto;padding:1.25rem 1rem 2.5rem}
    @media(min-width:992px){.ops-shell{padding:1.5rem 1.75rem 3rem}}
    .ops-page-header{display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:1rem;padding-bottom:1rem;margin-bottom:1.25rem;border-bottom:1px solid var(--ops-border)}
    .ops-breadcrumb{display:flex;flex-wrap:wrap;align-items:center;gap:.4rem;font-size:.8rem;color:var(--ops-text-muted);margin-bottom:.35rem}
    .ops-breadcrumb a{color:var(--ops-text-secondary);text-decoration:none}
    .ops-page-title{font-family:var(--Fonte-titulo);font-size:clamp(1.5rem,1.2rem + 1vw,2.1rem);font-weight:700;color:var(--ops-text-main);letter-spacing:-.01em;line-height:1.15;margin:0}
    .ops-page-subtitle{color:var(--ops-text-secondary);font-size:.9rem;margin:.25rem 0 0}
    .ops-header-actions{display:flex;flex-wrap:wrap;align-items:center;gap:.55rem}
    .ops-panel{background-color:var(--ops-surface);border:1px solid var(--ops-border);border-radius:8px}
    .ops-panel-header,.ops-toolbar{padding:.75rem 1.1rem;border-bottom:1px solid var(--ops-border);display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.65rem}
    .ops-panel-title{font-family:var(--Fonte-titulo);font-size:1.1rem;font-weight:600;color:var(--ops-text-main);margin:0}
    .ops-panel-body{padding:1.1rem}
    .ops-btn{min-height:40px;display:inline-flex;align-items:center;justify-content:center;gap:.42rem;padding:.45rem .95rem;font-size:.85rem;font-weight:600;border-radius:6px;border:1px solid var(--ops-border-strong);background-color:var(--ops-surface-elevated);color:var(--ops-text-main);text-decoration:none;cursor:pointer;white-space:nowrap}
    .ops-btn-sm{min-height:32px;padding:.25rem .65rem;font-size:.78rem}
    .ops-btn-primary{background-color:#5271ff!important;border-color:#5271ff!important;color:#fff!important}
    .ops-btn-success{background-color:#00bf63!important;border-color:#00bf63!important;color:#fff!important}
    .ops-btn-ghost{background-color:transparent;border-color:var(--ops-border);color:var(--ops-text-secondary)}
    .ops-table{width:100%;border-collapse:separate;border-spacing:0;font-size:.86rem}
    .ops-table th,.ops-table td{padding:.75rem 1rem;text-align:left;vertical-align:middle}
    html[data-theme="light"] .text-white:not(.ops-btn){color:#0f172a!important}
    html[data-theme="light"] .text-steel{color:#475569!important}
    html[data-theme="light"] .text-volt{color:#009e52!important}
    html[data-theme="light"] .text-cyan{color:#2563eb!important}
    </style>

    <!-- Folhas de estilo carregadas sem bloquear a renderização (LCP) em redes 3G -->
    <link rel="preload" href="../Styles/style.css?v=<?= htmlspecialchars((string) $versaoCss, ENT_QUOTES, 'UTF-8') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"
        as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        as="style" onload="this.onload=null;this.rel='stylesheet'">

    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="../Styles/style.css?v=<?= htmlspecialchars((string) $versaoCss, ENT_QUOTES, 'UTF-8') ?>">
    </noscript>
