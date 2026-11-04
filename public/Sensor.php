<?php
session_start();
require_once 'components/start.php';
redirecionarSeNaoAdmin();
include '../infra/conn.php';

$mensagemCadastro = '';
$mensagemErro = '';

$pageTitle = 'Cadastrar Sensor';
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
                    <span>Cadastro</span>
                </nav>
                <h1 class="ops-page-title">Cadastrar Sensor</h1>
                <p class="ops-page-subtitle">
                    Vinculação de sensores a trens ou segmentos de trilho.
                </p>
            </div>

            <div class="ops-header-actions">
                <a href="Sensor_Data.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Sensores
                </a>
                <a href="Sensor_Manage.php" class="ops-btn">
                    Gerenciar
                </a>
            </div>
        </div>

        <div class="ops-alert ops-alert-info" role="status">
            <div>
                <strong>Em produção:</strong> o cadastro de sensores está em produção e estará disponível em breve.
            </div>
        </div>

        <section class="ops-panel">
            <form id="CadastrarSensor" method="POST" action="">
                <div class="ops-panel-body">
                    <div class="ops-form-section-title">
                         Dados do Sensor
                    </div>

                    <div class="mb-3">
                        <span class="ops-label">Onde o sensor será instalado?</span>
                        <div class="ops-radio-grid">
                            <label class="ops-radio-card" for="trem">
                                <input type="radio" name="tipo" id="trem" value="trem" checked>
                                <div>
                                    <strong class="d-block text-white small">Trem</strong>
                                    <span class="text-steel" style="font-size: 0.76rem;">Vinculado a uma composição ferroviária.</span>
                                </div>
                            </label>
                            <label class="ops-radio-card" for="trilho">
                                <input type="radio" name="tipo" id="trilho" value="trilho">
                                <div>
                                    <strong class="d-block text-white small">Trilho</strong>
                                    <span class="text-steel" style="font-size: 0.76rem;">Vinculado a um trecho da linha.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="ops-label" for="tipo-dado">Tipo de dado</label>
                            <select class="ops-select" name="tipo_dado" id="tipo-dado">
                                <option value="Temperatura">Temperatura</option>
                                <option value="Velocidade">Velocidade</option>
                                <option value="Falha">Falha</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6" id="trem-vinculado-box">
                            <label class="ops-label" for="trem-vinculado">Trem vinculado</label>
                            <input class="ops-input" type="text" id="trem-vinculado" name="trem_vinculado" placeholder="Ex.: Locomotiva C-01">
                        </div>

                        <div class="col-12 col-md-6 d-none" id="localizacao-box">
                            <label class="ops-label" for="localizacao">Localização do trilho</label>
                            <input class="ops-input" type="text" id="localizacao" name="localizacao" placeholder="Ex.: Joinville - KM 12">
                        </div>
                    </div>
                </div>

                <div class="ops-panel-footer">
                    <a href="Sensor_Data.php" class="ops-btn ops-btn-ghost">Voltar</a>
                    <button type="button" class="ops-btn ops-btn-primary" disabled title="Funcionalidade em produção">
                        Cadastrar Sensor
                    </button>
                </div>
            </form>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
    <script src="../scripts/script_sensor.js"></script>
</body>

</html>
