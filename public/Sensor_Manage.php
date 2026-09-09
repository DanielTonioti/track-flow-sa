<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Gerenciador de sensor</title>
</head>

<body class="cores-background d-flex flex-column min-vh-100" data-background="azure-escuro-fundo">
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
                            <p><a href="#">Perfil</a></p>
                            <p class="Menu_Deslogar"><a href="Login.html">Deslogar</a></p>
                        </div>
                    </div>
                </div>

            </div>
        </nav>
    </header>
    <main class="flex-grow-1 d-flex flex-column">
        <div class="d-flex flex-column cores-background h-100 flex-grow-1" data-background="azure-escuro-fundo">

            <div class="d-flex justify-content-center">
                <div class="sensor-manage-main-title d-flex justify-content-center align-items-center cores-background rounded-pill mt-4"
                    data-background="azure-claro-fundo">
                    <p class="cores-color fw-bold py-0 mt-3" data-color="white">
                        Excluir sensor
                    </p>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="d-flex flex-column align-items-center justify-content-center mt-5 me-3 gap-2">
                    <div class="d-flex">
                        <p class="sensor-manage-main-text text-center border-tabela px-4 pt-1 fs-6 cores-color cores-background m-0 flex-grow-1"
                            data-color="white" data-background="verde">S.TRM.001</p>
                        <button class="sensor-manage-main-table-button border-tabela cores-color cores-background"
                            data-color="white" data-background="vermelho" data-bs-toggle="modal"
                            data-bs-target="#modalExcluir">Remover</button>
                    </div>
                    <div class="d-flex">
                        <p class="sensor-manage-main-text text-center border-tabela px-4 pt-1 fs-6 cores-color cores-background m-0 flex-grow-1"
                            data-color="white" data-background="verde">S.TRM.002</p>
                        <button class="sensor-manage-main-table-button border-tabela cores-color cores-background"
                            data-color="white" data-background="vermelho" data-bs-toggle="modal"
                            data-bs-target="#modalExcluir">Remover</button>
                    </div>
                    <div class="d-flex">
                        <p class="sensor-manage-main-text text-center border-tabela px-4 pt-1 fs-6 cores-color cores-background m-0 flex-grow-1"
                            data-color="white" data-background="verde">S.TRM.003</p>
                        <button class="sensor-manage-main-table-button border-tabela cores-color cores-background"
                            data-color="white" data-background="vermelho" data-bs-toggle="modal"
                            data-bs-target="#modalExcluir">Remover</button>
                    </div>
                    <div class="d-flex">
                        <p class="sensor-manage-main-text text-center border-tabela px-4 pt-1 fs-6 cores-color cores-background m-0 flex-grow-1"
                            data-color="white" data-background="verde">S.TRM.004</p>
                        <button class="sensor-manage-main-table-button border-tabela cores-color cores-background"
                            data-color="white" data-background="vermelho" data-bs-toggle="modal"
                            data-bs-target="#modalExcluir">Remover</button>
                    </div>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center mt-5 gap-2">
                    <div class="d-flex">
                        <p class="sensor-manage-main-text text-center border-tabela px-4 pt-1 fs-6 cores-color cores-background m-0 flex-grow-1"
                            data-color="white" data-background="verde">S.TRLH.001</p>
                        <button class="sensor-manage-main-table-button border-tabela cores-color cores-background"
                            data-color="white" data-background="vermelho" data-bs-toggle="modal"
                            data-bs-target="#modalExcluir">Remover</button>
                    </div>
                    <div class="d-flex">
                        <p class="sensor-manage-main-text text-center border-tabela px-4 pt-1 fs-6 cores-color cores-background m-0 flex-grow-1"
                            data-color="white" data-background="verde">S.TRLH.002</p>
                        <button class="sensor-manage-main-table-button border-tabela cores-color cores-background"
                            data-color="white" data-background="vermelho" data-bs-toggle="modal"
                            data-bs-target="#modalExcluir">Remover</button>
                    </div>
                    <div class="d-flex">
                        <p class="sensor-manage-main-text text-center border-tabela px-4 pt-1 fs-6 cores-color cores-background m-0 flex-grow-1"
                            data-color="white" data-background="verde">S.TRLH.003</p>
                        <button class="sensor-manage-main-table-button border-tabela cores-color cores-background"
                            data-color="white" data-background="vermelho" data-bs-toggle="modal"
                            data-bs-target="#modalExcluir">Remover</button>
                    </div>
                    <div class="d-flex">
                        <p class="sensor-manage-main-text text-center border-tabela px-4 pt-1 fs-6 cores-color cores-background m-0 flex-grow-1"
                            data-color="white" data-background="verde">S.TRLH.004</p>
                        <button class="sensor-manage-main-table-button border-tabela cores-color cores-background"
                            data-color="white" data-background="vermelho" data-bs-toggle="modal"
                            data-bs-target="#modalExcluir">Remover</button>
                    </div>
                </div>
            </div>
            <div class="align-items-center d-flex flex-column mt-auto mb-4">
                <button id="voltarsensor" class="btn btn-danger text-center"> Voltar </button>
            </div>
        </div>
        <div class="modal fade" id="modalExcluir" tabindex="-1" aria-labelledby="modalExcluirLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content cores-color cores-background rounded-4 border border-3 border-black w-auto mx-auto"
                    data-background="cinza-claro">
                    <div class="modal-body text-center pt-5 px-5 pb-3">
                        <h4 class="fw-bold mb-3">Certeza que deseja excluir sensor:</h4>
                        <h3 class="fw-bold mb-5" id="sensorNome"></h3>
                        <div class="d-flex justify-content-around gap-4">
                            <button type="button" class="btn btn-danger fw-bold px-4 py-2 rounded-3">
                                Confirmar
                            </button>
                            <button type="button" class="btn btn-success fw-bold px-4 py-2 rounded-3"
                                data-bs-dismiss="modal">
                                Voltar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer></footer>
    <script src="../scripts/script-sensor-manage.js"></script>
    <script src="../scripts/script-navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>