<?php
session_start();
require_once('components/csrf.php');
validarTokenCSRF();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    header('Location: cadastro_trens.php');
    exit;
}

$erro = '';
$sucesso = false;

$stmt = $db->prepare('SELECT id, nome, modelo, responsavel_usuario_id FROM trem WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$trem = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$trem) {
    header('Location: cadastro_trens.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar-trem'])) {
    $nome = trim($_POST['nome'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $responsavelId = trim((string) ($_POST['responsavel_usuario_id'] ?? ''));

    if ($nome === '') {
        $erro = 'O nome do trem é obrigatório.';
    } elseif (mb_strlen($nome) < 2 || mb_strlen($nome) > 100) {
        $erro = 'O nome do trem deve ter entre 2 e 100 caracteres.';
    } elseif ($modelo === '') {
        $erro = 'O modelo do trem é obrigatório.';
    } elseif (mb_strlen($modelo) < 2 || mb_strlen($modelo) > 50) {
        $erro = 'O modelo do trem deve ter entre 2 e 50 caracteres.';
    } elseif ($responsavelId === '' || !ctype_digit($responsavelId) || (int) $responsavelId <= 0) {
        $erro = 'Selecione um operador responsável válido.';
    }

    if ($erro === '') {
        $responsavel = (int) $responsavelId;
        $check = $db->prepare('SELECT id FROM funcionario WHERE id = ?');
        $check->bind_param('i', $responsavel);
        $check->execute();
        if ($check->get_result()->num_rows === 0) {
            $erro = 'Esse funcionário não existe.';
        }
        $check->close();
    }

    if ($erro === '') {
        $update = $db->prepare('UPDATE trem SET nome = ?, modelo = ?, responsavel_usuario_id = ? WHERE id = ?');
        $update->bind_param('ssii', $nome, $modelo, $responsavel, $id);

        if ($update->execute()) {
            $trem['nome'] = $nome;
            $trem['modelo'] = $modelo;
            $trem['responsavel_usuario_id'] = $responsavel;
            $sucesso = true;
        } else {
            $erro = 'Erro ao atualizar o trem.';
        }

        $update->close();
    }
}

$usuarios = $db->query('SELECT id, nome, cargo FROM funcionario ORDER BY nome ASC');
$pageTitle = 'Editar Trem';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">

<head>
    <?php include 'components/head.php'; ?>
</head>

<body class="ops-app-body">
    <header><?php include 'components/navbar.php'; ?></header>

    <main class="ops-shell" style="max-width: 760px;">

        <div class="ops-page-header">
            <div>
                <nav class="ops-breadcrumb" aria-label="Breadcrumb">
                    <a href="hub.php">Dashboard</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <a href="trens_trilhos.php">Trens e Rotas</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <a href="cadastro_trens.php">Trens</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <span>Editar <?= sprintf('#TRM-%03d', (int) $id) ?></span>
                </nav>
                <h1 class="ops-page-title">Editar Composição Ferroviária</h1>
            </div>

            <div class="ops-header-actions">
                <a href="cadastro_trens.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Voltar para Trens
                </a>
            </div>
        </div>

        <?php if ($erro !== ''): ?>
            <div class="ops-alert ops-alert-danger" role="alert">
                <div>
                    <strong class="d-block">Revise os dados</strong>
                    <span><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="ops-alert ops-alert-success" role="status">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 w-100">
                    <div>
                        <strong class="d-block">Composição atualizada com sucesso</strong>
                        <span>As alterações já estão refletidas nas listagens de frota e rotas.</span>
                    </div>
                    <a href="trens_trilhos.php" class="ops-btn ops-btn-sm">Ver Frota e Rotas</a>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" class="ops-panel">
            <input type="hidden" name="csrf_token"value="<?= htmlspecialchars(gerarTokenCSRF(), ENT_QUOTES, 'UTF-8') ?>">
            <div class="ops-panel-header">
                <h2 class="ops-panel-title">
                    Ficha de Edição &mdash; <?= sprintf('#TRM-%03d', (int) $id) ?>
                </h2>
            </div>

            <div class="ops-panel-body">
                <div class="mb-3">
                    <label for="nome" class="ops-label">Nome do trem <span class="ops-required" aria-hidden="true">*</span></label>
                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        class="ops-input"
                        maxlength="100"
                        value="<?= htmlspecialchars((string) ($trem['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label for="modelo" class="ops-label">Modelo técnico <span class="ops-required" aria-hidden="true">*</span></label>
                    <input
                        type="text"
                        id="modelo"
                        name="modelo"
                        class="ops-input"
                        maxlength="50"
                        value="<?= htmlspecialchars((string) ($trem['modelo'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        required>
                </div>

                <div class="mb-2">
                    <label for="responsavel_usuario_id" class="ops-label">Operador responsável <span class="ops-required" aria-hidden="true">*</span></label>
                    <select id="responsavel_usuario_id" name="responsavel_usuario_id" class="ops-select" required>
                        <option value="">Selecione um responsável...</option>
                        <?php if ($usuarios): ?>
                            <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                                <option value="<?= (int) $usuario['id'] ?>" <?= ((string) ($trem['responsavel_usuario_id'] ?? '') === (string) $usuario['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?> (<?= $usuario['cargo'] === 'admin' ? 'Admin' : 'Operador' ?>)
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="ops-panel-footer">
                <a href="cadastro_trens.php" class="ops-btn ops-btn-ghost">Cancelar</a>
                <button type="submit" name="atualizar-trem" class="ops-btn ops-btn-primary">
                    Salvar Alterações
                </button>
            </div>
        </form>

    </main>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script defer src="../scripts/script_navbar.js"></script>
</body>

</html>
