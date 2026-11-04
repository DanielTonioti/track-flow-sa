<?php
session_start();
require_once('components/csrf.php');
validarTokenCSRF();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

$erro = '';
$sucesso = '';

if (isset($_GET['atualizado'])) {
    $sucesso = 'Segmento de trilho atualizado com sucesso.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['acao']) && $_POST['acao'] === 'cadastrar_trilho') {
        $nome = trim($_POST['Numeracao'] ?? $_POST['nome'] ?? '');
        $localizacao = trim($_POST['EstacaoOrigem'] ?? $_POST['localizacao'] ?? '');
        $tipo = trim($_POST['tipo'] ?? 'Bitola Larga');

        if ($nome === '' || mb_strlen($nome) < 2 || mb_strlen($nome) > 100) {
            $erro = 'Informe o código/nome do trilho (2 a 100 caracteres).';
        } elseif ($localizacao === '' || mb_strlen($localizacao) < 2 || mb_strlen($localizacao) > 150) {
            $erro = 'Informe a localização ou quilometragem do trecho (2 a 150 caracteres).';
        } elseif ($tipo === '' || mb_strlen($tipo) > 50) {
            $erro = 'Selecione um tipo de via válido.';
        } else {
            $stmt = $db->prepare('INSERT INTO trilho (nome, localizacao, tipo) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $nome, $localizacao, $tipo);
            if ($stmt->execute()) {
                $sucesso = 'Segmento de trilho cadastrado na via permanente.';
            } else {
                $erro = 'Erro ao cadastrar o trilho no banco de dados.';
            }
            $stmt->close();
        }
    }

    if (isset($_POST['acao']) && $_POST['acao'] === 'excluir_trilho') {
        $idExcluir = (int) ($_POST['id_trilho'] ?? 0);
        if ($idExcluir > 0) {
            $chk = $db->prepare('SELECT id FROM sensor WHERE trilho_id = ? LIMIT 1');
            $chk->bind_param('i', $idExcluir);
            $chk->execute();
            $possuiSensor = $chk->get_result()->num_rows > 0;
            $chk->close();

            if ($possuiSensor) {
                $erro = 'Não é possível excluir este trilho: há sensores de via vinculados a ele.';
            } else {
                $del = $db->prepare('DELETE FROM trilho WHERE id = ?');
                $del->bind_param('i', $idExcluir);
                $del->execute();
                $del->close();
                $sucesso = 'Segmento de trilho removido com sucesso.';
            }
        }
    }
}

