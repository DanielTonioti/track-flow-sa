<?php
session_start();
require '../infra/conn.php';
require 'components/start.php';
redirecionarSeNaoAdmin();
$pageTitle = 'Detalhes do Relatório';
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

    <main class="ops-shell" style="max-width: 900px;">
        <div class="ops-page-header">
            <div>
                <nav class="ops-breadcrumb" aria-label="Breadcrumb">
                    <a href="hub.php">Dashboard</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <a href="Relatories.php">Relatórios</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <span>Detalhes</span>
                </nav>
                <h1 class="ops-page-title">Detalhes do Relatório</h1>
                <p class="ops-page-subtitle">
                    Exibição detalhada dos registros do relatório selecionado.
                </p>
            </div>

            <div class="ops-header-actions">
                <a href="Relatories.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Relatórios
                </a>
            </div>
        </div>

        <section class="ops-panel">
            <div class="ops-empty-state">
                <span class="ops-empty-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></span>
                <p class="mb-2 fw-semibold text-white">Página em produção.</p>
                <p class="small text-steel mb-3">A visualização detalhada de relatórios está em produção e estará disponível em breve.</p>
                <a href="Relatories.php" class="ops-btn ops-btn-ghost ops-btn-sm">Voltar para Relatórios</a>
            </div>
        </section>
    </main>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script defer src="../scripts/script_navbar.js"></script>
</body>

</html>
