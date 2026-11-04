<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';

$ehAdmin = (($_SESSION['cargo'] ?? '') === 'admin');

$sensoresBanco = [];
$sql = "SELECT s.id, s.nome, s.tipo, t.nome AS trem_nome, tr.nome AS trilho_nome
        FROM sensor s
        LEFT JOIN trem t ON t.id = s.trem_id
        LEFT JOIN trilho tr ON tr.id = s.trilho_id
        ORDER BY s.id DESC";
if ($res = $db->query($sql)) {
    while ($row = $res->fetch_assoc()) {
        $sensoresBanco[] = $row;
    }
}

$pageTitle = 'Sensores';
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
                    <span>Sensores</span>
                </nav>
                <h1 class="ops-page-title">Sensores Cadastrados</h1>
            </div>

            <div class="ops-header-actions">
                <a href="hub.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Dashboard
                </a>
                <?php if ($ehAdmin): ?>
                    <a href="Sensor_Manage.php" class="ops-btn">
                        <i class="bi bi-sliders" aria-hidden="true"></i>Gerenciar
                    </a>
                    <a href="Sensor.php" class="ops-btn ops-btn-primary">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>Cadastrar Sensor
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <section class="ops-panel" aria-labelledby="titulo-painel-sensores">
            <div class="ops-panel-header">
                <h2 id="titulo-painel-sensores" class="ops-panel-title">
                    <i class="bi bi-cpu text-cyan" aria-hidden="true"></i>Lista de Sensores
                </h2>
                <span class="ops-status-tag ops-status-neutral"><?= count($sensoresBanco) ?> REGISTRO(S)</span>
            </div>

            <div class="table-responsive">
                <table class="ops-table" aria-label="Sensores cadastrados">
                    <thead>
                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Identificação</th>
                            <th scope="col">Tipo de Dado</th>
                            <th scope="col">Vínculo</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sensoresBanco)): ?>
                            <?php foreach ($sensoresBanco as $s): ?>
                                <tr>
                                    <td><span class="ops-code-badge"><?= sprintf('#SNS-%03d', (int) $s['id']) ?></span></td>
                                    <td class="fw-semibold text-white"><?= htmlspecialchars((string) $s['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-steel"><?= htmlspecialchars((string) $s['tipo'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-steel">
                                        <?php if (!empty($s['trem_nome'])): ?>
                                            Trem: <?= htmlspecialchars((string) $s['trem_nome'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php elseif (!empty($s['trilho_nome'])): ?>
                                            Trilho: <?= htmlspecialchars((string) $s['trilho_nome'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="ops-status-tag ops-status-nominal">
                                            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>CADASTRADO
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="ops-empty-state">
                                        <span class="ops-empty-icon"><i class="bi bi-cpu" aria-hidden="true"></i></span>
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
