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

    <head>
        <header>
        <?php include("components/navbar.php") ?>
        </header>
    </head>

    <main>
        <div class="flex justify-content-center">
            <div class="admin-titulo d-flex justify-content-center align-items-center cores-background rounded-pill"
                data-background="azure-claro-fundo">
                <p class="admin-titulo-texto cores-color fw-bold mx-3" data-color="white">
                    Funcionarios
                </p>
            </div>
        </div>
        <!-- Área dos funcionarios -->
        <div>
            <div class="text-center text-white p-3">
                <button id="botao-admin" class="btn text-white fw-bold" type="button">
                    <div class="d-flex align-items-center">
                        Administradores
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta-para-baixo do admin"
                            class="users-page-arrow" id="seta-admin">
                    </div>
                </button>
                <div class="collapse" id="lista-adm">
                    <div class="d-flex flex-column mt-2">
                        <div>
                            <div class="flex centralizar-tabela">
                                <p class="border-tabela admin-tabela tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    Marlon
                                </p>
                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    Marlon@gmail.com
                                </p>
                                <p class="border-tabela admin-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    (47)99121-0088
                                </p>
                                <button
                                    class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="vermelho">
                                    Remover
                                </button>
                            </div>
                        </div>
                        <div>
                            <div class="flex centralizar-tabela">
                                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    Maria
                                </p>
                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    Maria@protonmail.com
                                </p>
                                <p class="border-tabela admin-tabela px-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    (47)99267-6572
                                </p>
                                <button
                                    class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="vermelho">
                                    Remover
                                </button>
                            </div>
                        </div>
                        <div>
                            <div class="flex centralizar-tabela">
                                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    Vanessa
                                </p>
                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    Vanessa@gmail.com
                                </p>
                                <p class="border-tabela admin-tabela px-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    (47)99123-4567
                                </p>
                                <button
                                    class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="vermelho">
                                    Remover
                                </button>
                            </div>
                        </div>
                        <div>
                            <div class="flex centralizar-tabela">
                                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    Maicon
                                </p>
                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    Maicon@hotmail.com
                                </p>
                                <p class="border-tabela admin-tabela px-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    (47)99830-3502
                                </p>
                                <button
                                    class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="vermelho">
                                    Remover
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- <span>Marlon</span>
                        <span>Maria</span>
                        <span>Vanessa</span>
                        <span>Maicon</span> -->
                </div>
            </div>
        </div>
        <div class="text-center cores-color p-3" data-color="white">
            <button id="botao-func" class="btn text-white fw-bold" type="button">
                Funcionarios
                <img src="../assets/icons/seta-para-baixo.png" alt="seta-para-baixo do admin" class="users-page-arrow"
                    id="seta-func">
            </button>
            <!-- Area funcionarios -->
            <div class="collapse" id="lista-func">
                <div class="d-flex flex-column mt-2">
                    <div>
                        <div class="flex centralizar-tabela">
                            <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                                Marlon2
                            </p>
                            <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                data-background="azure-claro-fundo">
                                MarlonAltAccount@gmail.com
                            </p>
                            <p class="border-tabela admin-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                                (47)99538-8303
                            </p>
                            <button class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-background="vermelho">
                                Remover
                            </button>
                        </div>
                    </div>
                    <div>
                        <div class="flex centralizar-tabela">
                            <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                                Jequison
                            </p>
                            <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                data-background="azure-claro-fundo">
                                jequison123@protonmail.com
                            </p>
                            <p class="border-tabela admin-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                                (47)99025-1026
                            </p>
                            <button class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-background="vermelho">
                                Remover
                            </button>
                        </div>
                    </div>
                    <div>
                        <div class="flex centralizar-tabela">
                            <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                                Petroso
                            </p>
                            <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                data-background="azure-claro-fundo">
                                Petroso@gmail.com
                            </p>
                            <p class="border-tabela admin-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                                (47)99125-0154
                            </p>
                            <button class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-background="vermelho">
                                Remover
                            </button>
                        </div>
                    </div>
                    <div>
                        <div class="flex centralizar-tabela">
                            <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                                Patrique
                            </p>
                            <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                data-background="azure-claro-fundo">
                                Patrique@hotmail.com
                            </p>
                            <p class="border-tabela admin-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                                (47)99195-1900
                            </p>
                            <button class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-background="vermelho">
                                Remover
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="cores-color cores-background centralizar-tabela flex" data-color="White">
            <button
                class="admin-button cores-color cores-background rounded-pill mt-1 align-items-center justify-content-center text-center"
                data-color="white" data-background="azure-claro-fundo" id="admin-button">
                <p class="my-auto fw-bold mx-3">Adicionar Funcionário</p>
            </button>
        </div>
        </div>
        <button id="voltarhub" class="btn btn-danger back-buttom"> Voltar </button>
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