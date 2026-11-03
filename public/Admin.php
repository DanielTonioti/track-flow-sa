<?php
session_start();
include_once("../infra/conn.php");
include("components/start.php");

$ehAdmin = (($_SESSION['cargo'] ?? '') === 'admin');

$sql = $ehAdmin
    ? "SELECT id, nome, email, telefone, cidade, uf, cargo FROM funcionario ORDER BY FIELD(cargo, 'admin', 'funcionario'), nome ASC"
    : "SELECT id, nome, email, cidade, uf, cargo FROM funcionario ORDER BY FIELD(cargo, 'admin', 'funcionario'), nome ASC";
$resFuncionarios = $db->query($sql);

$funcionarios = [];
$totalAdmins = 0;
$totalOperadores = 0;

if ($resFuncionarios) {
    while ($row = $resFuncionarios->fetch_assoc()) {
        $funcionarios[] = $row;
        if (($row['cargo'] ?? '') === 'admin') {
            $totalAdmins++;
        } else {
            $totalOperadores++;
        }
    }
}

function formatarTelefoneOperacional(?string $tel): string
{
    $digitos = preg_replace('/\D+/', '', (string) $tel);
    if (strlen($digitos) === 11) {
        return sprintf('(%s) %s-%s', substr($digitos, 0, 2), substr($digitos, 2, 5), substr($digitos, 7));
    }
    if (strlen($digitos) === 10) {
        return sprintf('(%s) %s-%s', substr($digitos, 0, 2), substr($digitos, 2, 4), substr($digitos, 6));
    }
    return $tel !== null && $tel !== '' ? $tel : '—';
}

