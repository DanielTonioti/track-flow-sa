<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

$sensors = [];
$sql = "SELECT id, nome, tipo FROM sensor ORDER BY id";
if ($stmt = $db->prepare($sql)) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $sensors[] = $row;
    }
    $stmt->close();
}

$pageTitle = 'Gerenciar Sensores';
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

    <main class="ops-shell" style="max-width: 960px;">
        <div class="ops-page-header">
            <div>
                <nav class="ops-breadcrumb" aria-label="Breadcrumb">
                    <a href="hub.php">Dashboard</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <a href="Sensor_Data.php">Sensores</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <span>Gerenciar</span>
                </nav>
                <h1 class="ops-page-title">Gerenciar Sensores</h1>
            </div>

            <div class="ops-header-actions">
                <a href="Sensor_Data.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Sensores
                </a>
                <a href="Sensor.php" class="ops-btn ops-btn-primary">
                    Adicionar Sensor
                </a>
            </div>
        </div>

        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="ops-alert ops-alert-info" role="status">
                <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                <div><?= htmlspecialchars((string) $_SESSION['mensagem'], ENT_QUOTES, 'UTF-8') ?></div>
            </div>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>

        <section class="ops-panel">
            <div class="ops-panel-header">
                <h2 class="ops-panel-title">
                    Sensores Cadastrados
                </h2>
                <span class="ops-status-tag ops-status-neutral"><?= count($sensors) ?> Item(ns)</span>
            </div>

            <div class="table-responsive">
                <table class="ops-table" aria-label="Gerenciamento de sensores">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Sensor</th>
                            <th scope="col">Tipo</th>
                            <th scope="col" class="text-end">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sensors)): ?>
                            <?php foreach ($sensors as $sensor): ?>
                                <tr>
                                    <td><span class="ops-code-badge"><?= sprintf('#SNS-%03d', (int) $sensor['id']) ?></span></td>
                                    <td class="fw-semibold text-white"><?= htmlspecialchars((string) $sensor['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-steel"><?= htmlspecialchars((string) ($sensor['tipo'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-end">
                                        <button
                                            type="button"
                                            class="ops-btn ops-btn-danger-outline ops-btn-sm btn-preparar-exclusao"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalExcluirSensor"
                                            data-sensor-id="<?= (int) $sensor['id'] ?>"
                                            data-sensor-nome="<?= htmlspecialchars((string) $sensor['nome'], ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="bi bi-trash3" aria-hidden="true"></i>Remover
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">
                                    <div class="ops-empty-state">
                                        <span class="ops-empty-icon"><i class="bi bi-sliders" aria-hidden="true"></i></span>
                                        <p class="mb-1 fw-semibold text-white">Página em produção.</p>
                                        <p class="small text-steel mb-0">O gerenciamento de sensores está em produção.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div class="modal fade ops-modal" id="ModalExcluirSensor" tabindex="-1" aria-labelledby="TituloModalExcluirSensor" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5 brand-font" id="TituloModalExcluirSensor">Excluir sensor</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir o sensor <strong id="nome-sensor-modal" class="text-white">selecionado</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="ops-btn ops-btn-ghost" data-bs-dismiss="modal">Cancelar</button>
                    <a href="Sensor_Manage.php" id="link-confirmar-exclusao-sensor" class="ops-btn ops-btn-danger">Confirmar</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
    <script src="../scripts/script_sensor_manage.js"></script>
    <script src="../scripts/pagination.js"></script>
</body>

</html>
