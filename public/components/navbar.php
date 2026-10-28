<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Avatar padrão
$avatarPadrao = "../assets/icons/User.png";
$avatar = $avatarPadrao;

// Esta navbar está em public/components/, então a raiz fica 2 níveis acima.
$raizProjeto = realpath(__DIR__ . "/../..");
$pastaAssets = realpath(__DIR__ . "/../../assets");

if (!empty($_SESSION["avatar"]) && $raizProjeto !== false && $pastaAssets !== false) {
    $valorAvatar = str_replace("\\", "/", trim((string) $_SESSION["avatar"]));
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

$usuarioEhAdmin = (($_SESSION['cargo'] ?? '') === 'admin');
$paginaAtual = basename($_SERVER['PHP_SELF'] ?? '');
?>

<nav class="menu-nav navbar navbar-dark" aria-label="Navegação Principal TrackFlow">
    <div class="container-fluid px-3 px-lg-4">

        <!-- LOGO & BRAND -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="hub.php">
            <img src="../assets/logo/logo-track-flow.png" alt="Logo TrackFlow" class="hub-icon" width="36" height="36">
            <span class="fw-bold fs-4 text-white brand-font tracking-tight mb-0">TrackFlow</span>
        </a>

        <!-- ÁREA DIREITA DO CABEÇALHO -->
        <div class="d-flex align-items-center gap-2">

            <!-- BOTÃO MENU HAMBÚRGUER -->
            <button class="navbar-toggler border-0 shadow-none px-2 py-1" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Abrir menu de navegação">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- NOME DO USUÁRIO -->
            <div class="d-none d-sm-flex align-items-center justify-content-center me-1">
                <span id="usuario-logado" class="nav-nome text-white small fw-bold">
                    <?= htmlspecialchars($_SESSION["usuario"] ?? 'Operador', ENT_QUOTES, "UTF-8") ?>
                </span>
            </div>

            <!-- AVATAR INTERATIVO -->
            <div class="perfil" role="button" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-label="Menu do usuário">
                <div class="imgCx">
                    <img src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar do usuário"
                        onerror="this.onerror=null; this.src='<?= htmlspecialchars($avatarPadrao, ENT_QUOTES, 'UTF-8') ?>';">
                </div>
            </div>

            <!-- MENU DROPDOWN DO USUÁRIO -->
            <div id="menu_user" class="menu" role="menu">
                <div>
                    <p><a href="Usuario_Info.php"><i class="bi bi-person-gear me-2 text-blue"></i>Perfil</a></p>
                    <p class="Menu_Deslogar"><a href="logout.php"><i class="bi bi-box-arrow-right me-2 text-danger"></i>Deslogar</a></p>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- MENU LATERAL OFFCANVAS
     Posicionado FORA do elemento <nav> para ancoragem direta ao viewport (100vh). -->
<div class="offcanvas offcanvas-start trackflow-offcanvas" tabindex="-1" id="offcanvasDarkNavbar"
    aria-labelledby="offcanvasDarkNavbarLabel" data-bs-scroll="true">

    <div class="offcanvas-header border-bottom border-light border-opacity-10 py-3 px-4">
        <div class="d-flex align-items-center gap-2">
            <img src="../assets/logo/logo-track-flow.png" alt="Logo TrackFlow" width="32" height="32" class="hub-icon">
            <h3 class="offcanvas-title brand-font fs-4 text-white mb-0" id="offcanvasDarkNavbarLabel">
                TrackFlow
            </h3>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Fechar menu"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column justify-content-between p-3">
        <ul class="navbar-nav pe-2 gap-1">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 <?= $paginaAtual === 'hub.php' ? 'active' : '' ?>" href="hub.php">
                    <i class="bi bi-speedometer2 text-blue fs-5" aria-hidden="true"></i>
                    <span>Dashboard // CCO</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 <?= $paginaAtual === 'Admin.php' ? 'active' : '' ?>" href="Admin.php">
                    <i class="bi bi-people-fill text-blue fs-5" aria-hidden="true"></i>
                    <span>Funcionários</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 <?= $paginaAtual === 'Relatories.php' ? 'active' : '' ?>" href="Relatories.php">
                    <i class="bi bi-file-earmark-bar-graph-fill text-blue fs-5" aria-hidden="true"></i>
                    <span>Relatórios</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 <?= in_array($paginaAtual, ['Sensor.php', 'Sensor_Data.php', 'sensor_delete.php', 'Sensor_Manage.php']) ? 'active' : '' ?>" href="Sensor_Data.php">
                    <i class="bi bi-cpu-fill text-blue fs-5" aria-hidden="true"></i>
                    <span>Sensores da Malha</span>
                </a>
            </li>
            <?php if ($usuarioEhAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 <?= in_array($paginaAtual, ['trens_trilhos.php', 'cadastro_trens.php', 'cadastro_rotas.php', 'cadastro_linhas.php']) ? 'active' : '' ?>" href="trens_trilhos.php">
                        <i class="bi bi-train-front-fill text-blue fs-5" aria-hidden="true"></i>
                        <span>Trilhos e Trens</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <div class="pt-3 border-top border-light border-opacity-10">
            <div class="d-flex align-items-center gap-2 px-2 py-2 rounded-3" style="background-color: rgba(255, 255, 255, 0.03);">
                <div class="perfil-mini flex-shrink-0">
                    <img src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="" width="38" height="38" class="rounded-circle object-fit-cover"
                        onerror="this.onerror=null; this.src='<?= htmlspecialchars($avatarPadrao, ENT_QUOTES, 'UTF-8') ?>';">
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <strong class="d-block text-white small text-truncate"><?= htmlspecialchars($_SESSION["usuario"] ?? 'Operador', ENT_QUOTES, "UTF-8") ?></strong>
                    <span class="text-steel d-block" style="font-size: 0.75rem;"><?= $usuarioEhAdmin ? 'Administrador' : 'Funcionário' ?></span>
                </div>
                <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;" title="Deslogar">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