$pageTitle = 'Funcionários e Credenciais';
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
                    <span>Funcionários</span>
                </nav>
                <h1 class="ops-page-title">Funcionários</h1>
                <p class="ops-page-subtitle">
                    Consulte administradores e funcionários cadastrados na malha ferroviária.
                </p>
            </div>

            <div class="ops-header-actions">
                <a href="hub.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Dashboard
                </a>
                <?php if ($ehAdmin): ?>
                    <a href="cadastro_usuario.php" class="ops-btn ops-btn-primary" id="admin-button">
                        <i class="bi bi-person-plus-fill" aria-hidden="true"></i>Adicionar Funcionário
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <section class="ops-panel" aria-labelledby="titulo-tabela-equipe">
            <div class="ops-panel-header">
                <h2 id="titulo-tabela-equipe" class="ops-panel-title">
                    <i class="bi bi-person-lines-fill text-cyan" aria-hidden="true"></i>Quadro de Funcionários Cadastrados
                </h2>
                <span id="contador-usuarios" class="ops-status-tag ops-status-neutral">
                    <?= count($funcionarios) ?> REGISTRO(S)
                </span>
            </div>

            <div class="ops-toolbar">
                <div class="ops-search-wrap">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input
                        type="search"
                        id="busca-funcionario"
                        class="ops-search-input"
                        placeholder="Filtrar por nome, e-mail ou cidade..."
                        aria-label="Filtrar funcionários por nome, e-mail ou cidade">
                </div>

                <div class="ops-filter-group" role="group" aria-label="Filtrar por nível de acesso">
                    <button type="button" class="ops-filter-pill active" data-role-filter="todos" aria-pressed="true">
                        Todos (<?= count($funcionarios) ?>)
                    </button>
                    <button type="button" class="ops-filter-pill" data-role-filter="admin" aria-pressed="false">
                        Administradores (<?= $totalAdmins ?>)
                    </button>
                    <button type="button" class="ops-filter-pill" data-role-filter="funcionario" aria-pressed="false">
                        Funcionários (<?= $totalOperadores ?>)
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="ops-table" id="tabela-funcionarios" aria-label="Lista de funcionários e administradores">
                    <thead>
                        <tr>
                            <th scope="col">Matrícula</th>
                            <th scope="col">Nome / Cidade</th>
                            <th scope="col">E-mail Corporativo</th>
                            <?php if ($ehAdmin): ?>
                                <th scope="col" class="text-end">Telefone</th>
                            <?php endif; ?>
                            <th scope="col">Nível de Acesso</th>
                            <th scope="col" class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($funcionarios)): ?>
                            <?php foreach ($funcionarios as $pessoa):
                                $idPessoa = (int) ($pessoa['id'] ?? 0);
                                $cargoPessoa = ($pessoa['cargo'] ?? 'funcionario') === 'admin' ? 'admin' : 'funcionario';
                                $cidadeUf = trim((string) ($pessoa['cidade'] ?? ''));
                                if (!empty($pessoa['uf'])) {
                                    $cidadeUf .= ($cidadeUf !== '' ? ' / ' : '') . strtoupper(trim((string) $pessoa['uf']));
                                }
                                $textoBusca = mb_strtolower(
                                    ($pessoa['nome'] ?? '') . ' ' . ($pessoa['email'] ?? '') . ' ' . $cidadeUf,
                                    'UTF-8'
                                );
                            ?>
                                <tr data-role="<?= htmlspecialchars($cargoPessoa, ENT_QUOTES, 'UTF-8') ?>"
                                    data-search="<?= htmlspecialchars($textoBusca, ENT_QUOTES, 'UTF-8') ?>">
                                    <td>
                                        <span class="ops-code-badge"><?= sprintf('#OP-%03d', $idPessoa) ?></span>
                                    </td>
                                    <td>
                                        <strong class="text-white d-block"><?= htmlspecialchars((string) ($pessoa['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
                                        <span class="text-steel small">
                                            <i class="bi bi-geo-alt me-1" aria-hidden="true"></i><?= htmlspecialchars($cidadeUf !== '' ? $cidadeUf : 'Base não informada', ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td class="mono-font small text-steel">
                                        <?= htmlspecialchars((string) ($pessoa['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                    <?php if ($ehAdmin): ?>
                                        <td class="text-end mono-font small">
                                            <?= htmlspecialchars(formatarTelefoneOperacional($pessoa['telefone'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                    <?php endif; ?>
                                    <td>
                                        <?php if ($cargoPessoa === 'admin'): ?>
                                            <span class="ops-status-tag ops-status-nominal">
                                                <i class="bi bi-shield-lock-fill" aria-hidden="true"></i>ADMIN
                                            </span>
                                        <?php else: ?>
                                            <span class="ops-status-tag ops-status-info">
                                                <i class="bi bi-person-badge" aria-hidden="true"></i>FUNCIONÁRIO
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex flex-wrap justify-content-end gap-1">
                                            <a href="Usuario_Info.php?id=<?= $idPessoa ?>"
                                                class="ops-btn ops-btn-ghost ops-btn-sm"
                                                title="Consultar ficha completa de <?= htmlspecialchars((string) ($pessoa['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                                <i class="bi bi-eye" aria-hidden="true"></i>Consultar
                                            </a>
                                            <?php if ($ehAdmin): ?>
                                                <a href="editar_usuario.php?id=<?= $idPessoa ?>"
                                                    class="ops-btn ops-btn-sm"
                                                    title="Editar cadastro de <?= htmlspecialchars((string) ($pessoa['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                                    <i class="bi bi-pencil-square" aria-hidden="true"></i>Editar
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= $ehAdmin ? 6 : 5 ?>">
                                    <div class="ops-empty-state">
                                        <span class="ops-empty-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                                        <p class="mb-2 fw-semibold text-white">Nenhum funcionário cadastrado.</p>
                                        <?php if ($ehAdmin): ?>
                                            <a href="cadastro_usuario.php" class="ops-btn ops-btn-primary ops-btn-sm">
                                                <i class="bi bi-person-plus" aria-hidden="true"></i>Cadastrar primeiro funcionário
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="estado-busca-vazia" class="ops-empty-state d-none">
                <span class="ops-empty-icon"><i class="bi bi-search" aria-hidden="true"></i></span>
                <p class="mb-1 fw-semibold text-white">Nenhum funcionário corresponde ao filtro aplicado.</p>
                <p class="small text-steel mb-0">Limpe o termo de pesquisa ou altere o filtro de nível de acesso.</p>
            </div>
        </section>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputBusca = document.getElementById('busca-funcionario');
            const botoesFiltro = document.querySelectorAll('[data-role-filter]');
            const linhas = document.querySelectorAll('#tabela-funcionarios tbody tr[data-role]');
            const contador = document.getElementById('contador-usuarios');
            const vazioBox = document.getElementById('estado-busca-vazia');

            let roleAtiva = 'todos';

            function aplicarFiltros() {
                const termo = (inputBusca ? inputBusca.value : '').trim().toLowerCase();
                let visiveis = 0;

                linhas.forEach(function (tr) {
                    const roleLinha = tr.getAttribute('data-role') || '';
                    const buscaLinha = tr.getAttribute('data-search') || '';
                    const bateRole = (roleAtiva === 'todos' || roleLinha === roleAtiva);
                    const bateTermo = (termo === '' || buscaLinha.indexOf(termo) !== -1);

                    if (bateRole && bateTermo) {
                        tr.classList.remove('d-none');
                        visiveis++;
                    } else {
                        tr.classList.add('d-none');
                    }
                });

                if (contador) {
                    contador.textContent = visiveis + ' REGISTRO(S)';
                }
                if (vazioBox && linhas.length > 0) {
                    vazioBox.classList.toggle('d-none', visiveis > 0);
                }
            }

            if (inputBusca) {
                inputBusca.addEventListener('input', aplicarFiltros);
            }

            botoesFiltro.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    roleAtiva = btn.getAttribute('data-role-filter') || 'todos';
                    botoesFiltro.forEach(function (b) {
                        const ativo = (b === btn);
                        b.classList.toggle('active', ativo);
                        b.setAttribute('aria-pressed', ativo ? 'true' : 'false');
                    });
                    aplicarFiltros();
                });
            });
        });
    </script>
</body>

</html>
