<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Gerenciador de sensor</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">

    <head>
        <header>

            <?php

            include("components/navbar.php");

            ?>

        </header>

    </head>

    <main>
        <div class="admin-titulo cores-background mb-5" data-background="azure-claro-fundo">
            <p class="admin-titulo-texto cores-color " data-color="white">
                Gerenciar Sensores
            </p>
        </div>

        <div class="centralizar-tabela">
            <div class="flex">
                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background" data-color="white"
                    data-background="azure-claro-fundo">
                    S001
                </p>
                <button class=" admin-tabela-button border-tabela cores-color cores-background" data-color="white"
                    data-background="vermelho" data-bs-toggle="modal" data-bs-target="#ModalExcluirSensor">
                    Remover
                </button>
            </div>
            <div class="flex">
                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background" data-color="white"
                    data-background="azure-claro-fundo">
                    S002
                </p>
                <button class="admin-tabela-button border-tabela cores-color cores-background" data-color="white"
                    data-background="vermelho" data-bs-toggle="modal" data-bs-target="#ModalExcluirSensor">
                    Remover
                </button>
            </div>
            <div class="flex">
                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background" data-color="white"
                    data-background="azure-claro-fundo">
                    S003
                </p>
                <button class="admin-tabela-button border-tabela cores-color cores-background" data-color="white"
                    data-background="vermelho" data-bs-toggle="modal" data-bs-target="#ModalExcluirSensor">
                    Remover
                </button>
            </div>
            <div class="flex">
                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background" data-color="white"
                    data-background="azure-claro-fundo">
                    S004
                </p>
                <button class="admin-tabela-button border-tabela cores-color cores-background" data-color="white"
                    data-background="vermelho" data-bs-toggle="modal" data-bs-target="#ModalExcluirSensor">
                    Remover
                </button>
            </div>
            <div class="flex">
                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background" data-color="white"
                    data-background="azure-claro-fundo">
                    S005
                </p>
                <button class="admin-tabela-button border-tabela cores-color cores-background" data-color="white"
                    data-background="vermelho" data-bs-toggle="modal" data-bs-target="#ModalExcluirSensor">
                    Remover
                </button>
            </div>

            <div class="cores-color cores-background" data-color="White">
                <button class="admin-adicionar admin-button cores-color cores-background" data-color="white"
                    data-background="azure-claro-fundo">
                    Adicionar Sensor
                </button>
            </div>
        </div>
        <button id="voltarsensor" class="btn btn-danger back-buttom"> Voltar </button>
    </main>
    <div class="modal fade" id="ModalExcluirSensor" tabindex="-1" aria-labelledby="TituloModalExcluirSensor"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content cores-background cores-color" data-background="cinza-claro" data-color="black">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="TituloModalExcluirSensor">Excluir sensor</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">Tem certeza que deseja excluir este sensor?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    <footer>

    </footer>





    <script src="../scripts/scriptSensorManage.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>