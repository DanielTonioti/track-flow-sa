<?php
session_start();
require_once('components/csrf.php');
validarTokenCSRF();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    header('Location: cadastro_rotas.php');
    exit;
}

$stmt = $db->prepare('SELECT id, nome, descricao, distancia_km, tempo_previsto_min, trem_id FROM rota WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$rota = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$rota) {
    header('Location: cadastro_rotas.php');
    exit;
}

$erro = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar-rota'])) {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $distancia = trim($_POST['distancia_km'] ?? '');
    $tempo = trim($_POST['tempo_previsto_min'] ?? '');
    $tremId = trim((string) ($_POST['trem_id'] ?? ''));

    if ($nome === '') {
        $erro = 'O nome da rota é obrigatório.';
    } elseif (mb_strlen($nome) < 2 || mb_strlen($nome) > 100) {
        $erro = 'O nome da rota deve ter entre 2 e 100 caracteres.';
    } elseif ($descricao === '') {
        $erro = 'A descrição da rota é obrigatória.';
    } elseif (mb_strlen($descricao) < 5 || mb_strlen($descricao) > 500) {
        $erro = 'A descrição da rota deve ter entre 5 e 500 caracteres.';
    } elseif (!is_numeric($distancia) || (float) $distancia <= 0) {
        $erro = 'A distância deve ser maior que zero.';
    } elseif (!ctype_digit($tempo) || (int) $tempo <= 0) {
        $erro = 'O tempo previsto deve ser um número inteiro maior que zero.';
    } elseif ($tremId === '' || !ctype_digit($tremId) || (int) $tremId <= 0) {
        $erro = 'Selecione um trem válido.';
    }

    if ($erro === '') {
        $tremIdInt = (int) $tremId;
        $checkTrem = $db->prepare('SELECT id FROM trem WHERE id = ?');
        $checkTrem->bind_param('i', $tremIdInt);
        $checkTrem->execute();

        if ($checkTrem->get_result()->num_rows === 0) {
            $erro = 'Esse trem não existe.';
        }

        $checkTrem->close();
    }

    if ($erro === '') {
        $distFloat = (float) $distancia;
        $tempoInt = (int) $tempo;
        $tremIdInt = (int) $tremId;

        $update = $db->prepare('UPDATE rota SET nome = ?, descricao = ?, distancia_km = ?, tempo_previsto_min = ?, trem_id = ? WHERE id = ?');
        $update->bind_param('ssdiii', $nome, $descricao, $distFloat, $tempoInt, $tremIdInt, $id);

        if ($update->execute()) {
            $rota['nome'] = $nome;
            $rota['descricao'] = $descricao;
            $rota['distancia_km'] = $distFloat;
            $rota['tempo_previsto_min'] = $tempoInt;
            $rota['trem_id'] = $tremIdInt;
            $sucesso = true;
        } else {
            $erro = 'Erro ao atualizar a rota.';
        }

        $update->close();
    }
}

$trens = $db->query('SELECT id, nome, modelo FROM trem ORDER BY nome ASC');
$pageTitle = 'Editar Rota';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">

<head>
    <?php include 'components/head.php'; ?>
</head>

