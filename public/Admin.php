<?php
session_start();
include_once("../infra/conn.php");
include("components/start.php");

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Administradores</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">
    <header>
        <?php

        include("components/navbar.php");
      
        $sql = "SELECT id, nome, email, telefone, cargo FROM funcionario";
        $resultado = $db->query($sql);
        $resultado2 = $db->query($sql);
        ?>
    </header>
    <main>
        <div class="flex justify-content-center">
            <div class="admin-titulo d-flex justify-content-center align-items-center cores-background rounded-pill"
                data-background="azure-claro-fundo">
                <p class="admin-titulo-texto cores-color fw-bold mx-3" data-color="white">
                    Funcionários
                </p>
            </div>
        </div>
        <!-- Ãrea dos funcionarios -->
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
                    <?php while($adm = $resultado->fetch_assoc()) {
                        if ($adm['cargo'] == "admin") { ?>
                        <div>
                            <div class="flex centralizar-tabela">
                                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    <?php echo($adm['nome'] ) ?>
                                </p>
                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    <?php echo($adm['email'] ) ?>
                                </p>
                                <p class="border-tabela admin-tabela px-1 cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    <?php echo($adm['telefone'] ) ?>
                                </p>
                                <?php
                                if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'admin') echo '<a href="Usuario_Info.php?id='.$adm['id'].'" class="text-decoration-none"><button class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background" data-color="white"">Editar</button></a>' ;
                                if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'admin') {
                                    echo '<form method="POST" action="excluir_usuario.php" class="d-inline">';
                                    echo '<input type="hidden" name="id" value="'.$adm['id'].'">';
                                    echo '<button type="submit" class="admin-tabela-button border-tabela p-1 w-auto cores-color cores-background" data-color="white">Deletar</button>';
                                    echo '</form>';
                                }
                                ?>
                            </div>
                        </div>
                        <?php } } ?>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="text-center text-white p-3">
                <button id="botao-func" class="btn text-white fw-bold" type="button">
                    <div class="d-flex align-items-center">
                        funcionario
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta-para-baixo do admin"
                            class="users-page-arrow" id="seta-admin">
                    </div>
                </button>
                <div class="collapse" id="lista-func">
                    <?php while($user = $resultado2->fetch_assoc()) {
                        if ($user['cargo'] == "funcionario") { ?>
                        <div>
                            <div class="flex centralizar-tabela">
                                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    <?php echo($user['nome'] ) ?>
                                </p>
                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    <?php echo($user['email'] ) ?>
                                </p>
                                <p class="border-tabela admin-tabela px-1 cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    <?php echo($user['telefone'] ) ?>
                                </p>
                            <?php
                                if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'admin') echo '<a href="Usuario_Info.php?id='.$user['id'].'" class= "text-decoration-none"><button class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background" data-color="white" data-bs-toggle="modal" data-bs-target="#ModalEditarUsuario">Editar</button></a>';

                              
                               if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'admin') {
                                    echo '<form method="POST" action="excluir_usuario.php" class="d-inline">';
                                    echo '<input type="hidden" name="id" value="'.$user['id'].'">';
                                    echo '<button type="submit" class="admin-tabela-button border-tabela p-1 w-auto cores-color cores-background" data-color="white">Deletar</button>';
                                    echo '</form>';
                                }
                                ?>
                            </div>
                        </div>
                        <?php } } ?>
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