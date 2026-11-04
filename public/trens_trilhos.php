<?php
session_start();
require_once 'components/start.php';
require_once '../infra/conn.php';
redirecionarSeNaoAdmin();

$usuarioEhAdmin = (($_SESSION['cargo'] ?? '') === 'admin');

$resTrens = $db->query(
    "SELECT t.*, f.nome AS responsavel_nome,
            (SELECT COUNT(*) FROM rota r WHERE r.trem_id = t.id) AS total_rotas,
            (SELECT COUNT(*) FROM sensor s WHERE s.trem_id = t.id) AS total_sensores
     FROM trem t
     LEFT JOIN funcionario f ON f.id = t.responsavel_usuario_id
     ORDER BY t.id DESC"
);

$listaTrens = [];
if ($resTrens) {
    while ($t = $resTrens->fetch_assoc()) {
        $listaTrens[] = $t;
    }
}

$resRotas = $db->query(
    "SELECT r.*, t.nome AS trem_nome, t.modelo AS trem_modelo
     FROM rota r
     INNER JOIN trem t ON t.id = r.trem_id
     ORDER BY r.id DESC"
);

$listaRotas = [];
$extensaoTotalKm = 0.0;
if ($resRotas) {
    while ($r = $resRotas->fetch_assoc()) {
        $listaRotas[] = $r;
        $extensaoTotalKm += (float) ($r['distancia_km'] ?? 0);
    }
}

$totalTrilhos = 0;
$resTrilhosCount = @$db->query("SELECT COUNT(*) AS total FROM trilho");
if ($resTrilhosCount && ($rowTr = $resTrilhosCount->fetch_assoc())) {
    $totalTrilhos = (int) $rowTr['total'];
}

