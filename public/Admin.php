<?php 
include("components/start.php");
if ($_SESSION['cargo'] !== 'admin') {
    header('Location: hub.php');
    exit();
}

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
        include_once("../infra/conn.php");
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
                                <p class="border-tabela admin-tabela px-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    <?php echo($adm['telefone'] ) ?>
                                </p>
                                <button
                                    class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-bs-toggle="modal" data-bs-target="#ModalEditarUsuario">
                                    Editar
                                </button>
                                <button
                                    class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-bs-toggle="modal" data-bs-target="#ModalDeletarUsuario">
                                    deletar
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- <span>Marlon</span>
                        <span>Maria</span>
                        <span>Vanessa</span>
                        <span>Maicon</span> -->
                </div>
                <?php } } ?>
            </div>
        </div>

        <div>
            <div class="text-center text-white p-3">
                <button id="botao-admin" class="btn text-white fw-bold" type="button">
                    <div class="d-flex align-items-center">
                        Operadores
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta-para-baixo do admin"
                            class="users-page-arrow" id="seta-admin">
                    </div>
                </button>
                <div class="collapse" id="lista-adm">
                    <?php while($user = $resultado2->fetch_assoc()) {
                        if ($user['cargo'] == "operador") { ?>
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
                                <p class="border-tabela admin-tabela px-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    <?php echo($user['telefone'] ) ?>
                                </p>
                                <button
                                    class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-bs-toggle="modal" data-bs-target="#ModalEditarUsuario">
                                    Editar
                                </button>
                                <button
                                    class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-bs-toggle="modal" data-bs-target="#ModalDeletarUsuario">
                                    deletar
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- <span>Marlon</span>
                        <span>Maria</span>
                        <span>Vanessa</span>
                        <span>Maicon</span> -->
                </div>
                <?php } } ?>
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
    <div class="modal fade" id="ModalEditarUsuario" tabindex="-1" aria-labelledby="TituloModalEditarUsuario"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content cores-background cores-color" data-background="cinza-claro" data-color="black">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="TituloModalEditarUsuario">Editar usuario</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="NomeUsuarioModal">Nome</label>
                            <input class="form-control" id="NomeUsuarioModal" type="text" value="Marlon">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="EmailUsuarioModal">E-mail</label>
                            <input class="form-control" id="EmailUsuarioModal" type="email" value="marlon@email.com">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="SenhaUsuarioModal">Senha</label>
                            <input class="form-control" id="SenhaUsuarioModal" type="password">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="AcessoUsuarioModal">Nivel de acesso</label>
                            <select class="form-select" id="AcessoUsuarioModal">
                                <option>Funcionário</option>
                                <option>Administrador</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade CadastroTremModal" id="ModalDeletarUsuario" tabindex="-1"
        aria-labelledby="TituloModalDeletarSensor" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content CadastroTremModalBox cores-background cores-color" data-background="cinza-claro"
                data-color="black">
                <h2 id="TituloModalDeletarSensor">Certeza que deseja<br> excluir dados do funcionario:<br>
                    <span>???</span>.
                </h2>
                <div class="CadastroTremModalButtons">
                    <button type="button" id="ConfirmarExclusaoSensor" data-bs-dismiss="modal">Confirmar</button>
                    <button type="button" data-bs-dismiss="modal">voltar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Hidden form to submit sensor deletion to server -->
    <form id="deleteSensorForm" action="excluir_usuario.php" method="POST" style="display:none;">
        <input type="hidden" name="id" id="deleteSensorId" value="">
    </form>
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