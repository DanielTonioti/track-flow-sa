<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Administradores</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">
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
                                            href="admin.php">Funcionarios</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page"
                                            href="relatories.php">Relatórios</a>
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
    <button id="voltarhub" class="btn btn-danger back-buttom"> Voltar </button>
    <div class="blockcentro titulo-Sensor">
        <div class="cores-background p-2 rounded-4 flex" data-background="azure-claro-fundo">
            <h2 class="titulo-Sensor"> Gerenciar trems e sensores </h2>
        </div>
    </div> 
    <div class="blockcentro">
        <div class="flex">
            <div>
            <div class="cores-background p-2 rounded-4 blockcentro table-trems" data-background="azure-claro-fundo">
                    <div>
                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <th>Número</th>
                                <th>Estação</th>
                                <th>Tamanho</th>
                                <th>Ações</th>
                            </tr>
                        </table>
                    </div>
                    <a href="cadastro_trens.php"> Cadastrar trem </a>
            </div>
            </div>
            <div>
            <div class="cores-background p-2 rounded-4 blockcentro" data-background="azure-claro-fundo">
                    <table class="table table-trilhos">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Tamanho</th>
                        </tr>
                    </table>
            <a href="cadastro_linhas.php"> Cadastrar linha </a>
            </div>
            </div>
        </div>
    </div> 

   
   
   
   
   </main>

    <footer>

    </footer>





    <script src="../scripts/scriptUsersPage.js"></script>
    <script src="../scripts/scriptVoltar.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>