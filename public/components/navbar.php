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