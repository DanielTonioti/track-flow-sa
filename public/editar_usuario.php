<?php
session_start();
require_once '../infra/conn.php';

if (($_SESSION['cargo'] ?? '') !== 'admin') {
    header("Location: Login.php");
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
    <title>Editar usuário</title>
</head>

<body class="cores-background " data-background="azure-escuro-fundo" class=" cores-background "
    data-background="azure-escuro-fundo">
    <header>
        <?php

        include "components/navbar.php";
        require_once "../infra/conn.php";

        $erro = "";
        if(isset($_GET["id"])){

            $id = $_GET["id"];
            $sql = "SELECT * FROM funcionario WHERE id = $id";
            $resultado = $db->query($sql);
            $user =mysqli_fetch_assoc($resultado);

        }

if (isset($_POST['CadastrarUsuario'])) {
    $id = $_POST['id'];
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['password'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');
    $acesso = $_POST['acesso'] ?? '';

    if ($nome === "") {
        $erro = "O nome é obrigatório.";
    } elseif (strlen($nome) < 3 || strlen($nome) > 100) {
        $erro = "O nome deve ter entre 3 e 100 caracteres.";
    } elseif (!preg_match("/^[A-Za-zÀ-ÿ\s]+$/u", $nome)) {
        $erro = "O nome deve conter apenas letras e espaços.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Digite um e-mail válido.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } elseif (!preg_match("/^[0-9]{10,11}$/", $telefone)) {
        $erro = "O telefone deve conter apenas números e ter 10 ou 11 dígitos.";
    } elseif ($acesso !== "funcionario" && $acesso !== "admin") {
        $erro = "Selecione um nível de acesso válido.";
    }

    if ($erro === "") {


            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "UPDATE funcionario SET nome = ?, email = ?, senha = ?, telefone = ?, cargo = ? WHERE id = $id;";

            $stmt = $db->prepare($sql);
            $stmt->bind_param(
                "sssss",
                $nome,
                $email,
                $senhaHash,
                $telefone,
                $acesso
            );

            if ($stmt->execute()) {
                header("Location: Admin.php");
                exit();
            } else {
                $erro = "Erro ao cadastrar o usuário.";
            }
            $stmt->close();
        }

    }

        ?>
    </header>

    <main>
        <form method="POST" id="CadastrarUsuario">
            <div class="blockcentro titulo-Sensor">
                <div class="cores-background p-2 rounded-4" data-background="azure-claro-fundo">
                    <h2 class="titulo-Sensor">Editar Usuário</h2>
                </div>
                <br>
                <div id="valortipo" class="cores-background p-4 w-25 rounded-4" data-background="azure-claro-fundo">
                    <br>
                    <div class="column-sensor" id="tipos">
                        <input type="hidden" name="id" value="<?php echo $user['id']?>">
                        <label for="nome"> Nome: </label>
                        <input type="text" name="nome" value="<?php echo $user['nome']?>" required>
                        <label for="nome"> E-mail: </label>
                        <input type="email" name="email"  value="<?php echo $user['email']?>" required>
                        <label for="senha"> Senha: </label>
                        <input type="password" name="password" required>
                        <label for="telefone"> Telefone: </label>
                        <input type="text" name="telefone" value="<?php echo $user['telefone']?>">
                        <label for="acesso"> Nível de acesso: </label>
                        <div>
                            <input type="radio" name="acesso" id="funcionario" value='funcionario' required> Funcionário
                            <input type="radio" name="acesso" id="administrador" value='admin'> Administrador
                        </div>
                    </div>
                </div>
                <br>
                <input type="submit" value="Salvar" name="CadastrarUsuario"
                    class="border-none-buttom cores-background titulo-Sensor p-2 rounded-3"
                    data-background="azure-claro-fundo">
                     <?php if ($erro !== "") { ?>

                    <div class="alert alert-danger mt-3">
                        <?= htmlspecialchars($erro) ?>
                    </div>

                <?php } ?>
            </div>
        </form>
       


        <script src="../scripts/"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>

        <button id="voltaradmin" class="btn btn-danger back-buttom"> Voltar </button>
    </main>
    <footer>

    </footer>

    <script src="../scripts/scriptvoltarAdmin.js"></script>
    <script src="../scripts/scriptSensor.js"></script>
    <script src="../scripts/scriptAdmin.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>