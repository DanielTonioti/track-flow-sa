<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Informações do relatorio</title>
</head class="header">

<body class="cores-background min-vh-100" data-background="azure-escuro-fundo">

    <head>
        <header>
            <nav class="menu-nav navbar navbar-dark cores-background" data-background="azure-claro-fundo">
                <div class="container-fluid d-flex justify-content-between align-items-center">
                    <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                        <img src="../assets/logo/logo-track-flow.png" alt="Logo TrackFlow" class="hub-icon">
                        <h2 class="fw-bold cores-color mb-0" data-color="white">Trackflow</h2>
                    </a>
                    <div class="d-flex align-items-center gap-3">
                        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasDarkNavbar">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="hub-end offcanvas offcanvas-start cores-background" tabindex="-1"
                            id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel"
                            data-background="azure-claro-fundo">
                            <div class="offcanvas-header">
                                <h3 class="offcanvas-title cores-color" data-color="white"
                                    id="offcanvasDarkNavbarLabel">
                                    TrackFlow
                                </h3>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page"
                                            href="Admin.html">Funcionários</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page"
                                            href="Relatories.html">Relatórios</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="hub.html">Sensores</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="perfil">
                            <div class="imgCx">
                                <img src="../assets/icons/User.png" alt="Usuário">
                            </div>
                        </div>
                        <div id="menu_user" class="cores-color menu" data-color="white">
                            <div>
                                <p>
                                    <a href="#">Perfil</a>
                                </p>
                                <p class="Menu_Deslogar">
                                    <a href="Login.html">Deslogar</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
    </head>
    <main class="mt-5 flex-grow-1 d-flex flex-column justify-content-start align-items-center">
        <div class="d-flex flex-wrap justify-content-start relatorie-info-c-archives gap-4 w-100 container">
            <div class="cores-background mb-0 rounded-2" data-background="azure-apagado">
                <div class="cores-background cores-color relatories-info-main-head-div px-3 rounded-top-2"
                    data-color="white" data-background="azul-pastel">
                    <p class="mb-1">Relatório [nome do trem ou da linha]</p>
                    <p class="mb-2 relatories-info-main-date-relatorie pb-1">17 de Maio, 2024 - 20 de Maio, 2024</p>
                </div>
                <div class="d-flex justify-content-between px-2 mb-2 mt-2">
                    <button class="cores-background rounded-pill border border-1 px-2" data-background="azul-apagado">
                        Visualizar
                    </button>
                    <button class="cores-background border border-1 rounded-pill px-2" data-background="azul-vivido">
                        Baixar
                    </button>
                </div>
            </div>
            <div class="cores-background mb-0 rounded-2" data-background="azure-apagado">
                <div class="cores-background cores-color relatories-info-main-head-div px-3 rounded-top-2"
                    data-color="white" data-background="azul-pastel">
                    <p class="mb-1">Relatório [nome do trem ou da linha]</p>
                    <p class="mb-2 relatories-info-main-date-relatorie pb-1">17 de Maio, 2024 - 20 de Maio, 2024</p>
                </div>
                <div class="d-flex justify-content-between px-2 mb-2 mt-2">
                    <button class="cores-background rounded-pill border border-1 px-2" data-background="azul-apagado">
                        Visualizar
                    </button>
                    <button class="cores-background border border-1 rounded-pill px-2" data-background="azul-vivido">
                        Baixar
                    </button>
                </div>
            </div>
    </main>
    <footer></footer>

    <script src="../scripts/script-voltar.js"></script>
    <script src="../scripts/script-navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>