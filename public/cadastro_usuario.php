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
                                    <a class="nav-link active" aria-current="page" href="admin.php">Funcionarios</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="relatories.php">Relatórios</a>
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
                                <a href="login.php">
                                    Deslogar
                                </a>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </nav>


    </header>

    <main>
        <form id="CadastrarSensor">
            <div class="blockcentro titulo-Sensor">
                <div class="cores-background p-2 rounded-4" data-background="azure-claro-fundo">
                    <h2 class="titulo-Sensor">Cadastrar Usuário</h2>
                </div>
                <br>
                <div id="valortipo" class="cores-background p-4 w-25 rounded-4" data-background="azure-claro-fundo">
                    <br>
                    <div class="column-sensor" id="tipos">
                        <label for="nome"> Nome: </label>
                        <input type="text" name="nome" required>
                        <label for="nome"> E-mail: </label>
                        <input type="email" name="email" required>
                        <div class="center-sensor">
                        <input type="radio" name="acesso" id="funcionario" value=true required> Funcionário
                        <input type="radio" name="acesso" id="administrador" value=false> Administrador
                        </div>
                    </div>
                </div>
                <br>
                <input type="submit" value="Cadastrar" class="border-none-buttom cores-background titulo-Sensor p-2 rounded-3"
                data-background="azure-claro-fundo"></button>
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

    <script src="../scripts/scriptSensor.js"></script>
    <script src="../scripts/scriptAdmin.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>