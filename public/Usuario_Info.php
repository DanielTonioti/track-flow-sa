<?php
session_start();
require_once "../infra/conn.php";
require_once "components/start.php";

$idSessaoAtual = (int) ($_SESSION['id'] ?? 0);
$ehAdmin = (($_SESSION['cargo'] ?? '') === 'admin');

$idConsulta = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$id = ($idConsulta !== false && $idConsulta !== null && $idConsulta > 0) ? (int) $idConsulta : $idSessaoAtual;
$user = null;

if ($id > 0) {
    $sql = $ehAdmin
        ? "SELECT * FROM funcionario WHERE id = ?"
        : "SELECT id, nome, email, cargo, cidade, bairro, rua, uf, avatar, criado_em FROM funcionario WHERE id = ?";
    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
    }
}

if (!$user && $idSessaoAtual > 0 && $id !== $idSessaoAtual) {
    header("Location: Admin.php");
    exit();
}

$avatarPadrao = "../assets/icons/User.png";
$avatar = $avatarPadrao;

$raizProjeto = realpath(__DIR__ . "/..");
$pastaAssets = realpath(__DIR__ . "/../assets");
$avatarBruto = $user['avatar'] ?? ($_SESSION["avatar"] ?? '');

if (!empty($avatarBruto) && $raizProjeto !== false && $pastaAssets !== false) {
    $valorAvatar = str_replace("\\", "/", trim((string) $avatarBruto));
    $caminhoRelativo = preg_replace('#^(?:\.\./)+#', '', $valorAvatar);
    $caminhoRelativo = preg_replace('#^Assets/#i', 'assets/', $caminhoRelativo);
    $candidatosAvatar = [];

    if (preg_match('#^(?:[A-Za-z]:/|/)#', $valorAvatar)) {
        $candidatosAvatar[] = $valorAvatar;
    } elseif (strpos($caminhoRelativo, 'assets/') === 0) {
        $candidatosAvatar[] = $raizProjeto . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $caminhoRelativo);
    } elseif (strpos($caminhoRelativo, '/') === false && $caminhoRelativo !== '') {
        $candidatosAvatar[] = $pastaAssets . DIRECTORY_SEPARATOR . 'images'
            . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $caminhoRelativo;
    } elseif ($caminhoRelativo !== '') {
        $candidatosAvatar[] = $pastaAssets . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $caminhoRelativo);
    }

    $prefixoAssets = rtrim($pastaAssets, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    foreach ($candidatosAvatar as $candidatoAvatar) {
        $arquivoFisico = realpath($candidatoAvatar);
        if ($arquivoFisico === false || !is_file($arquivoFisico)) {
            continue;
        }

        $comparacaoPrefixo = DIRECTORY_SEPARATOR === '\\'
            ? strncasecmp($arquivoFisico, $prefixoAssets, strlen($prefixoAssets))
            : strncmp($arquivoFisico, $prefixoAssets, strlen($prefixoAssets));

        if ($comparacaoPrefixo === 0) {
            $caminhoDentroAssets = substr($arquivoFisico, strlen($prefixoAssets));
            $avatar = "../assets/" . str_replace(DIRECTORY_SEPARATOR, "/", $caminhoDentroAssets);
            break;
        }
    }
}

$trensVinculados = [];
if ($id > 0) {
    if ($stmtTrens = $db->prepare("SELECT id, nome, modelo FROM trem WHERE responsavel_usuario_id = ? ORDER BY id DESC")) {
        $stmtTrens->bind_param('i', $id);
        $stmtTrens->execute();
        $resTrens = $stmtTrens->get_result();
        while ($tRow = $resTrens->fetch_assoc()) {
            $trensVinculados[] = $tRow;
        }
        $stmtTrens->close();
    }
}

