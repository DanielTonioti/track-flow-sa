<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/icone.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style.css">
    <title> Página Inicial </title>
</head>

<body class="cores-background " data-background="azure-escuro-fundo">

    <head>
        <header>

            <nav class="menu-nav navbar navbar-dark cores-background data-background="azure-claro-fundo">
                <!-- http://127.0.0.1:5500/public/hub.html -->
                <div class="container-fluid">

                    <a class="navbar-brand  d-flex align-items-center" href="#">
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
                                            href="Admin.html">Funcionarios</a>
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
                        <img src="../assets/icons/User.png" alt="...">
                    </div>
                </div>
                <div id="menu_user"  class="cores-color menu" data-color="white">
                    <div>
                        <p>
                            <a href="#">
                                Perfil
                            </a>
                        </p>
                          <p class="Menu_Deslogar">
                            <a   href="Login.html">
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
        <hr class="opacity-100 cores-background hub-main-hr d-flex m-0" data-background="cinza-claro">
        <div class="cores-background hub-main-graphics d-flex align-items-center justify-content-center"
            data-background="azure-apagado">
            <p>a</p>
        </div>
        <!-- Area sensores -->
        <div class="flex centralizar-tabela mt-4 cores-color" data-color="white">
            <div class="d-flex flex-column">
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor001">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRM.001
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin001">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor001">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Velocidade média:</p>
                                <p>12 km/h</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p> 56 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p> Funcionando </p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor002">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRM.002
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin002">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor002">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Velocidade média:</p>
                                <p>26 km/h</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>45 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor003">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRM.003
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin003">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor003">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Velocidade média:</p>
                                <p>14 km/h</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>24 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor004">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRM.004
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin004">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor004">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Velocidade média:</p>
                                <p>1670 km/h</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>5400 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor005">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRM.005
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin005">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor005">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Velocidade média:</p>
                                <p>0 km/h</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>12 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Não Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column">
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor006">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRLH.001
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin006">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor006">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>21 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor007">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRLH.001
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin007">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor007">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>19 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor008">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRLH.001
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin008">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor008">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>23 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor009">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRLH.001
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin009">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor009">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>17 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor010">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRLH.001
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin010">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor010">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>29 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Area botoes de modificação -->
        <div class="justify-content-center align-items-center d-flex mt-5">
            <button
                class="border-none cores-background me-4 rounded-pill cores-color px-3 fw-bold hub-main-sensor-management"
                data-background="azure-claro-fundo" data-color="white" id="AddSensor">
                <p class="mt-2">Adicionar sensor</p>
            </button>
            <button
                class="cores-background align-items-center rounded-pill justify-content-center cores-color px-3 fw-bold hub-main-sensor-management me-4"
                data-background="azure-claro-fundo" data-color="white">
                <p class="mt-2">Excluir sensor</p>
            </button>
        </div>
        <button id="VoltarLogin" class="btn btn-danger back-buttom"> Voltar </button>
    </main>
    <footer>

    </footer>

    <script src="../scripts/script-sair.js"></script>
    <script src="../scripts/script-navbar.js"></script>
    <script src="../scripts/script-hub.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

</body>

</html>