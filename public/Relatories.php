<?php
session_start();
require_once 'components/start.php';

$pageTitle = 'Relatórios';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">

<head>
    <?php include 'components/head.php'; ?>
</head>

<body class="ops-app-body">
    <header>
        <?php include("components/navbar.php"); ?>
    </header>

    <main class="ops-shell ops-shell-md">
        <div class="ops-page-header">
            <div>
                <nav class="ops-breadcrumb" aria-label="Breadcrumb">
                    <a href="hub.php">Dashboard</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <span>Relatórios</span>
                </nav>
                <h1 class="ops-page-title">Relatórios</h1>
            </div>

            <div class="ops-header-actions">
                <a href="hub.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Dashboard
                </a>
            </div>
        </div>

        <section class="ops-panel" aria-labelledby="titulo-relatorios-dev">
            <div class="ops-empty-state">
                <span class="ops-empty-icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
                <h2 id="titulo-relatorios-dev" class="h5 mb-2 fw-semibold text-white">Página em desenvolvimento.</h2>
                <p class="small text-steel mb-3">O módulo de relatórios está em desenvolvimento e estará disponível em breve.</p>
                <a href="hub.php" class="ops-btn ops-btn-ghost ops-btn-sm">Voltar para o Dashboard</a>
            </div>
        </section>
    </main>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script defer src="../scripts/script_navbar.js"></script>
</body>

</html>
