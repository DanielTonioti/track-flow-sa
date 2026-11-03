<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

$erro = '';
$sucesso = isset($_GET['excluida']);
$mensagemSucesso = $sucesso ? 'Rota excluída com sucesso.' : '';

$nomeInput = '';
$descricaoInput = '';
$distanciaInput = '';
$tempoInput = '';
$tremIdInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar-rota'])) {
    $nomeInput = trim($_POST['nome'] ?? '');
    $descricaoInput = trim($_POST['descricao'] ?? '');
    $distanciaInput = trim($_POST['distancia_km'] ?? '');
    $tempoInput = trim($_POST['tempo_previsto_min'] ?? '');
    $tremIdInput = trim((string) ($_POST['trem_id'] ?? ''));

    if ($nomeInput === '') {
        $erro = 'O nome da rota é obrigatório.';
    } elseif (mb_strlen($nomeInput) < 2 || mb_strlen($nomeInput) > 100) {
        $erro = 'O nome da rota deve ter entre 2 e 100 caracteres.';
    } elseif ($descricaoInput === '') {
        $erro = 'A descrição da rota é obrigatória.';
    } elseif (mb_strlen($descricaoInput) < 5 || mb_strlen($descricaoInput) > 500) {
        $erro = 'A descrição da rota deve ter entre 5 e 500 caracteres.';
    } elseif (!is_numeric($distanciaInput) || (float) $distanciaInput <= 0) {
        $erro = 'A distância deve ser maior que zero.';
    } elseif (!ctype_digit($tempoInput) || (int) $tempoInput <= 0) {
        $erro = 'O tempo previsto deve ser um número inteiro maior que zero.';
    } elseif ($tremIdInput === '' || !ctype_digit($tremIdInput) || (int) $tremIdInput <= 0) {
        $erro = 'Selecione um trem válido para esta rota.';
    }

    if ($erro === '') {
        $tremIdInt = (int) $tremIdInput;
        $checkTrem = $db->prepare('SELECT id FROM trem WHERE id = ?');
        $checkTrem->bind_param('i', $tremIdInt);
        $checkTrem->execute();

        if ($checkTrem->get_result()->num_rows === 0) {
            $erro = 'O trem selecionado não existe.';
        }

        $checkTrem->close();
    }

    if ($erro === '') {
        $distFloat = (float) $distanciaInput;
        $tempoInt = (int) $tempoInput;
        $tremIdInt = (int) $tremIdInput;

        $insert = $db->prepare('INSERT INTO rota (nome, descricao, distancia_km, tempo_previsto_min, trem_id) VALUES (?, ?, ?, ?, ?)');
        $insert->bind_param('ssdii', $nomeInput, $descricaoInput, $distFloat, $tempoInt, $tremIdInt);

        if ($insert->execute()) {
            $sucesso = true;
            $mensagemSucesso = 'Rota ferroviária cadastrada com sucesso.';
            $nomeInput = '';
            $descricaoInput = '';
            $distanciaInput = '';
            $tempoInput = '';
            $tremIdInput = '';
        } else {
            $erro = 'Erro ao cadastrar a rota no banco de dados.';
        }

        $insert->close();
    }
}

$resTrens = $db->query('SELECT id, nome, modelo FROM trem ORDER BY nome ASC');
$listaTrensSelect = [];
if ($resTrens) {
    while ($tr = $resTrens->fetch_assoc()) {
        $listaTrensSelect[] = $tr;
    }
}

$rotas = $db->query(
    "SELECT r.id, r.nome, r.descricao, r.distancia_km, r.tempo_previsto_min, r.trem_id,
            t.nome AS trem_nome, t.modelo AS trem_modelo
     FROM rota r
     LEFT JOIN trem t ON t.id = r.trem_id
     ORDER BY r.id DESC"
);

$pageTitle = 'Cadastrar e Gerenciar Rotas';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">

<head>
    <?php include 'components/head.php'; ?>
</head>

