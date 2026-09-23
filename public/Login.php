<?php
session_start();
include "../infra/conn.php";
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE usuario = ? AND senha = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $usuario, $senha);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado && $resultado->num_rows > 0) {
            $_SESSION['usuario'] = $usuario;
            header("Location: hub.php");
            exit();
        } else {
            $erro = "Usuário ou senha inválidos!";
        }
        $stmt->close();
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
    <title>Tela de login</title>
</head>



<body>


    <main class="container-fluid p-0 min-vh-100">
        <div class="row g-0 min-vh-100 flex-column flex-md-row">
            <!-- Slogan e imagem -->
            <div class="col-12 col-md-7 d-flex flex-column align-items-center justify-content-center cores-background p-5"
                data-background="azure-escuro-fundo">
                <div class="text-center">
                    <h1 class="login-tamanho-titulo-slogan cores-color mb-4" data-color="white">
                        No <span class="cores-color" data-color="lime-green">ritmo</span> que move
                        <br><u>estados</u>
                    </h1>
                    <img class="userselect" src="../assets/logo/logo-track-flow.png" alt="Logo TrackFlow"
                        class="img-fluid login-img">
                </div>
            </div>
            <!-- Login Area -->
            <div class="vr d-none d-md-block cores-background p-1" data-background="cinza-claro"></div>
            <div class="d-block d-md-none border-bottom cores-background p-1" data-background="white"></div>
            <div class="col-12 col-md d-flex flex-column align-items-center justify-content-center cores-background py-5"
                data-background="azure-claro-fundo">
                <div class="col-10 col-lg-8">
                    <h1 class="text-light mb-5 text-center text-md-start fs-2 fw-bold">Entrar no TrackFlow</h1>
                    <form id="form-login" method="POST">
                        <div class="form-floating mb-4">
                            <input type="email"
                                class="form-control p-4 rounded border border-dark border-4 bg-transparent text-light"
                                id="usuario-email" placeholder="" name="usuario">
                            <label class="userselect" for="usuario-email">Email</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password"
                                class="form-control p-4 rounded border-dark border-4 bg-transparent text-light"
                                id="usuario-senha" placeholder="" name="senha">
                            <label class="userselect" for="usuario-senha">Senha</label>
                        </div>
                        <?php
                        if (isset($erro)) {
                            echo $erro;
                        };
                        ?>
                        <button id="botao-envio" type="submit"
                            class="userselect cores-background w-100 py-1 rounded-pill fs-4 text-light fw-bold login-button border-0 shadow"
                            data-background="azul-real">
                            Entrar
                        </button>
                        <p class="userselect toggle fs-5 text-center fw-bold text-light mt-4 cursor-pointer">
                            <u>Esqueceu a senha?</u>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="../scripts/scriptLogin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>