<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Sensor</title>
</head>

<body class="cores-background " data-background="azure-escuro-fundo" class=" cores-background "
    data-background="azure-escuro-fundo">
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
                            <h3 class="offcanvas-title cores-color" data-color="white" id="offcanvasDarkNavbarLabel">
                                TrackFlow
                            </h3>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="Admin.html">Funcionários</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="Relatories.html">Relatórios</a>
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

    <main>
        <form id="CadastrarSensores">
            <div class="blockcentro titulo-Sensor">
                <div class="cores-background p-2 rounded-4" data-background="azure-claro-fundo">
                    <h2 class="titulo-Sensor">Cadastrar Sensor</h2>
                </div>
                <br>
                <div id="valortipo" class="cores-background p-4 w-25 rounded-4" data-background="azure-claro-fundo">
                    <div class="center-sensor">
                        <input type="radio" name="tipo" id="trem" value=true required> Trem
                        <input type="radio" name="tipo" id="trilho" value=false> Trilho
                    </div>
                    <br>
                    <div class="column-sensor" id="tipos">
                        <label for="tipo_dado">Tipo de Dado</label>
                        <select name="tipo_dado" id="tipo_dado" required>
                            <option value="" disabled selected>Selecione</option>
                            <option value="Velocidade">Velocidade</option>
                            <option value="Temperatura">Temperatura</option>
                            <option value="Falha">Falha</option>
                        </select>
                        <br>
                        <div class="hiden" id="tout">
                            <label for="trem-vinculado">Trem vinculado</label>
                            <br>
                            <input class="w-100" type="text" id="tremvinculado" placeholder="Ex: S.TRM.1" required>
                        </div>

                        <div class="hiden" id="localisation">
                            <label for="loc-trilho">Localização</label>
                            <br>
                            <input class="w-100" type="text" id="loc-trilho" placeholder="asfa" required>
                        </div>

                    </div>
                </div>
                <br>
                <button class="border-none-buttom cores-background titulo-Sensor p-2 rounded-3"
                    data-background="azure-claro-fundo">Cadastrar</button>
            </div>
        </form>


        <script src="../scripts/"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>

        <button id="voltarhub" class="btn btn-danger back-buttom"> Voltar </button>
    </main>
    <footer>

    </footer>

    <script src="../scripts/script-sensor.js"></script>
    <script src="../scripts/script-admin.js"></script>
    <script src="../scripts/script-navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>