$trilhos = $db->query('SELECT id, nome, localizacao, tipo FROM trilho ORDER BY id DESC');
$pageTitle = 'Trilhos e Linhas da Via Permanente';
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
                    <a href="trens_trilhos.php">Trens e Rotas</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <span>Trilhos e Linhas</span>
                </nav>
                <h1 class="ops-page-title">Via Permanente — Trilhos e Linhas</h1>
            </div>

            <div class="ops-header-actions">
                <a href="trens_trilhos.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Frota e Rotas
                </a>
                <a href="Sensor.php" class="ops-btn">
                    <i class="bi bi-cpu" aria-hidden="true"></i>Vincular Sensor
                </a>
            </div>
        </div>

        <?php if ($erro !== ''): ?>
            <div class="ops-alert ops-alert-danger" role="alert">
                <div>
                    <strong class="d-block">Operação não realizada</strong>
                    <span><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($sucesso !== ''): ?>
            <div class="ops-alert ops-alert-success" role="status">
                <div>
                    <strong class="d-block">Via permanente atualizada</strong>
                    <span><?= htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="row g-3 align-items-start">

            <div class="col-12 col-lg-5">
                <form id="CadastroTremForm" method="POST" class="ops-panel">
                     <input type="hidden" name="csrf_token"value="<?= htmlspecialchars(gerarTokenCSRF(), ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="acao" value="cadastrar_trilho">

                    <div class="ops-panel-header">
                        <h2 class="ops-panel-title">
                            <i class="bi bi-distribute-vertical text-cyan" aria-hidden="true"></i>Cadastrar Segmento de Trilho
                        </h2>
                    </div>

                    <div class="ops-panel-body">
                        <div class="mb-3">
                            <label class="ops-label" for="Numeracao">Código / Nome do trilho <span class="ops-required" aria-hidden="true">*</span></label>
                            <input class="ops-input mono-font" type="text" id="Numeracao" name="Numeracao" maxlength="100" placeholder="Ex.: TRLH-N289T - KM 12" required>
                        </div>

                        <div class="mb-3">
                            <label class="ops-label" for="EstacaoOrigem">Localização / Pátio <span class="ops-required" aria-hidden="true">*</span></label>
                            <input class="ops-input" type="text" id="EstacaoOrigem" name="EstacaoOrigem" maxlength="150" placeholder="Ex.: Curva Sul — Subdivisão Joinville" required>
                        </div>

                        <div class="mb-2">
                            <label class="ops-label" for="tipo">Especificação da via</label>
                            <select class="ops-select" id="tipo" name="tipo">
                                <option value="Bitola Larga (1600 mm)">Bitola Larga (1600 mm)</option>
                                <option value="Bitola Métrica (1000 mm)">Bitola Métrica (1000 mm)</option>
                                <option value="AMV / Aparelho de Mudança de Via">AMV / Aparelho de Mudança de Via</option>
                                <option value="Ponte / Obra de Arte Especial">Ponte / Obra de Arte Especial</option>
                            </select>
                        </div>
                    </div>

                    <div class="ops-panel-footer justify-content-end">
                        <button type="submit" class="ops-btn ops-btn-primary w-100">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>Cadastrar Trilho
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-12 col-lg-7">
                <section class="ops-panel" aria-labelledby="TituloTrilhosCadastrados">
                    <div class="ops-panel-header">
                        <h2 id="TituloTrilhosCadastrados" class="ops-panel-title">
                            <i class="bi bi-list-ul text-cyan" aria-hidden="true"></i>Trilhos Cadastrados
                        </h2>
                        <span class="ops-status-tag ops-status-neutral">
                            <?= $trilhos ? (int) $trilhos->num_rows : 0 ?> Segmento(s)
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="ops-table" aria-label="Segmentos de trilho cadastrados">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Trilho / Especificação</th>
                                    <th scope="col">Localização</th>
                                    <th scope="col" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($trilhos && $trilhos->num_rows > 0): ?>
                                    <?php while ($trilho = $trilhos->fetch_assoc()):
                                        $idTr = (int) $trilho['id'];
                                        $nomeTr = (string) ($trilho['nome'] ?? '');
                                        $locTr = (string) ($trilho['localizacao'] ?? 'Não informada');
                                        $tipoTr = (string) ($trilho['tipo'] ?? 'Via Permanente');
                                    ?>
                                        <tr>
                                            <td><span class="ops-code-badge"><?= sprintf('#VIA-%03d', $idTr) ?></span></td>
                                            <td>
                                                <strong class="text-white mono-font d-block"><?= htmlspecialchars($nomeTr, ENT_QUOTES, 'UTF-8') ?></strong>
                                                <span class="text-steel small"><?= htmlspecialchars($tipoTr, ENT_QUOTES, 'UTF-8') ?></span>
                                            </td>
                                            <td class="small text-steel"><?= htmlspecialchars($locTr, ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-1">
                                                    <button
                                                        type="button"
                                                        class="ops-btn ops-btn-sm btn-editar-trilho"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#ModalEditarTrilho"
                                                        data-id="<?= $idTr ?>"
                                                        data-nome="<?= htmlspecialchars($nomeTr, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-local="<?= htmlspecialchars($locTr, ENT_QUOTES, 'UTF-8') ?>">
                                                        Editar
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="ops-btn ops-btn-danger-outline ops-btn-sm btn-excluir-trilho"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#ModalExcluirTrilho"
                                                        data-id="<?= $idTr ?>"
                                                        data-nome="<?= htmlspecialchars($nomeTr, ENT_QUOTES, 'UTF-8') ?>">
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
                                                <span class="ops-empty-icon"><i class="bi bi-distribute-vertical" aria-hidden="true"></i></span>
                                                <p class="mb-0 fw-semibold text-white">Nenhum segmento de trilho cadastrado.</p>
                                                <span class="small text-steel">Cadastre trechos de via para alocar sensores fixos de trilho.</span>
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

    <div class="modal fade ops-modal" id="ModalEditarTrilho" tabindex="-1" aria-labelledby="TituloModalEditarTrilho" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="atualizar_trilho.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5 fw-bold text-white" id="TituloModalEditarTrilho">Editar Segmento de Trilho</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editTrilhoId" value="">
                    <div class="mb-3">
                        <label class="ops-label" for="editTrilhoNome">Código / Nome <span class="ops-required" aria-hidden="true">*</span></label>
                        <input type="text" class="ops-input mono-font" name="nome" id="editTrilhoNome" required>
                    </div>
                    <div class="mb-2">
                        <label class="ops-label" for="editTrilhoLocal">Localização <span class="ops-required" aria-hidden="true">*</span></label>
                        <input type="text" class="ops-input" name="localizacao" id="editTrilhoLocal" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="ops-btn ops-btn-ghost" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="ops-btn ops-btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade ops-modal" id="ModalExcluirTrilho" tabindex="-1" aria-labelledby="TituloModalExcluirTrilho" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5 fw-bold text-white d-flex align-items-center gap-2" id="TituloModalExcluirTrilho">
                        <i class="bi bi-exclamation-octagon-fill text-danger" aria-hidden="true"></i>Excluir Trilho
                    </h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Tem certeza de que deseja excluir o segmento <strong id="delTrilhoNome" class="text-white"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="ops-btn ops-btn-ghost" data-bs-dismiss="modal">Voltar</button>
                    <form method="POST" class="m-0">
                        <input type="hidden" name="acao" value="excluir_trilho">
                        <input type="hidden" name="id_trilho" id="delTrilhoId" value="">
                        <button type="submit" id="ConfirmarExclusaoTrilho" class="ops-btn ops-btn-danger">Confirmar Exclusão</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
    <script src="../scripts/script_linhas.js"></script>
    <script src="../scripts/pagination.js"></script>
</body>

</html>
