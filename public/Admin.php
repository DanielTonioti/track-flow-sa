<?php
session_start();
include_once("../infra/conn.php");
include("components/start.php");
$sql = "SELECT id, nome, email, telefone, cargo FROM funcionario";
$resultado = $db->query($sql);
$resultado2 = $db->query($sql);
?>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
        <div class="d-flex justify-content-center">
            <div class="admin-titulo d-flex justify-content-center align-items-center cores-background rounded-pill py-2"
                data-background="azure-claro-fundo">
                <p class="admin-titulo-texto cores-color fw-bold m-0" data-color="white">
                    Funcionários
                </p>
            </div>
        </div>
        <!-- Area dos funcionarios -->
        <div>
            <div class="text-center text-white p-3">

                <button id="botao-admin" data-bs-toggle="collapse" role="button" class="btn text-white fw-bold"
                    type="button">

                    <div class="d-flex align-items-center justify-content-center">
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
                                <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'admin') { ?>
                                <p class="border-tabela admin-tabela px-1 cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    <?php echo htmlspecialchars($adm['telefone']); ?>
                                </p>

                                    <a href="editar_usuario.php?id=<?= (int) $adm['id'] ?>"
                                        class="admin-tabela-button border-tabela p-1 w-auto cores-color cores-background d-inline-block text-decoration-none"
                                        data-color="white" data-background="azure-claro-fundo">
                                        Editar
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

                    <div class="d-flex align-items-center justify-content-center">Funcionários
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
                                <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'admin') { ?>
                                <p class="border-tabela admin-tabela px-1 cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    <?php echo htmlspecialchars($user['telefone']); ?>
                                </p>
                                    <a href="editar_usuario.php?id=<?= (int) $user['id'] ?>"
                                        class="admin-tabela-button border-tabela p-1 w-auto cores-color cores-background d-inline-block text-decoration-none"
                                        data-color="white" data-background="azure-claro-fundo">
                                        Editar
                                    </a>

                                <?php } ?>

                            </div>

                        <?php }
                    } ?>

                </div>
            </div>
        </div>


        <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'admin') { ?>
            <div class="cores-color cores-background d-flex justify-content-center" data-color="white">
                <a href="cadastro_usuario.php"
                    class="admin-button cores-color cores-background rounded-pill mt-1 d-flex justify-content-center align-items-center text-decoration-none px-4"
                    data-color="white" data-background="azure-claro-fundo" id="admin-button">
                    Adicionar Funcionário
                </a>
            </div>
        <?php } ?>
        <a href="hub.php" class="btn btn-danger back-buttom">Voltar</a>
    </main>
    <script src="../scripts/script_user_page.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
</body>

</html>