$ehProprioPerfil = ($id === $idSessaoAtual);
$cargoReal = ($user['cargo'] ?? ($_SESSION['cargo'] ?? 'funcionario'));
$nomeUsuario = htmlspecialchars($user['nome'] ?? $_SESSION["usuario"] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
$emailUsuario = htmlspecialchars($user['email'] ?? $_SESSION["email"] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
$telUsuario = '';
$cepUsuario = '';
if ($ehAdmin) {
    $telBruto = (string) ($user['telefone'] ?? '');
    $digitosTel = preg_replace('/\D+/', '', $telBruto);
    if (strlen($digitosTel) === 11) {
        $telFormatado = sprintf('(%s) %s-%s', substr($digitosTel, 0, 2), substr($digitosTel, 2, 5), substr($digitosTel, 7));
    } elseif (strlen($digitosTel) === 10) {
        $telFormatado = sprintf('(%s) %s-%s', substr($digitosTel, 0, 2), substr($digitosTel, 2, 4), substr($digitosTel, 6));
    } else {
        $telFormatado = $telBruto !== '' ? $telBruto : 'Não informado';
    }
    $telUsuario = htmlspecialchars($telFormatado, ENT_QUOTES, 'UTF-8');

    $cepBruto = preg_replace('/\D+/', '', (string) ($user['cep'] ?? ''));
    $cepFormatado = (strlen($cepBruto) === 8)
        ? substr($cepBruto, 0, 5) . '-' . substr($cepBruto, 5)
        : ((string) ($user['cep'] ?? '') !== '' ? (string) $user['cep'] : 'Não informado');
    $cepUsuario = htmlspecialchars($cepFormatado, ENT_QUOTES, 'UTF-8');
}

$cidadeUsuario = htmlspecialchars($user['cidade'] ?? $_SESSION["cidade"] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
$bairroUsuario = htmlspecialchars($user['bairro'] ?? $_SESSION["bairro"] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
$ruaUsuario = htmlspecialchars($user['rua'] ?? $_SESSION["rua"] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
$ufUsuario = htmlspecialchars(strtoupper((string) ($user['uf'] ?? $_SESSION["uf"] ?? '')), ENT_QUOTES, 'UTF-8');
$criadoEm = !empty($user['criado_em']) ? date('d/m/Y H:i', strtotime((string) $user['criado_em'])) : 'Registro ativo';

$pageTitle = $ehProprioPerfil ? 'Meu Perfil' : 'Ficha de Funcionário';
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

    <main class="ops-shell" style="max-width: 1020px;">

        <div class="ops-page-header">
            <div>
                <nav class="ops-breadcrumb" aria-label="Breadcrumb">
                    <a href="hub.php">Dashboard</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <a href="Admin.php">Funcionários</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <span><?= $ehProprioPerfil ? 'Meu Perfil' : sprintf('Ficha #OP-%03d', $id) ?></span>
                </nav>
                <h1 class="ops-page-title"><?= $ehProprioPerfil ? 'Meu Perfil' : 'Consulta de Funcionário' ?></h1>
                <p class="ops-page-subtitle">
                    Dossiê cadastral, localização de base ferroviária e composições vinculadas ao operador.
                </p>
            </div>

            <div class="ops-header-actions">
                <a href="Admin.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-people" aria-hidden="true"></i>Lista de Equipe
                </a>
                <a href="hub.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-speedometer2" aria-hidden="true"></i>Dashboard
                </a>
                <?php if ($ehAdmin && $id > 0): ?>
                    <a href="editar_usuario.php?id=<?= (int) $id ?>" class="ops-btn ops-btn-primary">
                        <i class="bi bi-pencil-square" aria-hidden="true"></i>Editar Cadastro
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <section class="ops-panel h-100 p-4 d-flex flex-column align-items-center text-center justify-content-between">
                    <div class="w-100">
                        <img
                            class="ops-dossier-avatar mb-3"
                            src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>"
                            alt="Foto de perfil de <?= $nomeUsuario ?>"
                            onerror="this.onerror=null; this.src='<?= htmlspecialchars($avatarPadrao, ENT_QUOTES, 'UTF-8') ?>';">

                        <div class="mb-2">
                            <span class="ops-code-badge"><?= sprintf('#OP-%03d', (int) $id) ?></span>
                        </div>

                        <h2 class="h4 brand-font fw-bold text-white mb-2"><?= $nomeUsuario ?></h2>

                        <?php if ($cargoReal === 'admin'): ?>
                            <span class="ops-status-tag ops-status-nominal">
                                <i class="bi bi-shield-lock-fill" aria-hidden="true"></i>ADMINISTRADOR
                            </span>
                        <?php else: ?>
                            <span class="ops-status-tag ops-status-info">
                                <i class="bi bi-person-badge" aria-hidden="true"></i>OPERADOR FERROVIÁRIO
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="w-100 pt-3 mt-4 border-top border-light border-opacity-10 text-start">
                        <div class="d-flex justify-content-between align-items-center small mb-1">
                            <span class="text-steel mono-font">CADASTRO</span>
                            <span class="text-white mono-font"><?= htmlspecialchars($criadoEm, ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small">
                            <span class="text-steel mono-font">TRENS ATRIBUÍDOS</span>
                            <span class="text-volt mono-font fw-bold"><?= count($trensVinculados) ?></span>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-12 col-lg-8">
                <section class="ops-panel mb-3">
                    <div class="ops-panel-header">
                        <h3 class="ops-panel-title">
                            <i class="bi bi-card-list text-cyan" aria-hidden="true"></i>Dados de Contato e Estação Base
                        </h3>
                    </div>
                    <div class="ops-panel-body">
                        <div class="ops-kv-grid">
                            <div class="ops-kv-item">
                                <span class="ops-kv-label">E-mail Corporativo</span>
                                <span class="ops-kv-value mono-font small"><?= $emailUsuario ?></span>
                            </div>
                            <?php if ($ehAdmin): ?>
                                <div class="ops-kv-item">
                                    <span class="ops-kv-label">Telefone de Contato</span>
                                    <span class="ops-kv-value mono-font small"><?= $telUsuario ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="ops-kv-item">
                                <span class="ops-kv-label">Cidade / UF</span>
                                <span class="ops-kv-value"><?= $cidadeUsuario ?><?= $ufUsuario !== '' ? ' / ' . $ufUsuario : '' ?></span>
                            </div>
                            <?php if ($ehAdmin): ?>
                                <div class="ops-kv-item">
                                    <span class="ops-kv-label">CEP</span>
                                    <span class="ops-kv-value mono-font small"><?= $cepUsuario ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="ops-kv-item" style="grid-column: 1 / -1;">
                                <span class="ops-kv-label">Logradouro e Bairro</span>
                                <span class="ops-kv-value"><?= $ruaUsuario ?> &mdash; <?= $bairroUsuario ?></span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="ops-panel">
                    <div class="ops-panel-header">
                        <h3 class="ops-panel-title">
                            <i class="bi bi-train-front text-cyan" aria-hidden="true"></i>Composições Ferroviárias sob Responsabilidade
                        </h3>
                        <span class="ops-status-tag ops-status-neutral"><?= count($trensVinculados) ?> TREM(NS)</span>
                    </div>
                    <div class="ops-panel-body p-0">
                        <?php if (!empty($trensVinculados)): ?>
                            <div class="table-responsive">
                                <table class="ops-table" aria-label="Trens vinculados ao operador">
                                    <thead>
                                        <tr>
                                            <th scope="col">Código</th>
                                            <th scope="col">Composição</th>
                                            <th scope="col">Modelo</th>
                                            <?php if ($ehAdmin): ?>
                                                <th scope="col" class="text-end">Ação</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($trensVinculados as $tr): ?>
                                            <tr>
                                                <td><span class="ops-code-badge"><?= sprintf('#TRM-%03d', (int) $tr['id']) ?></span></td>
                                                <td class="fw-semibold text-white"><?= htmlspecialchars((string) $tr['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td class="text-steel mono-font small"><?= htmlspecialchars((string) $tr['modelo'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <?php if ($ehAdmin): ?>
                                                    <td class="text-end">
                                                        <a href="editar_trem.php?id=<?= (int) $tr['id'] ?>" class="ops-btn ops-btn-ghost ops-btn-sm">
                                                            <i class="bi bi-pencil-square" aria-hidden="true"></i>Gerenciar
                                                        </a>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="ops-empty-state py-4">
                                <p class="small text-steel mb-0">Nenhum trem vinculado diretamente a este operador no momento.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
</body>

</html>
