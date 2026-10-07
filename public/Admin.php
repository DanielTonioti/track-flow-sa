<?php
session_start();
include_once("../infra/conn.php");
include("components/start.php");
$sql = "SELECT id, nome, email, telefone, cargo FROM funcionario";
$stmt = $db->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt2 = $db->prepare($sql);
$stmt2->execute();
$resultado2 = $stmt2->get_result();
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
        <!-- Area dos funcionarios -->
        <div>
            <div class="text-center text-white p-3">

                <button id="botao-admin" data-bs-toggle="collapse" role="button" class="btn text-white fw-bold"
                    type="button">

                    <div class="d-flex align-items-center">
                        Administradores

                        <img src="../assets/icons/seta-para-baixo.png" alt="Seta para baixo dos administradores"
                            class="users-page-arrow" id="seta-admin">
                    </div>

                </button>

                <div class="collapse" id="lista-adm">

                    <?php while ($adm = $resultado->fetch_assoc()) {

                        if ($adm['cargo'] == "admin") { ?>

                            <div class="flex centralizar-tabela">

                                <p class="border-tabela admin-tabela tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    <?php echo htmlspecialchars($adm['nome']); ?>
                                </p>

                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    <?php echo htmlspecialchars($adm['email']); ?>
                                </p>

                                <p class="border-tabela admin-tabela px-1 cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    <?php echo htmlspecialchars($adm['telefone']); ?>
                                </p>

                                <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'admin') { ?>

                                    <a href="editar_usuario.php?id=<?= $adm['id'] ?>" class="text-decoration-none">

                                        <button class="admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                            data-color="white" data-background="azure-claro-fundo">
                                            Editar
                                        </button>

                                    </a>

                                <?php } ?>

                            </div>

                        <?php }
                    } ?>

                </div>
            </div>
        </div>

        <div>
            <div class="text-center text-white p-3">

                <button id="botao-func" data-bs-toggle="collapse" role="button" class="btn text-white fw-bold"
                    type="button">

                    <div class="d-flex align-items-center">Funcionários
                        <img src="../assets/icons/seta-para-baixo.png" alt="Seta para baixo dos funcionários"
                            class="users-page-arrow" id="seta-func">
                    </div>

                </button>

                <div class="collapse" id="lista-func">

                    <?php while ($user = $resultado2->fetch_assoc()) {

                        if ($user['cargo'] == "funcionario") { ?>

                            <div class="flex centralizar-tabela">

                                <p class="border-tabela admin-tabela tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    <?php echo htmlspecialchars($user['nome']); ?>
                                </p>

                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </p>

                                <p class="border-tabela admin-tabela px-1 cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    <?php echo htmlspecialchars($user['telefone']); ?>
                                </p>

                                <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'admin') { ?>

                                    <a href="editar_usuario.php?id=<?= $user['id'] ?>" class="text-decoration-none">

                                        <button class="admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                            data-color="white" data-background="azure-claro-fundo">
                                            Editar
                                        </button>

                                    </a>

                                <?php } ?>

                            </div>

                        <?php }
                    } ?>

                </div>
            </div>
        </div>


        <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'admin') { ?>
            <div class="cores-color cores-background centralizar-tabela flex" data-color="white">
                <button
                    class="admin-button cores-color cores-background rounded-pill mt-1 align-items-center justify-content-center text-center"
                    data-color="white" data-background="azure-claro-fundo" id="admin-button">
                    Adicionar Funcionário
                </button>
            </div>
        <?php } ?>
        <?php include("components/back_button.php"); ?>
    </main>

    <script src="../scripts/scriptUsersPage.js"></script>
    <script src="../scripts/scriptVoltar.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>