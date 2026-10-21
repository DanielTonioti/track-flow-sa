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

?>

<nav class="menu-nav navbar navbar-dark cores-background" data-background="azure-claro-fundo">

    <div class="container-fluid">

        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center" href="hub.php">
            <img src="../assets/logo/logo-track-flow.png" alt="Logo TrackFlow" class="hub-icon">
            <h2 class="fw-bold cores-color mb-0" data-color="white">
                Trackflow
            </h2>
        </a>

        <div class="d-flex">

            <!-- BOTÃO MENU -->
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENU LATERAL -->
            <div class="hub-end offcanvas offcanvas-start cores-background" tabindex="-1" id="offcanvasDarkNavbar"
                aria-labelledby="offcanvasDarkNavbarLabel" data-background="azure-claro-fundo">

                <div class="offcanvas-header">
                    <h3 class="offcanvas-title cores-color" data-color="white" id="offcanvasDarkNavbarLabel">
                        TrackFlow
                    </h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                        aria-label="Fechar"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="Admin.php">Funcionários</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="Relatories.php">Relatórios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="hub.php">Sensores</a>
                        </li>
                        <?php if ($usuarioEhAdmin): ?>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="trens_trilhos.php">Trilhos e trens</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>

            <!-- NOME DO USUÁRIO -->
            <div class="d-flex align-items-center justify-content-center me-2">
                <span id="usuario-logado" class="nav-nome cores-color small fw-bold" data-color="white">
                    <?php
                    if (!empty($_SESSION["usuario"])) {
                        echo htmlspecialchars($_SESSION["usuario"], ENT_QUOTES, "UTF-8");
                    }
                    ?>
                </span>
            </div>

            <!-- AVATAR -->
            <div class="perfil">
                <div class="imgCx">
                    <img src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar do usuário"
                        onerror="this.onerror=null; this.src='<?= htmlspecialchars($avatarPadrao, ENT_QUOTES, 'UTF-8') ?>';">
                </div>
            </div>

            <!-- MENU DO USUÁRIO -->
            <div id="menu_user" class="cores-color menu" data-color="white">
                <div>
                    <p><a href="#">Perfil</a></p>
                    <p class="Menu_Deslogar"><a href="logout.php">Deslogar</a></p>
                </div>
            </div>

        </div>

    </div>

</nav>