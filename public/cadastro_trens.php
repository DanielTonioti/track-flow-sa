<?php
session_start();
require_once('components/csrf.php');
validarTokenCSRF();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();


$erro = '';
$sucesso = isset($_GET['excluido']);
$mensagemSucesso = $sucesso ? 'Trem removido com sucesso da malha.' : '';
$responsavelId = '';


if (isset($_GET['excluir_erro'])) {
    if ($_GET['excluir_erro'] === 'sensor') {
        $erro = 'Não foi possível excluir o trem: há sensores embarcados vinculados a esta composição. Remova ou realoque os sensores primeiro.';
    } else {
        $erro = 'Não foi possível excluir o trem: há pelo menos uma rota dependente desta composição. Exclua ou reatribua a rota antes de continuar.';
    }
}


$nomeInput = '';
$modeloInput = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar-trem'])) {
    $nomeInput = trim($_POST['nome'] ?? '');
    $modeloInput = trim($_POST['modelo'] ?? '');
    $responsavelId = trim((string) ($_POST['responsavel_usuario_id'] ?? ''));


    if ($nomeInput === '') {
        $erro = 'O nome do trem é obrigatório.';
    } elseif (mb_strlen($nomeInput) < 2 || mb_strlen($nomeInput) > 100) {
        $erro = 'O nome do trem deve ter entre 2 e 100 caracteres.';
    } elseif ($modeloInput === '') {
        $erro = 'O modelo do trem é obrigatório.';
    } elseif (mb_strlen($modeloInput) < 2 || mb_strlen($modeloInput) > 50) {
        $erro = 'O modelo do trem deve ter entre 2 e 50 caracteres.';
    } elseif ($responsavelId === '' || !ctype_digit($responsavelId) || (int) $responsavelId <= 0) {
        $erro = 'Selecione um operador responsável válido para a composição.';
    }


    if ($erro === '') {
        $responsavel = (int) $responsavelId;


        $checkResp = $db->prepare('SELECT id FROM funcionario WHERE id = ?');
        $checkResp->bind_param('i', $responsavel);
        $checkResp->execute();
        if ($checkResp->get_result()->num_rows === 0) {
            $erro = 'O funcionário selecionado não foi encontrado.';
        }
        $checkResp->close();
    }


    if ($erro === '') {
        $stmt = $db->prepare('INSERT INTO trem (nome, modelo, responsavel_usuario_id) VALUES (?, ?, ?)');
        $stmt->bind_param('ssi', $nomeInput, $modeloInput, $responsavel);


        if ($stmt->execute()) {
            $sucesso = true;
            $mensagemSucesso = 'Trem cadastrado com sucesso e disponibilizado para rotas e sensores.';
            $nomeInput = '';
            $modeloInput = '';
            $responsavelId = '';
        } else {
            $erro = 'Erro ao cadastrar o trem no banco de dados.';
        }


        $stmt->close();
    }
}


$usuarios = $db->query('SELECT id, nome, cargo FROM funcionario ORDER BY nome ASC');
$trens = $db->query(
    "SELECT t.*, f.nome AS responsavel_nome
     FROM trem t
     LEFT JOIN funcionario f ON f.id = t.responsavel_usuario_id
     ORDER BY t.id DESC"
);


$pageTitle = 'Cadastrar e Gerenciar Trens';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">


<head>
    <?php include 'components/head.php'; ?>
</head>


