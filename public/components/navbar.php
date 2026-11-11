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


$navGrupoDashboard = ($paginaAtual === 'hub.php');
$navGrupoUsuarios = in_array($paginaAtual, ['Admin.php', 'cadastro_usuario.php', 'editar_usuario.php', 'Usuario_Info.php'], true);
$navGrupoFrota = in_array($paginaAtual, ['trens_trilhos.php', 'cadastro_trens.php', 'editar_trem.php', 'cadastro_rotas.php', 'editar_rota.php', 'cadastro_linhas.php'], true);
$navGrupoSensores = in_array($paginaAtual, ['Sensor_Data.php', 'Sensor.php', 'Sensor_Manage.php', 'sensor_delete.php'], true);
$navGrupoRelatorios = in_array($paginaAtual, ['Relatories.php', 'Relatories_New.php', 'Relatorie_Info.php'], true);
?>


<nav class="menu-nav navbar navbar-dark" aria-label="Navegação Principal TrackFlow">
    <div class="container-fluid px-2 px-lg-3" style="max-width: 1600px; margin: 0 auto;">


        <div class="d-flex align-items-center gap-2">
            <button class="navbar-toggler ops-nav-toggler" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar"
                aria-label="Abrir menu de navegação">
                <i class="bi bi-list fs-4" aria-hidden="true"></i>
            </button>


            <a class="navbar-brand d-flex align-items-center gap-2 py-0 me-0" href="hub.php">
                <span class="ops-brand-mark" aria-hidden="true">
                    <img src="../assets/logo/logo-track-flow.png" alt="Logo TrackFlow" class="hub-icon" width="26"
                        height="26" decoding="async">
                </span>
                <span class="d-flex flex-column lh-1">
                    <span class="fw-bold fs-4 text-white brand-font tracking-tight">TrackFlow</span>
                    <span class="ops-brand-sub text-volt">Ferrorama</span>
                </span>
            </a>
        </div>


        <div class="d-flex align-items-center gap-2 position-relative">


            <div class="d-none d-md-flex flex-column text-end me-1 lh-sm">
                <span id="usuario-logado" class="text-white small fw-semibold">
                    <?= htmlspecialchars($_SESSION["usuario"] ?? 'Operador', ENT_QUOTES, "UTF-8") ?>
                </span>
                <span class="mono-font ops-role-caption d-inline-flex align-items-center gap-1">
                    <span
                        class="ops-role-dot <?= $usuarioEhAdmin ? 'dot-admin' : 'dot-func' ?>"></span><?= $usuarioEhAdmin ? 'Administrador' : 'Funcionário' ?>
                </span>
            </div>


            <button type="button" class="perfil ops-avatar-trigger" aria-haspopup="true" aria-expanded="false"
                aria-controls="menu_user"
                aria-label="Abrir menu da conta de <?= htmlspecialchars($_SESSION["usuario"] ?? 'Operador', ENT_QUOTES, 'UTF-8') ?>">
                <span class="imgCx">
                    <img src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Foto de perfil" width="40"
                        height="40" decoding="async"
                        onerror="this.onerror=null; this.src='<?= htmlspecialchars($avatarPadrao, ENT_QUOTES, 'UTF-8') ?>';">
                </span>
            </button>


            <!-- MENU DROPDOWN DO USUÁRIO -->
            <div id="menu_user" class="menu" role="menu" aria-label="Opções do operador">
                <div class="px-3 py-2 border-bottom border-light border-opacity-10 d-md-none">
                    <strong
                        class="d-block text-white small text-truncate"><?= htmlspecialchars($_SESSION["usuario"] ?? 'Operador', ENT_QUOTES, "UTF-8") ?></strong>
                    <span class="mono-font text-steel d-inline-flex align-items-center gap-1"
                        style="font-size: 0.7rem;"><span
                            class="ops-role-dot <?= $usuarioEhAdmin ? 'dot-admin' : 'dot-func' ?>"></span><?= $usuarioEhAdmin ? 'Administrador' : 'Funcionário' ?></span>
                </div>
                <div>
                    <p><a href="Usuario_Info.php" role="menuitem"><i class="bi bi-person-vcard me-2 text-cyan"
                                aria-hidden="true"></i>Meu Perfil</a></p>
                    <p><button type="button" id="menu-theme-action" class="ops-menu-action-btn" role="menuitem"><i
                                class="bi bi-sun-fill me-2 text-cyan" id="menu-theme-icon" aria-hidden="true"></i><span
                                id="menu-theme-text">Ativar Modo Claro</span></button></p>
                    <p class="Menu_Deslogar"><a href="logout.php" role="menuitem"><i
                                class="bi bi-box-arrow-right me-2 text-danger" aria-hidden="true"></i>Encerrar
                            Sessão</a></p>
                </div>
            </div>
        </div>
    </div>
</nav>


<div class="offcanvas offcanvas-start trackflow-offcanvas" tabindex="-1" id="offcanvasDarkNavbar"
    aria-labelledby="offcanvasDarkNavbarLabel" data-bs-scroll="true">


    <div class="offcanvas-header border-bottom border-light border-opacity-10 py-3 px-4">
        <div class="d-flex align-items-center gap-2">
            <div>
                <h2 class="offcanvas-title brand-font fs-4 text-white mb-0 lh-1" id="offcanvasDarkNavbarLabel">
                    Menu
                </h2>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
            aria-label="Fechar menu"></button>
    </div>


    <div class="offcanvas-body d-flex flex-column justify-content-between p-3">
        <div>
            <div class="px-2 pb-2 mb-2 text-uppercase text-steel small"
                style="font-size: 0.68rem; letter-spacing: 0.08em;">
                Módulos do Sistema
            </div>
            <ul class="navbar-nav pe-1 gap-1">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 px-3 py-2 <?= $navGrupoDashboard ? 'active' : '' ?>"
                        href="hub.php" <?= $navGrupoDashboard ? 'aria-current="page"' : '' ?>>
                        <i class="bi bi-speedometer2 text-cyan fs-5" aria-hidden="true"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 px-3 py-2 <?= $navGrupoUsuarios ? 'active' : '' ?>"
                        href="Admin.php" <?= $navGrupoUsuarios ? 'aria-current="page"' : '' ?>>
                        <i class="bi bi-people text-cyan fs-5" aria-hidden="true"></i>
                        <span>Funcionários e Acessos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 px-3 py-2 <?= $navGrupoFrota ? 'active' : '' ?>"
                        href="trens_trilhos.php" <?= $navGrupoFrota ? 'aria-current="page"' : '' ?>>
                        <i class="bi bi-train-front text-cyan fs-5" aria-hidden="true"></i>
                        <span>Trens, Rotas e Trilhos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 px-3 py-2 <?= $navGrupoSensores ? 'active' : '' ?>"
                        href="Sensor_Data.php" <?= $navGrupoSensores ? 'aria-current="page"' : '' ?>>
                        <i class="bi bi-cpu text-cyan fs-5" aria-hidden="true"></i>
                        <span>Sensores da Malha</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 px-3 py-2 <?= $navGrupoRelatorios ? 'active' : '' ?>"
                        href="Relatories.php" <?= $navGrupoRelatorios ? 'aria-current="page"' : '' ?>>
                        <i class="bi bi-journal-text text-cyan fs-5" aria-hidden="true"></i>
                        <span>Relatórios e Leituras</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>