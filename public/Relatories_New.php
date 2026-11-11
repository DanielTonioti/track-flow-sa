<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';

$pageTitle = 'Novo Relatório';
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

    <main class="ops-shell" style="max-width: 780px;">
        <div class="ops-page-header">
            <div>
                <nav class="ops-breadcrumb" aria-label="Breadcrumb">
                    <a href="hub.php">Dashboard</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <a href="Relatories.php">Relatórios</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <span>Novo</span>
                </nav>
                <h1 class="ops-page-title">Novo Relatório</h1>
                <p class="ops-page-subtitle">
                    Emissão de relatórios por sensor, trem ou período.
                </p>
            </div>

            <div class="ops-header-actions">
                <a href="Relatories.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Relatórios
                </a>
            </div>
        </div>

        <div class="ops-alert ops-alert-info" role="status">
            <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
            <div>
                <strong>Em produção:</strong> a geração de novos relatórios está em produção e estará disponível em breve.
            </div>
        </div>

        <section class="ops-panel">
            <form action="Relatories.php" method="GET">
                <div class="ops-panel-body">
                    <div class="ops-form-section-title">
                        <i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i> Parâmetros do Relatório
                    </div>

                    <div class="mb-3">
                        <label class="ops-label" for="TituloRelatorio">Título</label>
                        <input class="ops-input" type="text" id="TituloRelatorio" placeholder="Ex.: Relatório semanal da linha">
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="ops-label" for="SensorRelatorio">Sensor ou rota</label>
                            <select class="ops-select" id="SensorRelatorio">
                                <option selected>Selecione...</option>
                                <option>Trens monitorados</option>
                                <option>Sensores de via</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="ops-label" for="PeriodoRelatorio">Período</label>
                            <select class="ops-select" id="PeriodoRelatorio">
                                <option selected>Últimos 7 dias</option>
                                <option>Últimos 30 dias</option>
                                <option>Período personalizado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="ops-panel-footer">
                    <a class="ops-btn ops-btn-ghost" href="Relatories.php">Cancelar</a>
                    <button class="ops-btn ops-btn-primary" type="button" disabled title="Funcionalidade em produção">
                        <i class="bi bi-file-earmark-check" aria-hidden="true"></i>Gerar Relatório
                    </button>
                </div>
            </form>
        </section>
    </main>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script defer src="../scripts/script_navbar.js"></script>
</body>

</html>