<body class="ops-app-body">
    <header>
        <?php include 'components/navbar.php'; ?>
    </header>


    <main class="ops-shell">


        <div class="ops-page-header">
            <div>
                <nav class="ops-breadcrumb" aria-label="Breadcrumb">
                    <a href="hub.php">Dashboard</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <a href="trens_trilhos.php">Trens e Rotas</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <span>Gerenciar Trens</span>
                </nav>
                <h1 class="ops-page-title">Cadastro de Trens</h1>
            </div>


            <div class="ops-header-actions">
                <a href="trens_trilhos.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Visão Geral
                </a>
                <a href="cadastro_rotas.php" class="ops-btn">
                    Ir para Rotas
                </a>
            </div>
        </div>


        <?php if ($erro !== ''): ?>
            <div class="ops-alert ops-alert-danger" role="alert">
                <i class="bi bi-exclamation-octagon-fill" aria-hidden="true"></i>
                <div>
                    <strong class="d-block">Operação bloqueada</strong>
                    <span><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        <?php endif; ?>


        <?php if ($sucesso && $mensagemSucesso !== ''): ?>
            <div class="ops-alert ops-alert-success" role="status">
                <div>
                    <strong class="d-block">Frota atualizada</strong>
                    <span><?= htmlspecialchars($mensagemSucesso, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        <?php endif; ?>


        <div class="row g-3 align-items-start">


            <div class="col-12 col-lg-5">
                <form method="POST" class="ops-panel">
                    <input type="hidden" name="csrf_token"
                        value="<?= htmlspecialchars(gerarTokenCSRF(), ENT_QUOTES, 'UTF-8') ?>">
                    <div class="ops-panel-body">
                        <div class="mb-3">
                            <label for="nome" class="ops-label">Identificação do trem <span class="ops-required"
                                    aria-hidden="true">*</span></label>
                            <input type="text" id="nome" name="nome" class="ops-input" maxlength="100"
                                placeholder="Ex.: Locomotiva Serra Sul C-01"
                                value="<?= htmlspecialchars($nomeInput, ENT_QUOTES, 'UTF-8') ?>" required>
                            <span class="ops-help">Nome ou código do trem (2 a 100 caracteres).</span>
                        </div>


                        <div class="mb-3">
                            <label for="modelo" class="ops-label">Modelo técnico <span class="ops-required"
                                    aria-hidden="true">*</span></label>
                            <input type="text" id="modelo" name="modelo" class="ops-input" maxlength="50"
                                placeholder="Ex.: GE AC44i Diesel-Elétrica"
                                value="<?= htmlspecialchars($modeloInput, ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>


                        <div class="mb-2">
                            <label for="responsavel_usuario_id" class="ops-label">Operador responsável <span
                                    class="ops-required" aria-hidden="true">*</span></label>
                            <select id="responsavel_usuario_id" name="responsavel_usuario_id" class="ops-select"
                                required>
                                <option value="">Selecione um responsável...</option>
                                <?php if ($usuarios): ?>
                                    <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                                        <option value="<?= (int) $usuario['id'] ?>" <?= ($responsavelId !== '' && (string) $responsavelId === (string) $usuario['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?>
                                            (<?= $usuario['cargo'] === 'admin' ? 'Admin' : 'Operador' ?>)
                                        </option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                            <span class="ops-help">Todo trem deve estar vinculado a um funcionário cadastrado.</span>
                        </div>
                    </div>


                    <div class="ops-panel-footer justify-content-end">
                        <button type="submit" name="cadastrar-trem" class="ops-btn ops-btn-primary w-100">
                            Cadastrar Trem
                        </button>
                    </div>
                </form>
            </div>


            <div class="col-12 col-lg-7">
                <section class="ops-panel" aria-labelledby="titulo-lista-trens">
                    <div class="ops-panel-header">
                        <h2 id="titulo-lista-trens" class="ops-panel-title">
                            Trens Cadastrados
                        </h2>
                        <span class="ops-status-tag ops-status-neutral">
                            <?= $trens ? (int) $trens->num_rows : 0 ?> Registro(s)
                        </span>
                    </div>


                    <div class="table-responsive">
                        <table class="ops-table" aria-label="Tabela de trens cadastrados">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Trem / Modelo</th>
                                    <th scope="col">Responsável</th>
                                    <th scope="col" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($trens && $trens->num_rows > 0): ?>
                                    <?php while ($trem = $trens->fetch_assoc()):
                                        $idT = (int) $trem['id'];
                                        $nomeT = (string) ($trem['nome'] ?? '');
                                        ?>
                                        <tr>
                                            <td><span class="ops-code-badge"><?= sprintf('#TRM-%03d', $idT) ?></span></td>
                                            <td>
                                                <strong
                                                    class="text-white d-block"><?= htmlspecialchars($nomeT, ENT_QUOTES, 'UTF-8') ?></strong>
                                                <span
                                                    class="mono-font text-steel small"><?= htmlspecialchars((string) ($trem['modelo'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                                            </td>
                                            <td class="small text-steel">
                                                <?= htmlspecialchars((string) ($trem['responsavel_nome'] ?? 'Sem responsável'), ENT_QUOTES, 'UTF-8') ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-1">
                                                    <a href="editar_trem.php?id=<?= $idT ?>" class="ops-btn ops-btn-sm">
                                                        Editar
                                                    </a>
                                                    <button type="button"
                                                        class="ops-btn ops-btn-danger-outline ops-btn-sm btn-preparar-exclusao-trem"
                                                        data-bs-toggle="modal" data-bs-target="#modalExcluirTrem"
                                                        data-trem-id="<?= $idT ?>"
                                                        data-trem-nome="<?= htmlspecialchars($nomeT, ENT_QUOTES, 'UTF-8') ?>">
                                                        Excluir
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="ops-empty-state">
                                                <span class="ops-empty-icon"><i class="bi bi-train-front"
                                                        aria-hidden="true"></i></span>
                                                <p class="mb-0 fw-semibold text-white">Nenhum trem cadastrado até o momento.
                                                </p>
                                                <span class="small text-steel">Preencha o formulário ao lado.</span>
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


    <div class="modal fade ops-modal" id="modalExcluirTrem" tabindex="-1" aria-labelledby="modalExcluirTremLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5 fw-bold text-white d-flex align-items-center gap-2"
                        id="modalExcluirTremLabel">
                        <i class="bi bi-exclamation-octagon-fill text-danger" aria-hidden="true"></i>Excluir Composição
                    </h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">
                        Confirma a exclusão do trem <strong id="nomeTremModal" class="text-white"></strong>?
                    </p>
                    <p class="small text-steel mb-0">
                        Trens vinculados a rotas ativas ou sensores embarcados não serão excluídos até que os vínculos
                        sejam removidos.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="ops-btn ops-btn-ghost" data-bs-dismiss="modal">Cancelar</button>
                    <form action="excluir_trem.php" method="POST" class="m-0">
                        <input type="hidden" name="csrf_token"
                            value="<?= htmlspecialchars(gerarTokenCSRF(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="id" id="inputExcluirTremId" value="">
                        <button type="submit" class="ops-btn ops-btn-danger">
                            Confirmar Exclusão
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script defer src="../scripts/script_navbar.js"></script>
    <script defer src="../scripts/script_cadastro_trens.js"></script>
    <script defer src="../scripts/pagination.js"></script>
</body>


</html>