<body class="ops-app-body">
    <header><?php include 'components/navbar.php'; ?></header>

    <main class="ops-shell">

        <div class="ops-page-header">
            <div>
                <nav class="ops-breadcrumb" aria-label="Breadcrumb">
                    <a href="hub.php">Dashboard</a>
                    <span class="ops-breadcrumb-sep">//</span>
                    <a href="trens_trilhos.php">Trens e Rotas</a>
                    <span class="ops-breadcrumb-sep">//</span>
                    <span>Gerenciar Rotas</span>
                </nav>
                <h1 class="ops-page-title">Cadastro e Programação de Rotas</h1>
                <p class="ops-page-subtitle">
                    Defina itinerários, extensão quilométrica, tempo estimado de viagem e trem vinculado.
                </p>
            </div>

            <div class="ops-header-actions">
                <a href="trens_trilhos.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Visão Geral
                </a>
                <a href="cadastro_trens.php" class="ops-btn">
                    <i class="bi bi-train-front" aria-hidden="true"></i>Gerenciar Trens
                </a>
            </div>
        </div>

        <?php if ($erro !== ''): ?>
            <div class="ops-alert ops-alert-danger" role="alert">
                <i class="bi bi-exclamation-octagon-fill" aria-hidden="true"></i>
                <div>
                    <strong class="d-block">Verifique os campos da rota</strong>
                    <span><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($sucesso && $mensagemSucesso !== ''): ?>
            <div class="ops-alert ops-alert-success" role="status">
                <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                <div>
                    <strong class="d-block">Malha atualizada</strong>
                    <span><?= htmlspecialchars($mensagemSucesso, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($listaTrensSelect)): ?>
            <div class="ops-alert ops-alert-warning" role="status">
                <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 w-100">
                    <div>
                        <strong class="d-block">Nenhum trem cadastrado ainda</strong>
                        <span>Toda rota ferroviária exige uma composição vinculada. Cadastre um trem antes de criar rotas.</span>
                    </div>
                    <a href="cadastro_trens.php" class="ops-btn ops-btn-volt ops-btn-sm">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>Cadastrar Trem Agora
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <div class="row g-3 align-items-start">

            <!-- COLUNA ESQUERDA: FORMULÁRIO DE ROTA -->
            <div class="col-12 col-lg-5">
                <form method="POST" class="ops-panel">
                    <div class="ops-panel-header">
                        <h2 class="ops-panel-title">
                            <i class="bi bi-signpost-2 text-volt" aria-hidden="true"></i>Nova Rota Operacional
                        </h2>
                    </div>

                    <div class="ops-panel-body">
                        <div class="mb-3">
                            <label for="nome" class="ops-label">Nome da rota <span class="ops-required" aria-hidden="true">*</span></label>
                            <input
                                type="text"
                                id="nome"
                                name="nome"
                                class="ops-input"
                                maxlength="100"
                                placeholder="Ex.: Corredor Joinville — Porto São Francisco"
                                value="<?= htmlspecialchars($nomeInput, ENT_QUOTES, 'UTF-8') ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="ops-label">Descrição operacional <span class="ops-required" aria-hidden="true">*</span></label>
                            <textarea
                                id="descricao"
                                name="descricao"
                                class="ops-textarea"
                                rows="3"
                                maxlength="500"
                                placeholder="Descreva pátios de cruzamento, tipo de carga ou restrições do trecho..."
                                required><?= htmlspecialchars($descricaoInput, ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="distancia_km" class="ops-label">Distância (km) <span class="ops-required" aria-hidden="true">*</span></label>
                                <input
                                    type="number"
                                    id="distancia_km"
                                    name="distancia_km"
                                    class="ops-input mono-font"
                                    min="0.01"
                                    step="0.01"
                                    placeholder="46.50"
                                    value="<?= htmlspecialchars($distanciaInput, ENT_QUOTES, 'UTF-8') ?>"
                                    required>
                            </div>
                            <div class="col-6">
                                <label for="tempo_previsto_min" class="ops-label">Tempo (min) <span class="ops-required" aria-hidden="true">*</span></label>
                                <input
                                    type="number"
                                    id="tempo_previsto_min"
                                    name="tempo_previsto_min"
                                    class="ops-input mono-font"
                                    min="1"
                                    step="1"
                                    placeholder="55"
                                    value="<?= htmlspecialchars($tempoInput, ENT_QUOTES, 'UTF-8') ?>"
                                    required>
                            </div>
                        </div>

                        <div id="preview-velocidade-box" class="p-2 rounded-1 mb-3 d-flex justify-content-between align-items-center" style="background: var(--ops-ground); border: 1px solid var(--ops-border);">
                            <span class="mono-font text-steel" style="font-size: 0.72rem;">VELOCIDADE MÉDIA ESTIMADA</span>
                            <strong id="preview-velocidade-valor" class="mono-font text-cyan small">&mdash; km/h</strong>
                        </div>

                        <div class="mb-2">
                            <label for="trem_id" class="ops-label">Trem vinculado <span class="ops-required" aria-hidden="true">*</span></label>
                            <select id="trem_id" name="trem_id" class="ops-select" <?= empty($listaTrensSelect) ? 'disabled' : '' ?> required>
                                <option value="">Selecione um trem...</option>
                                <?php foreach ($listaTrensSelect as $trem): ?>
                                    <option value="<?= (int) $trem['id'] ?>" <?= ($tremIdInput !== '' && (string) $tremIdInput === (string) $trem['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($trem['nome'], ENT_QUOTES, 'UTF-8') ?> &mdash; <?= htmlspecialchars($trem['modelo'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="ops-panel-footer justify-content-end">
                        <button type="submit" name="cadastrar-rota" class="ops-btn ops-btn-primary w-100" <?= empty($listaTrensSelect) ? 'disabled' : '' ?>>
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>Cadastrar Rota
                        </button>
                    </div>
                </form>
            </div>

            <!-- COLUNA DIREITA: LISTAGEM DE ROTAS -->
            <div class="col-12 col-lg-7">
                <section class="ops-panel" aria-labelledby="titulo-lista-rotas">
                    <div class="ops-panel-header">
                        <h2 id="titulo-lista-rotas" class="ops-panel-title">
                            <i class="bi bi-signpost-split text-cyan" aria-hidden="true"></i>Rotas Cadastradas
                        </h2>
                        <span class="ops-status-tag ops-status-neutral">
                            <?= $rotas ? (int) $rotas->num_rows : 0 ?> REGISTRO(S)
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="ops-table" aria-label="Tabela de rotas cadastradas">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Itinerário // Trem</th>
                                    <th scope="col" class="text-end">Distância</th>
                                    <th scope="col" class="text-end">Tempo</th>
                                    <th scope="col" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rotas && $rotas->num_rows > 0): ?>
                                    <?php while ($rota = $rotas->fetch_assoc()):
                                        $idR = (int) $rota['id'];
                                        $nomeR = (string) ($rota['nome'] ?? '');
                                        $descR = (string) ($rota['descricao'] ?? '');
                                        $distR = (float) ($rota['distancia_km'] ?? 0);
                                        $tempoR = (int) ($rota['tempo_previsto_min'] ?? 0);
                                        $tremNomeR = (string) ($rota['trem_nome'] ?? 'Trem não localizado');
                                    ?>
                                        <tr>
                                            <td><span class="ops-code-badge"><?= sprintf('#RTA-%03d', $idR) ?></span></td>
                                            <td>
                                                <strong class="text-white d-block"><?= htmlspecialchars($nomeR, ENT_QUOTES, 'UTF-8') ?></strong>
                                                <span class="text-steel small d-block text-truncate" style="max-width: 240px;" title="<?= htmlspecialchars($descR, ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars($descR, ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                                <span class="mono-font text-cyan" style="font-size: 0.72rem;">
                                                    <i class="bi bi-train-front me-1" aria-hidden="true"></i><?= htmlspecialchars($tremNomeR, ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="text-end mono-font small">
                                                <?= number_format($distR, 2, ',', '.') ?> <span class="text-steel">km</span>
                                            </td>
                                            <td class="text-end mono-font small">
                                                <?= $tempoR ?> <span class="text-steel">min</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-1">
                                                    <a href="editar_rota.php?id=<?= $idR ?>" class="ops-btn ops-btn-sm">
                                                        <i class="bi bi-pencil-square" aria-hidden="true"></i>Editar
                                                    </a>
                                                    <button
                                                        type="button"
                                                        class="ops-btn ops-btn-danger-outline ops-btn-sm btn-preparar-exclusao-rota"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalExcluirRota"
                                                        data-rota-id="<?= $idR ?>"
                                                        data-rota-nome="<?= htmlspecialchars($nomeR, ENT_QUOTES, 'UTF-8') ?>">
                                                        <i class="bi bi-trash3" aria-hidden="true"></i>Excluir
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="ops-empty-state">
                                                <span class="ops-empty-icon"><i class="bi bi-signpost-split" aria-hidden="true"></i></span>
                                                <p class="mb-0 fw-semibold text-white">Nenhuma rota cadastrada.</p>
                                                <span class="small text-steel">Preencha o formulário ao lado para registrar o primeiro itinerário.</span>
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
    <div class="modal fade ops-modal" id="modalExcluirRota" tabindex="-1" aria-labelledby="modalExcluirRotaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5 fw-bold text-white d-flex align-items-center gap-2" id="modalExcluirRotaLabel">
                        <i class="bi bi-exclamation-octagon-fill text-danger" aria-hidden="true"></i>Excluir Rota
                    </h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1">
                        Confirma a exclusão da rota <strong id="nomeRotaModal" class="text-white"></strong>?
                    </p>
                    <p class="small text-steel mb-0">Esta ação removerá o itinerário permanentemente.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="ops-btn ops-btn-ghost" data-bs-dismiss="modal">Cancelar</button>
                    <form action="excluir_rota.php" method="POST" class="m-0">
                        <input type="hidden" name="id" id="inputExcluirRotaId" value="">
                        <button type="submit" class="ops-btn ops-btn-danger">
                            <i class="bi bi-trash3-fill" aria-hidden="true"></i>Confirmar Exclusão
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const distEl = document.getElementById('distancia_km');
            const tempoEl = document.getElementById('tempo_previsto_min');
            const velOut = document.getElementById('preview-velocidade-valor');

            function atualizarVelocidade() {
                if (!distEl || !tempoEl || !velOut) return;
                const d = parseFloat(distEl.value);
                const t = parseFloat(tempoEl.value);
                if (d > 0 && t > 0) {
                    const vel = d / (t / 60);
                    velOut.textContent = vel.toFixed(1).replace('.', ',') + ' km/h';
                } else {
                    velOut.textContent = '— km/h';
                }
            }

            if (distEl && tempoEl) {
                distEl.addEventListener('input', atualizarVelocidade);
                tempoEl.addEventListener('input', atualizarVelocidade);
                atualizarVelocidade();
            }

            document.querySelectorAll('.btn-preparar-exclusao-rota').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('inputExcluirRotaId').value = btn.getAttribute('data-rota-id') || '';
                    document.getElementById('nomeRotaModal').textContent = btn.getAttribute('data-rota-nome') || '';
                });
            });
        });
    </script>
</body>

</html>