<body class="ops-app-body">
    <header><?php include 'components/navbar.php'; ?></header>

    <main class="ops-shell" style="max-width: 820px;">

        <div class="ops-page-header">
            <div>
                <nav class="ops-breadcrumb" aria-label="Breadcrumb">
                    <a href="hub.php">Dashboard</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <a href="trens_trilhos.php">Trens e Rotas</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <a href="cadastro_rotas.php">Rotas</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <span>Editar <?= sprintf('#RTA-%03d', (int) $id) ?></span>
                </nav>
                <h1 class="ops-page-title">Editar Rota</h1>
            </div>

            <div class="ops-header-actions">
                <a href="cadastro_rotas.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Voltar para Rotas
                </a>
            </div>
        </div>

        <?php if ($erro !== ''): ?>
            <div class="ops-alert ops-alert-danger" role="alert">
                <i class="bi bi-exclamation-octagon-fill" aria-hidden="true"></i>
                <div>
                    <strong class="d-block">Revise os dados</strong>
                    <span><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="ops-alert ops-alert-success" role="status">
                <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 w-100">
                    <div>
                        <strong class="d-block">Rota atualizada com sucesso</strong>
                        <span>Os novos parâmetros do itinerário foram salvos.</span>
                    </div>
                    <a href="trens_trilhos.php" class="ops-btn ops-btn-sm">Ver Visão Geral</a>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" class="ops-panel">
            <input type="hidden" name="csrf_token"value="<?= htmlspecialchars(gerarTokenCSRF(), ENT_QUOTES, 'UTF-8') ?>">
            <div class="ops-panel-header">
                <h2 class="ops-panel-title">
                    <i class="bi bi-pencil-square text-volt" aria-hidden="true"></i>Ficha de Edição &mdash; <?= sprintf('#RTA-%03d', (int) $id) ?>
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
                        value="<?= htmlspecialchars((string) ($rota['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="ops-label">Descrição da rota <span class="ops-required" aria-hidden="true">*</span></label>
                    <textarea
                        id="descricao"
                        name="descricao"
                        class="ops-textarea"
                        rows="4"
                        maxlength="500"
                        required><?= htmlspecialchars((string) ($rota['descricao'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6">
                        <label for="distancia_km" class="ops-label">Distância (km) <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="number"
                            id="distancia_km"
                            name="distancia_km"
                            class="ops-input mono-font"
                            min="0.01"
                            step="0.01"
                            value="<?= htmlspecialchars((string) ($rota['distancia_km'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="tempo_previsto_min" class="ops-label">Tempo previsto (minutos) <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="number"
                            id="tempo_previsto_min"
                            name="tempo_previsto_min"
                            class="ops-input mono-font"
                            min="1"
                            step="1"
                            value="<?= htmlspecialchars((string) ($rota['tempo_previsto_min'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                </div>

                <div class="p-2 rounded-1 mb-3 d-flex justify-content-between align-items-center" style="background: var(--ops-ground); border: 1px solid var(--ops-border);">
                    <span class="mono-font text-steel" style="font-size: 0.72rem;">VELOCIDADE MÉDIA PLANEJADA</span>
                    <strong id="preview-velocidade-edit" class="mono-font text-cyan small">&mdash; km/h</strong>
                </div>

                <div class="mb-2">
                    <label for="trem_id" class="ops-label">Trem vinculado <span class="ops-required" aria-hidden="true">*</span></label>
                    <select id="trem_id" name="trem_id" class="ops-select" required>
                        <option value="">Selecione um trem...</option>
                        <?php if ($trens): ?>
                            <?php while ($trem = $trens->fetch_assoc()): ?>
                                <option value="<?= (int) $trem['id'] ?>" <?= ((string) ($rota['trem_id'] ?? '') === (string) $trem['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($trem['nome'], ENT_QUOTES, 'UTF-8') ?> &mdash; <?= htmlspecialchars($trem['modelo'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="ops-panel-footer">
                <a href="cadastro_rotas.php" class="ops-btn ops-btn-ghost">Cancelar</a>
                <button type="submit" name="atualizar-rota" class="ops-btn ops-btn-primary">
                    <i class="bi bi-floppy" aria-hidden="true"></i>Salvar Alterações
                </button>
            </div>
        </form>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dEl = document.getElementById('distancia_km');
            const tEl = document.getElementById('tempo_previsto_min');
            const out = document.getElementById('preview-velocidade-edit');
            function calc() {
                if (!dEl || !tEl || !out) return;
                const d = parseFloat(dEl.value);
                const t = parseFloat(tEl.value);
                out.textContent = (d > 0 && t > 0) ? (d / (t / 60)).toFixed(1).replace('.', ',') + ' km/h' : '— km/h';
            }
            if (dEl && tEl) {
                dEl.addEventListener('input', calc);
                tEl.addEventListener('input', calc);
                calc();
            }
        });
    </script>
</body>

</html>