$pageTitle = 'Trens, Rotas e Via Permanente';
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
                    <span>Trens e Rotas</span>
                </nav>
                <h1 class="ops-page-title">Trens e Rotas</h1>
            </div>

            <div class="ops-header-actions">
                <a href="hub.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Dashboard
                </a>
                <?php if ($usuarioEhAdmin): ?>
                    <a href="cadastro_linhas.php" class="ops-btn">
                        Trilhos e Linhas
                    </a>
                    <a href="cadastro_trens.php" class="ops-btn ops-btn-primary">
                        Gerenciar Trens
                    </a>
                    <a href="cadastro_rotas.php" class="ops-btn ops-btn-primary">
                        Gerenciar Rotas
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="ops-panel mb-3">
            <div class="ops-toolbar border-bottom-0">
                <div class="ops-search-wrap" style="max-width: 460px;">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input
                        type="search"
                        id="busca-frota-rotas"
                        class="ops-search-input"
                        placeholder="Pesquisar trens por nome/modelo ou rotas por itinerário..."
                        aria-label="Pesquisar trens e rotas">
                </div>
            </div>
        </div>

        <div class="row g-3">

            <div class="col-12 col-xl-6">
                <section class="ops-panel h-100 d-flex flex-column" aria-labelledby="heading-trens">
                    <div class="ops-panel-header">
                        <h2 id="heading-trens" class="ops-panel-title">
                            Trens Cadastrados
                        </h2>
                        <div class="d-flex align-items-center gap-2">
                            <span class="ops-status-tag ops-status-neutral"><?= count($listaTrens) ?> Trem(ns)</span>
                            <?php if ($usuarioEhAdmin): ?>
                                <a href="cadastro_trens.php" class="ops-btn ops-btn-primary ops-btn-sm">
                                    <i class="bi bi-plus-lg" aria-hidden="true"></i>Novo Trem
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="table-responsive flex-grow-1">
                        <table class="ops-table" id="tabela-trens-overview" aria-label="Lista de trens">
                            <thead>
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Trem / Modelo</th>
                                    <th scope="col">Responsável</th>
                                    <th scope="col" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($listaTrens)): ?>
                                    <?php foreach ($listaTrens as $trem):
                                        $idTrem = (int) ($trem['id'] ?? 0);
                                        $nomeTrem = (string) ($trem['nome'] ?? '');
                                        $modeloTrem = (string) ($trem['modelo'] ?? '');
                                        $respTrem = (string) ($trem['responsavel_nome'] ?? 'Não atribuído');
                                        $criadoTrem = !empty($trem['criado_em']) ? date('d/m/Y H:i', strtotime((string) $trem['criado_em'])) : 'Ativo';
                                        $buscaTrem = mb_strtolower($nomeTrem . ' ' . $modeloTrem . ' ' . $respTrem, 'UTF-8');
                                    ?>
                                        <tr data-filter-row="<?= htmlspecialchars($buscaTrem, ENT_QUOTES, 'UTF-8') ?>">
                                            <td>
                                                <span class="ops-code-badge"><?= sprintf('#TRM-%03d', $idTrem) ?></span>
                                            </td>
                                            <td>
                                                <strong class="text-white d-block"><?= htmlspecialchars($nomeTrem, ENT_QUOTES, 'UTF-8') ?></strong>
                                                <span class="mono-font text-steel small"><?= htmlspecialchars($modeloTrem, ENT_QUOTES, 'UTF-8') ?></span>
                                            </td>
                                            <td>
                                                <?php if (!empty($trem['responsavel_usuario_id'])): ?>
                                                    <a href="Usuario_Info.php?id=<?= (int) $trem['responsavel_usuario_id'] ?>" class="text-cyan text-decoration-none small fw-semibold">
                                                        <?= htmlspecialchars($respTrem, ENT_QUOTES, 'UTF-8') ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-steel small"><?= htmlspecialchars($respTrem, ENT_QUOTES, 'UTF-8') ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-1">
                                                    <button
                                                        type="button"
                                                        class="ops-btn ops-btn-ghost ops-btn-sm btn-consultar-ficha"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalFichaFrotaRota"
                                                        data-ficha-tipo="Composição Ferroviária"
                                                        data-ficha-codigo="<?= sprintf('#TRM-%03d', $idTrem) ?>"
                                                        data-ficha-titulo="<?= htmlspecialchars($nomeTrem, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-ficha-c1-label="Modelo da Locomotiva / Trem"
                                                        data-ficha-c1-val="<?= htmlspecialchars($modeloTrem, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-ficha-c2-label="Operador Responsável"
                                                        data-ficha-c2-val="<?= htmlspecialchars($respTrem, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-ficha-c3-label="Vínculos na Malha"
                                                        data-ficha-c3-val="<?= (int) ($trem['total_rotas'] ?? 0) ?> rota(s) • <?= (int) ($trem['total_sensores'] ?? 0) ?> sensor(es)"
                                                        data-ficha-c4-label="Data de Registro"
                                                        data-ficha-c4-val="<?= htmlspecialchars($criadoTrem, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-ficha-desc="Composição habilitada para vinculação de itinerários e sensores embarcados de velocidade/temperatura."
                                                        data-ficha-edit-url="editar_trem.php?id=<?= $idTrem ?>">
                                                        Consultar
                                                    </button>
                                                    <?php if ($usuarioEhAdmin): ?>
                                                        <a href="editar_trem.php?id=<?= $idTrem ?>" class="ops-btn ops-btn-sm">
                                                            Editar
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="ops-empty-state">
                                                <span class="ops-empty-icon"><i class="bi bi-train-front" aria-hidden="true"></i></span>
                                                <p class="mb-1 fw-semibold text-white">Nenhum trem cadastrado.</p>
                                                <p class="small text-steel mb-3">Cadastre um trem para habilitar criação de rotas.</p>
                                                <?php if ($usuarioEhAdmin): ?>
                                                    <a href="cadastro_trens.php" class="ops-btn ops-btn-primary ops-btn-sm">
                                                        <i class="bi bi-plus-lg" aria-hidden="true"></i>Cadastrar Primeiro Trem
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-6">
                <section class="ops-panel h-100 d-flex flex-column" aria-labelledby="heading-rotas">
                    <div class="ops-panel-header">
                        <h2 id="heading-rotas" class="ops-panel-title">
                            Rotas Cadastradas
                        </h2>
                        <div class="d-flex align-items-center gap-2">
                            <span class="ops-status-tag ops-status-neutral"><?= count($listaRotas) ?> Rota(s)</span>
                            <?php if ($usuarioEhAdmin): ?>
                                <a href="cadastro_rotas.php" class="ops-btn ops-btn-primary ops-btn-sm">
                                    <i class="bi bi-plus-lg" aria-hidden="true"></i>Nova Rota
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="table-responsive flex-grow-1">
                        <table class="ops-table" id="tabela-rotas-overview" aria-label="Lista de rotas">
                            <thead>
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Rota / Trem</th>
                                    <th scope="col" class="text-end">Distância</th>
                                    <th scope="col" class="text-end">Tempo</th>
                                    <th scope="col" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($listaRotas)): ?>
                                    <?php foreach ($listaRotas as $rota):
                                        $idRota = (int) ($rota['id'] ?? 0);
                                        $nomeRota = (string) ($rota['nome'] ?? '');
                                        $descRota = (string) ($rota['descricao'] ?? '');
                                        $distKm = (float) ($rota['distancia_km'] ?? 0);
                                        $tempoMin = (int) ($rota['tempo_previsto_min'] ?? 0);
                                        $velMedia = ($tempoMin > 0) ? ($distKm / ($tempoMin / 60.0)) : 0.0;
                                        $tremRota = (string) ($rota['trem_nome'] ?? '');
                                        $buscaRota = mb_strtolower($nomeRota . ' ' . $descRota . ' ' . $tremRota, 'UTF-8');
                                    ?>
                                        <tr data-filter-row="<?= htmlspecialchars($buscaRota, ENT_QUOTES, 'UTF-8') ?>">
                                            <td>
                                                <span class="ops-code-badge"><?= sprintf('#RTA-%03d', $idRota) ?></span>
                                            </td>
                                            <td>
                                                <strong class="text-white d-block"><?= htmlspecialchars($nomeRota, ENT_QUOTES, 'UTF-8') ?></strong>
                                                <span class="text-steel small">
                                                    <?= htmlspecialchars($tremRota, ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="text-end mono-font small">
                                                <?= number_format($distKm, 2, ',', '.') ?> <span class="text-steel">km</span>
                                            </td>
                                            <td class="text-end mono-font small">
                                                <?= $tempoMin ?> <span class="text-steel">min</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-1">
                                                    <button
                                                        type="button"
                                                        class="ops-btn ops-btn-ghost ops-btn-sm btn-consultar-ficha"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalFichaFrotaRota"
                                                        data-ficha-tipo="Rota"
                                                        data-ficha-codigo="<?= sprintf('#RTA-%03d', $idRota) ?>"
                                                        data-ficha-titulo="<?= htmlspecialchars($nomeRota, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-ficha-c1-label="Trem Vinculado"
                                                        data-ficha-c1-val="<?= htmlspecialchars($tremRota, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-ficha-c2-label="Extensão do Trecho"
                                                        data-ficha-c2-val="<?= number_format($distKm, 2, ',', '.') ?> km"
                                                        data-ficha-c3-label="Tempo Previsto"
                                                        data-ficha-c3-val="<?= $tempoMin ?> minutos"
                                                        data-ficha-c4-label="Velocidade Média Planejada"
                                                        data-ficha-c4-val="<?= number_format($velMedia, 1, ',', '.') ?> km/h"
                                                        data-ficha-desc="<?= htmlspecialchars($descRota, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-ficha-edit-url="editar_rota.php?id=<?= $idRota ?>">
                                                        Consultar
                                                    </button>
                                                    <?php if ($usuarioEhAdmin): ?>
                                                        <a href="editar_rota.php?id=<?= $idRota ?>" class="ops-btn ops-btn-sm">
                                                            Editar
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="ops-empty-state">
                                                <span class="ops-empty-icon"><i class="bi bi-signpost-split" aria-hidden="true"></i></span>
                                                <p class="mb-1 fw-semibold text-white">Nenhuma rota cadastrada.</p>
                                                <p class="small text-steel mb-3">Cadastre a primeira rota.</p>
                                                <?php if ($usuarioEhAdmin): ?>
                                                    <a href="cadastro_rotas.php" class="ops-btn ops-btn-primary ops-btn-sm">
                                                        <i class="bi bi-plus-lg" aria-hidden="true"></i>Cadastrar Primeira Rota
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

        </div>

    </main>

    <div class="modal fade ops-modal" id="modalFichaFrotaRota" tabindex="-1" aria-labelledby="fichaModalTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <span id="fichaModalTipo" class="mono-font text-cyan text-uppercase d-block" style="font-size: 0.7rem; letter-spacing: 0.07em;">Ficha Técnica</span>
                        <h3 class="modal-title fs-5 fw-bold text-white d-flex align-items-center gap-2" id="fichaModalTitulo">
                            <span id="fichaModalCodigo" class="ops-code-badge"></span>
                            <span id="fichaModalNome"></span>
                        </h3>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="ops-kv-grid mb-3">
                        <div class="ops-kv-item">
                            <span id="fichaC1Label" class="ops-kv-label"></span>
                            <span id="fichaC1Val" class="ops-kv-value"></span>
                        </div>
                        <div class="ops-kv-item">
                            <span id="fichaC2Label" class="ops-kv-label"></span>
                            <span id="fichaC2Val" class="ops-kv-value"></span>
                        </div>
                        <div class="ops-kv-item">
                            <span id="fichaC3Label" class="ops-kv-label"></span>
                            <span id="fichaC3Val" class="ops-kv-value mono-font"></span>
                        </div>
                        <div class="ops-kv-item">
                            <span id="fichaC4Label" class="ops-kv-label"></span>
                            <span id="fichaC4Val" class="ops-kv-value mono-font"></span>
                        </div>
                    </div>
                    <div class="ops-kv-item">
                        <span class="ops-kv-label">Descrição / Observações Operacionais</span>
                        <p id="fichaDesc" class="mb-0 text-steel small" style="line-height: 1.55;"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="ops-btn ops-btn-ghost" data-bs-dismiss="modal">Fechar</button>
                    <?php if ($usuarioEhAdmin): ?>
                        <a href="#" id="fichaEditLink" class="ops-btn ops-btn-primary">
                           Editar Registro
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
    <script src="../scripts/script_trens_trilhos.js"></script>
    <script src="../scripts/pagination.js"></script>
</body>

</html>
