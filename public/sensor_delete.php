<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

$pageTitle = 'Exclusão de Sensores';
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

    <main class="ops-shell" style="max-width: 820px;">
        <div class="ops-page-header">
            <div>
                <nav class="ops-breadcrumb" aria-label="Breadcrumb">
                    <a href="hub.php">Dashboard</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <a href="Sensor_Data.php">Sensores</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <span>Exclusão</span>
                </nav>
                <h1 class="ops-page-title">Exclusão de Sensores</h1>
                <p class="ops-page-subtitle">
                    Remoção de sensores vinculados a trens e trilhos.
                </p>
            </div>

            <div class="ops-header-actions">
                <a href="Sensor_Manage.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Gerenciar Sensores
                </a>
            </div>
        </div>

        <section class="ops-panel">
            <div class="ops-empty-state">
                <span class="ops-empty-icon"><i class="bi bi-tools" aria-hidden="true"></i></span>
                <p class="mb-2 fw-semibold text-white">Página em produção.</p>
                <p class="small text-steel mb-3">Esta funcionalidade está em produção e estará disponível em breve.</p>
                <a href="Sensor_Manage.php" class="ops-btn ops-btn-primary ops-btn-sm">
                    Ir para Gerenciar Sensores
                </a>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
</body>

</html>
