<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Data dos sensores</title>
</head>

<body>
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
                            <h3 class="offcanvas-title cores-color" data-color="white" id="offcanvasDarkNavbarLabel">
                                TrackFlow</h3>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
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

    <main class="container py-4">
        <div class="ReportFormPanel mx-auto cores-background rounded-4 p-4 p-md-5" data-background="azure-claro-fundo">
            <h1 class="text-white mb-4">Novo relatÃ³rio</h1>
            <form>
                <div class="mb-3">
                    <label class="form-label text-white" for="TituloRelatório">Título</label>
                    <input class="form-control" type="text" id="TituloRelatório" placeholder="Título do relatÃ³rio">
                </div>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label text-white" for="SensorRelatório">Sensor ou rota</label>
                        <select class="form-select" id="SensorRelatório">
                            <option selected>Selecione</option>
                            <option>Rota Norte</option>
                            <option>Sensor S001</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label text-white" for="PeriodoRelatório">Período</label>
                        <select class="form-select" id="PeriodoRelatório">
                            <option selected>últimos 7 dias</option>
                            <option>últimos 30 dias</option>
                            <option>Período personalizado</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a class="btn btn-outline-light" href="Relatories.php">Cancelar</a>
                    <button class="btn btn-light fw-bold" type="button">Gerar relatório</button>
                </div>
            </form>
        </div>
    </main>

    </head>

    <script src="../scripts/scriptVoltar.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>

