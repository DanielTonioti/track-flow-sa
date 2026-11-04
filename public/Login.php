<?php
session_start();
include "../infra/conn.php";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["usuario"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($email === "" || $senha === "") {
        $erro = "Informe seu e-mail corporativo e sua senha de acesso.";
    } else {
        $sql = "SELECT id, nome, email, telefone, cargo, avatar, senha FROM funcionario WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $funcionario = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($funcionario && password_verify($senha, $funcionario["senha"])) {
            $_SESSION["id"] = $funcionario["id"];
            $_SESSION["usuario"] = $funcionario["nome"];
            $_SESSION["email"] = $funcionario["email"];
            $_SESSION["telefone"] = $funcionario["telefone"];
            $_SESSION["cargo"] = $funcionario["cargo"];
            $_SESSION["avatar"] = $funcionario["avatar"] ?? null;

            header("Location: hub.php");
            exit();
        } else {
            $erro = "Credenciais inválidas. Verifique o e-mail e a senha informados.";
        }
    }
}

$pageTitle = 'Autenticação de Operador';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">

<head>
    <?php include 'components/head.php'; ?>
</head>

<body class="ops-app-body">
    <main class="ops-login-layout">

        <section class="ops-login-showcase" aria-label="TrackFlow">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative" style="z-index: 1;">
                <a href="../index.php" class="d-inline-flex align-items-center gap-2 text-decoration-none">
                    <span class="ops-brand-mark">
                        <img src="../assets/logo/logo-track-flow.png" alt="Logo TrackFlow" class="ops-logo-invert" width="24" height="24">
                    </span>
                    <span class="d-flex flex-column lh-1">
                        <span class="brand-font fw-bold fs-4 text-white">TrackFlow</span>
                        <span class="ops-brand-sub mono-font">MONITORAMENTO FERROVIÁRIO</span>
                    </span>
                </a>

                <a href="../index.php" class="ops-btn ops-btn-ghost ops-btn-sm">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Página Inicial
                </a>
            </div>

            <div class="my-4 my-lg-auto py-4 position-relative" style="z-index: 1; max-width: 520px;">
                <h1 class="brand-font fw-bold text-white mb-0" style="font-size: clamp(2.4rem, 1.8rem + 2.5vw, 3.75rem); line-height: 1.08; letter-spacing: -0.025em;">
                    No <span class="text-volt">ritmo</span> que move estados.
                </h1>
            </div>

            <div class="d-none d-lg-block" aria-hidden="true"></div>
        </section>

        <section class="ops-login-panel-wrap" aria-labelledby="login-heading">
            <div class="ops-login-card">
                <div class="mb-4">
                    <span class="mono-font text-cyan text-uppercase d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.08em;">
                        Acesso ao Sistema
                    </span>
                    <h2 id="login-heading" class="brand-font fw-bold text-white fs-2 mb-1">
                        Entrar no TrackFlow
                    </h2>
                    <p class="text-steel small mb-0">
                        Digite seu e-mail e sua senha para entrar no sistema.
                    </p>
                </div>

                <?php if ($erro !== ""): ?>
                    <div class="ops-alert ops-alert-danger" role="alert">
                        <i class="bi bi-exclamation-octagon-fill" aria-hidden="true"></i>
                        <div>
                            <strong class="d-block">Falha na autenticação</strong>
                            <span><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="ops-panel p-4" novalidate>
                    <div class="mb-3">
                        <label class="ops-label" for="usuario-email">
                            E-mail corporativo <span class="ops-required" aria-hidden="true">*</span>
                        </label>
                        <input
                            type="email"
                            class="ops-input"
                            id="usuario-email"
                            name="usuario"
                            placeholder="operador@trackflow.com.br"
                            value="<?= htmlspecialchars($_POST['usuario'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            autocomplete="email"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="ops-label" for="usuario-senha">
                            Senha <span class="ops-required" aria-hidden="true">*</span>
                        </label>
                        <div class="ops-password-wrap">
                            <input
                                type="password"
                                class="ops-input"
                                id="usuario-senha"
                                name="senha"
                                placeholder="Digite sua senha"
                                autocomplete="current-password"
                                required>
                        </div>
                    </div>

                    <button type="submit" class="ops-btn ops-btn-primary w-100 py-2 fs-6">
                        <span>Entrar no Sistema</span>
                    </button>

                    <div class="mt-3 pt-3 border-top border-light border-opacity-10 text-center">
                        <button
                            class="btn btn-link btn-sm text-steel text-decoration-none p-0"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#ajudaRecuperacaoSenha"
                            aria-expanded="false"
                            aria-controls="ajudaRecuperacaoSenha">
                            <i class="bi bi-question-circle me-1 text-cyan" aria-hidden="true"></i>Esqueceu a senha ou precisa de acesso?
                        </button>

                        <div class="collapse mt-2 text-start" id="ajudaRecuperacaoSenha">
                            <div class="p-2 rounded-1 small text-steel" style="background: var(--ops-ground); border: 1px solid var(--ops-border);">
                                Por segurança, entre em contato com a empresa para que possamos analisar.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_auth.js"></script>
</body>

</html>
