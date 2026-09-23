<?php
session_start();
include "../infra/conn.php";
$id = isset($_GET['id']) ?? $_GET['id'];
if ($id > 0) {
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title> Informações do usuario </title>
</head>

<body class="cores-background " data-background="azure-escuro-fundo">

    <head>
        <header>

            <?php

            include("components/navbar.php");

            ?>


        </header>

    </head>

    <main>
        <div class="blockcentro">
            <h1 class="cores-color" data-color="white">Editar Usuario: Placeholder</h1>
        </div>
        <form id="updateForm" action="atualizar_usuario.php" method="POST">
            <div class="blockcentro">
                <input type="hidden" name="id" value="<?php echo $user['id'] ?>">

                <label for="inputPassword5" class="cores-color form-label" data-color="white">Name</label>
                <input type="text" id="inputPassword5" class="FormUsuario form-control"
                    aria-describedby="passwordHelpBlock" name="NomeUpdate" value="<?php echo $user['nome'] ?>">
                <label for="inputPassword5" class="cores-color form-label" data-color="white">E-mail</label>
                <input type="email" id="inputPassword5" class="FormUsuario form-control"
                    aria-describedby="passwordHelpBlock" name="EmailUpdate" value="<?php echo $user['email'] ?>">
                <label for="inputPassword5" class="cores-color form-label" data-color="white">Senha</label>
                <input type="password" id="inputPassword5" class="FormUsuario form-control"
                    aria-describedby="passwordHelpBlock" name="PasswordUpdateRegular" value="">
                <label for="inputPassword5" class="cores-color form-label" data-color="white">Confirmar Senha</label>
                <input type="password" id="inputPassword5" class="FormUsuario form-control"
                    aria-describedby="passwordHelpBlock" name="PasswordUpdateConfirm" value="">
            </div>
            <div class="DangerZone">
                <div class="blockcentro">
                    <label for="inputPassword5" class="cores-color  form-label" data-color="white">Nivel de
                        Acesso</label>
                    <div>
                        <input type="radio" name="acesso" id="funcionario" value="1" <?php echo $user['acesso']?> required> Funcionario
                        <input type="radio" name="acesso" id="administrador" value="0" <?php echo $user['acesso'] ?>> Administrador
                    </div>
                    <div class="ButtonExcluirUsuario">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">Excluir Usuario</button>
                    </div>
                </div>
            </div>
            <div class="blockcentro">
                <button type="button" class="btn cores-color cores-background" data-color="white"
                    data-background="azure-claro-fundo" data-bs-toggle="modal" data-bs-target="#updateModal">Atualizar
                    usuario</button>
            </div>
        </form>
        <form id="deleteForm" action="excluir_usuario.php" method="POST" style="display:none;">
            <input type="hidden" name="id" value="<?php echo $user['id']?>">
        </form>

        <!-- initial access value for modal logic -->
        <input type="hidden" id="initialAcesso" value="<?php echo $user['acesso']?>">

        <!-- hidden access-change form -->
        <form id="accessForm" action="atualizar_usuario.php" method="POST" style="display:none;">
            <input type="hidden" name="id" value="<?php echo $user['id']?>">
            <input type="hidden" name="action" value="change_access">
            <input type="hidden" name="acesso" value="">
        </form>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmar exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Tem certeza que deseja excluir o usuário:
                        <strong><?php echo $user['nome']?></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="confirmDelete" class="btn btn-danger">Excluir</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">Confirmar alteração</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Tem certeza que deseja atualizar os dados do usuário:
                        <strong><?php echo $user['nome']?></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="confirmUpdate" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>




        <button id="voltarhub" class="btn btn-danger back-buttom"> Voltar </button>
    </main>
    <footer>

    </footer>
    <script src="../scripts/scriptVoltar.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/scriptUsuarioInfo.js"></script>

</body>

</html>