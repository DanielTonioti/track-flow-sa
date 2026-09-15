<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Cadastrar Trem</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">
    <header>
        <nav class="menu-nav navbar navbar-dark cores-background" data-background="azure-claro-fundo">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="hub.php">
                    <img src="../Assets/logo/logo-track-flow.png" alt="Logo TrackFlow" class="hub-icon">
                    <h2 class="fw-bold cores-color mb-0" data-color="white">Trackflow</h2>
                </a>
                <div class="d-flex align-items-center">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar"
                        aria-label="Abrir menu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="hub-end offcanvas offcanvas-start cores-background" tabindex="-1"
                        id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel"
                        data-background="azure-claro-fundo">
                        <div class="offcanvas-header">
                            <h3 class="offcanvas-title cores-color" data-color="white" id="offcanvasDarkNavbarLabel">
                                TrackFlow</h3>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                                aria-label="Fechar menu"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                <li class="nav-item"><a class="nav-link" href="Admin.php">Funcionarios</a></li>
                                <li class="nav-item"><a class="nav-link" href="Relatories.php">Relatorios</a></li>
                                <li class="nav-item"><a class="nav-link" href="hub.php">Sensores</a></li>
                                <li class="nav-item"><a class="nav-link active" aria-current="page"
                                        href="Trilhos.html">Trilhos e rotas</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="perfil">
                        <div class="imgCx">
                            <img src="../Assets/icons/User.png" alt="Abrir menu do usuario">
                        </div>
                    </div>
                    <div id="menu_user" class="cores-color menu" data-color="white">
                        <div>
                            <p><a href="#">Perfil</a></p>
                            <p class="Menu_Deslogar"><a href="Login.php">Deslogar</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="CadastroTremPage">
        <h1 class="CadastroTremTitle">Cadastrar Trem</h1>

        <form class="CadastroTremForm" id="CadastroTremForm" novalidate>
            <div class="CadastroTremFields">
                <div class="CadastroTremField">
                    <label class="CadastroTremLabel" for="Numeracao">Numeração:</label>
                    <input class="CadastroTremInput" type="text" id="Numeracao" name="Numeracao"
                        placeholder="Ex: N289T" required>
                </div>

                <div class="CadastroTremField">
                    <label class="CadastroTremLabel" for="EstacaoOrigem">Estação de Origem:</label>
                    <input class="CadastroTremInput" type="text" id="EstacaoOrigem" name="EstacaoOrigem"
                        placeholder="Inicial" required>
                </div>

                <div class="CadastroTremField">
                    <label class="CadastroTremLabel" for="Tamanho">Tamanho:</label>
                    <input class="CadastroTremInput" type="number" id="Tamanho" name="Tamanho"
                        placeholder="Tamanho em metros" min="1" required>
                </div>
            </div>
        </form>

        <button class="CadastroTremButton" type="submit" form="CadastroTremForm">Cadastrar</button>
    </main>

    <script src="../scripts/scriptTrilhos.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwxHj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>
