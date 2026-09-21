<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Relatório</title>
</head>

<body class="cores-background " data-background="azure-escuro-fundo">

    <head>
        <header>

            <nav class="menu-nav navbar navbar-dark cores-background" data-background="azure-claro-fundo">
                <!-- http://127.0.0.1:5500/public/hub.html -->
                <div class="container-fluid">

                    <a class="navbar-brand d-flex align-items-center" href="#">
                        <img src="../assets/logo/logo-track-flow.png" alt="Logo TrackFlow" class="hub-icon ">
                        <h2 class="fw-bold cores-color mb-0" data-color="white"> Trackflow </h2>
                    </a>
                    <div class="d-flex">
                        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasDarkNavbar">
                            <span class=" navbar-toggler-icon"></span>
                        </button>
                        <div class="hub-end offcanvas offcanvas-start cores-background" tabindex="-1"
                            id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel"
                            data-background="azure-claro-fundo">
                            <div class="offcanvas-header">
                                <h3 class="offcanvas-title cores-color" data-color="white"
                                    id="offcanvasDarkNavbarLabel">
                                    TrackFlow</h3>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page"
                                            href="Admin.php">Funcionários</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page"
                                            href="Relatories.php">Relatórios</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="hub.php">Sensores</a>
                                    </li>

                                </ul>

                            </div>
                        </div>


                        <div class="perfil">
                            <div class="imgCx">
                                <img src="../assets/icons/User.png" alt="...">
                            </div>
                        </div>
                        <div id="menu_user" class="cores-color menu" data-color="white">
                            <div>
                                <p>
                                    <a href="#">
                                        Perfil
                                    </a>
                                </p>
                                <p class="Menu_Deslogar">
                                    <a href="Login.php">
                                        Deslogar
                                    </a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </nav>


        </header>

    </head>

    <main class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="text-white mb-1">Relatórios</h1>
                <p class="text-white-50 mb-0">Cadastre e consulte relatórios do sistema.</p>
            </div>
            <a class="btn btn-light fw-bold" href="Relatories_New.php">Novo relatório</a>
        </div>

        <section class="cores-background rounded-4 p-4 mb-4" data-background="azure-claro-fundo">
            <h2 class="h4 text-white mb-3">Relatórios recentes</h2>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Título</th>
                            <th scope="col">Período</th>
                            <th scope="col">Responsável</th>
                            <th scope="col">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Monitoramento da Rota Norte</td>
                            <td>01/09/2026 - 07/09/2026</td>
                            <td>Daniel</td>
                            <td><a class="btn btn-sm btn-outline-light" href="Relatorie_Info.php">Visualizar</a></td>
                        </tr>
                        <tr>
                            <td>Dados dos sensores</td>
                            <td>08/09/2026 - 14/09/2026</td>
                            <td>Equipe TrackFlow</td>
                            <td><a class="btn btn-sm btn-outline-light" href="Relatorie_Info.php">Visualizar</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <button id="voltarhub" class="btn btn-danger back-buttom">Voltar</button>
    </main>
    <script src="../scripts/scriptVoltar.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>

