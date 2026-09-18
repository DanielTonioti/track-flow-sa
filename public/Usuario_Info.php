<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style.css">
    <title> Informações do usuario </title>
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

    </head>

    <main>
        <div class="blockcentro">
            <h1 class="cores-color" data-color="white">Editar Usuario: Placeholder</h1>
        </div>
        <form action="">
        <div class="blockcentro">
            
            <label for="inputPassword5" class="cores-color form-label" data-color="white">Name</label>
            <input type="Text" id="inputPassword5" required class="FormUsuario form-control"
                aria-describedby="passwordHelpBlock" name="NomeUpdate">
            <label for="inputPassword5" class="cores-color form-label" data-color="white">E-mail</label>
            <input type="email" id="inputPassword5" required class="FormUsuario form-control"
                aria-describedby="passwordHelpBlock" name="EmailUpdate">
            <label for="inputPassword5" class="cores-color form-label" data-color="white">Senha</label>
            <input type="password" id="inputPassword5" required class="FormUsuario form-control"
                aria-describedby="passwordHelpBlock" name="PasswordUpdateRegular">
            <label for="inputPassword5" class="cores-color form-label" data-color="white">Confirmar Senha</label>
            <input type="password" id="inputPassword5" required class="FormUsuario form-control"
                aria-describedby="passwordHelpBlock" name="PasswordUpdateConfirm">
            </div>
        <div class="DangerZone">
            <div class="blockcentro">
                <label for="inputPassword5" class="cores-color  form-label" data-color="white">Nivel de
                    Acesso</label>
                        <div>
                        <input type="radio" name="acesso" id="funcionario" value=true required> Funcionário
                        <input type="radio" name="acesso" id="administrador" value=false> Administrador
                        </div>
                <div class="ButtonExcluirUsuario">
                    <button type="button" class="  btn btn-danger">Excluir Usuario</button>
                </div>
            </div>
        </div>
        <div class="blockcentro">
            <input type="submit" class="btn cores-color cores-background" data-color="white"
                data-background="azure-claro-fundo" value="Atualizar usuário"></input>
        </div>
        </form>
        



        <button id="voltarhub" class="btn btn-danger back-buttom"> Voltar </button>
    </main>
    <footer>

    </footer>
    <script src="../scripts/scriptVoltar.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

</body>

</html>