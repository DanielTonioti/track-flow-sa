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
        <?php

        include("components/navbar.php");

        ?>
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
                    <div class="cores-background p-2 rounded-4 blockcentro table-trems"
                        data-background="azure-claro-fundo">
                        <h3> Trens </h3>
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
                        <h3> Linhas </h3>
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