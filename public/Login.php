<?php
session_start();
include "../infra/conn.php";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["usuario"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($email === "" || $senha === "") {
        $erro = "Informe email e senha.";
    } else {
        $sql = "SELECT id, nome, email, telefone, cargo, senha FROM funcionario WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $funcionario = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($funcionario && $senha === $funcionario["senha"]) {
            $_SESSION["id"] = $funcionario["id"];
            $_SESSION["usuario"] = $funcionario["nome"];
            $_SESSION["email"] = $funcionario["email"];
            $_SESSION["telefone"] = $funcionario["telefone"];
            $_SESSION["cargo"] = $funcionario["cargo"];

            header("Location: hub.php");
            exit();
        } else {
            $erro = "Email ou senha incorreta.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Tela de login</title>

</head>

<body>
    <main class="container-fluid p-0 min-vh-100">

        <div class="row g-0 min-vh-100 flex-column flex-md-row">

            <div class="col-12 col-md-7 d-flex flex-column align-items-center justify-content-center cores-background p-5"
                data-background="azure-escuro-fundo">

                <div class="text-center">

                    <h1 class="login-tamanho-titulo-slogan cores-color mb-4" data-color="white">
                        No
                        <span class="cores-color" data-color="lime-green">
                            ritmo
                        </span>
                        que move
                        <br>
                        <u>estados</u>
                    </h1>

                    <img src="../assets/logo/logo-track-flow.png" alt="Logo TrackFlow" class="img-fluid login-img">
                </div>
            </div>
            <div class="vr d-none d-md-block cores-background p-1" data-background="cinza-claro"></div>

            <div class="col-12 col-md d-flex flex-column align-items-center justify-content-center cores-background py-5"
                data-background="azure-claro-fundo">

                <div class="col-10 col-lg-8">

                    <h1 class="text-light mb-5 text-center text-md-start fs-2 fw-bold">
                        Entrar no TrackFlow
                    </h1>

                    <form method="POST" action="">

                        <div class="form-floating mb-4">

                            <input type="email"
                                class="form-control p-4 rounded border border-dark border-4 bg-transparent text-light"
                                id="usuario-email" placeholder="Email" name="usuario" required>

                            <label class="userselect" for="usuario-email">
                                Email
                            </label>

                        </div>

                        <!-- SENHA -->

                        <div class="form-floating mb-4">

                            <input type="password"
                                class="form-control p-4 rounded border-dark border-4 bg-transparent text-light"
                                id="usuario-senha" placeholder="Senha" name="senha" required>
                            <label class="userselect" for="usuario-senha">
                                Senha
                            </label>

                        </div>

                        <?php if ($erro !== ""): ?>

                            <div class="alert alert-danger text-center">
                                <?= htmlspecialchars($erro) ?>
                            </div>

                        <?php endif; ?>

                        <button type="submit"
                            class="userselect cores-background w-100 py-1 rounded-pill fs-4 text-light fw-bold login-button border-0 shadow"
                            data-background="azul-real">
                            Entrar
                        </button>

                        <p class="userselect toggle fs-5 text-center fw-bold text-light mt-4 cursor-pointer">
                            <a class="text-light" href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">
                                Esqueceu a senha?
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
