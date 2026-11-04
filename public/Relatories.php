<?php
session_start();
require_once 'components/start.php';
include "../infra/conn.php";

$sensorId = (int) ($_GET['sensor_id'] ?? 0);
$inicio = trim((string) ($_GET['inicio'] ?? ''));
$fim = trim((string) ($_GET['fim'] ?? ''));
$dataInicial = $inicio !== '' ? $inicio : '1000-01-01';
$dataFinal = $fim !== '' ? $fim : '9999-12-31';

$sensores = [];
if ($resSensores = $db->query('SELECT id, nome FROM sensor ORDER BY nome')) {
    $sensores = $resSensores->fetch_all(MYSQLI_ASSOC);
}

$relatorios = [];
$sql = 'SELECT dados_sensor.id, sensor.nome AS sensor, trem.nome AS trem,
               dados_sensor.valor, dados_sensor.data_registro
        FROM dados_sensor
        JOIN sensor ON sensor.id = dados_sensor.sensor_id
        LEFT JOIN trem ON trem.id = sensor.trem_id
        WHERE (? = 0 OR dados_sensor.sensor_id = ?)
          AND DATE(dados_sensor.data_registro) BETWEEN ? AND ?
        ORDER BY dados_sensor.data_registro DESC';
if ($stmt = $db->prepare($sql)) {
    $stmt->bind_param('iiss', $sensorId, $sensorId, $dataInicial, $dataFinal);
    $stmt->execute();
    $relatorios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

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

    <main class="ops-shell">
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
                <a href="Relatories_New.php" class="ops-btn ops-btn-primary">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i>Novo Relatório
                </a>
            </div>
        </div>

        <section class="ops-panel mb-3" aria-labelledby="titulo-filtro-relatorios">
            <div class="ops-panel-header">
                <h2 id="titulo-filtro-relatorios" class="ops-panel-title">
                    <i class="bi bi-funnel text-cyan" aria-hidden="true"></i>Filtro de Leituras
                </h2>
            </div>
            <div class="ops-panel-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="ops-label" for="sensor_id">Sensor</label>
                        <select class="ops-select" name="sensor_id" id="sensor_id">
                            <option value="0">Todos</option>
                            <?php foreach ($sensores as $sensor): ?>
                                <option value="<?= (int) $sensor['id'] ?>" <?= $sensorId === (int) $sensor['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars((string) $sensor['nome'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="ops-label" for="inicio">De</label>
                        <input class="ops-input" type="date" name="inicio" id="inicio" value="<?= htmlspecialchars($inicio, ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="ops-label" for="fim">Até</label>
                        <input class="ops-input" type="date" name="fim" id="fim" value="<?= htmlspecialchars($fim, ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-12 col-md-2">
                        <button class="ops-btn ops-btn-primary w-100" type="submit">
                            <i class="bi bi-search" aria-hidden="true"></i>Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section class="ops-panel" aria-labelledby="titulo-tabela-relatorios">
            <div class="ops-panel-header">
                <h2 id="titulo-tabela-relatorios" class="ops-panel-title">
                    <i class="bi bi-journal-text text-cyan" aria-hidden="true"></i>Leituras dos Sensores
                </h2>
                <span class="ops-status-tag ops-status-neutral"><?= count($relatorios) ?> REGISTRO(S)</span>
            </div>

            <div class="table-responsive">
                <table class="ops-table" aria-label="Leituras registradas">
                    <thead>
                        <tr>
                            <th scope="col">Sensor</th>
                            <th scope="col">Trem / Linha</th>
                            <th scope="col" class="text-end">Valor</th>
                            <th scope="col" class="text-end">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($relatorios)): ?>
                            <?php foreach ($relatorios as $relatorio): ?>
                                <tr>
                                    <td class="fw-semibold text-white"><?= htmlspecialchars((string) $relatorio['sensor'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-steel"><?= htmlspecialchars((string) ($relatorio['trem'] ?? 'Via permanente'), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-end mono-font text-volt"><?= htmlspecialchars((string) $relatorio['valor'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-end mono-font small text-steel">
                                        <?= !empty($relatorio['data_registro']) ? date('d/m/Y H:i', strtotime((string) $relatorio['data_registro'])) : '—' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">
                                    <div class="ops-empty-state">
                                        <span class="ops-empty-icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
                                        <p class="mb-1 fw-semibold text-white">Página em produção.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
</body>

</html>
