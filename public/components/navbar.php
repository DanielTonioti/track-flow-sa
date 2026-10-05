<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Avatar padrão
$avatarPadrao = "../assets/icons/User.png";
$avatar = $avatarPadrao;

// Usa o avatar cadastrado só se o arquivo realmente existir
if (!empty($_SESSION["avatar"])) {

    // Normaliza: remove "../" e padroniza a pasta (caso existam dados antigos)
    $caminho = str_replace("../", "", $_SESSION["avatar"]);
    $caminho = preg_replace('#^Assets/#', 'assets/', $caminho);

    // Esta navbar está em pages/components/, então a raiz fica 2 níveis acima
    $arquivoFisico = __DIR__ . "/../../" . $caminho;

    if (is_file($arquivoFisico)) {
        $avatar = "../" . $caminho;
    }
}

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
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="admin.php">Funcionarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="relatories.php">Relatórios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="hub.php">Sensores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="trens_trilhos.php">Trilhos e trens</a>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- NOME DO USUÁRIO -->
            <div class="d-flex flex-column align-items-end me-2">
                <span id="usuario-logado" class="cores-color small fw-bold" data-color="white">
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