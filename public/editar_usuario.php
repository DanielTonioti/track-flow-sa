<?php
session_start();
require_once 'components/start.php';
require_once '../infra/conn.php';

redirecionarSeNaoAdmin();

$erro = "";

// Recupera o ID tanto pela URL quanto pelo formulário
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

$excluir_erro = isset($_GET['excluir_erro']);

if ($excluir_erro) {
    $erro = "Não foi possível excluir o funcionário: há pelo menos um trem cadastrado sob responsabilidade dele(a). Reatribua o trem antes de remover a conta.";
}

if ($id <= 0) {
    header("Location: Admin.php");
    exit();
}

// Busca os dados atuais do funcionário
$sql = "SELECT * FROM funcionario WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$user = $resultado->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: Admin.php");
    exit();
}

if (isset($_POST['CadastrarUsuario'])) {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['password'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');
    $acesso = $_POST['acesso'] ?? '';

    $cep = trim($_POST['cep'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $rua = trim($_POST['rua'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $uf = strtoupper(trim($_POST['uf'] ?? ''));

    $arquivo = $_FILES['avatar'] ?? null;

    $extensoesPermitidas = [
        'image/png' => 'png',
        'image/jpeg' => 'jpg'
    ];

    $caminhoAvatar = $user['avatar'] ?? null;
    $destino = null;

    // Validação dos campos
    if ($nome === "") {
        $erro = "O nome é obrigatório.";
    } elseif (mb_strlen($nome) < 3 || mb_strlen($nome) > 100) {
        $erro = "O nome deve ter entre 3 e 100 caracteres.";
    } elseif (!preg_match("/^[A-Za-zÀ-ÿ\s]+$/u", $nome)) {
        $erro = "O nome deve conter apenas letras e espaços.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Digite um e-mail válido.";
    } elseif (strlen($senha) > 0 && strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } elseif (!preg_match("/^[0-9]{10,11}$/", $telefone)) {
        $erro = "O telefone deve conter apenas números e ter 10 ou 11 dígitos.";
    } elseif ($cep === '') {
        $erro = "CEP é obrigatório.";
    } elseif (!preg_match('/^[0-9]+$/', $cep)) {
        $erro = "O CEP deve conter apenas números.";
    } elseif (strlen($cep) !== 8) {
        $erro = "O CEP deve conter exatamente 8 números.";
    } elseif ($cidade === '') {
        $erro = "Cidade é obrigatória.";
    } elseif ($rua === '') {
        $erro = "Rua é obrigatória.";
    } elseif ($bairro === '') {
        $erro = "Bairro é obrigatório.";
    } elseif ($uf === '') {
        $erro = "Estado é obrigatório.";
    } elseif ($acesso !== "funcionario" && $acesso !== "admin") {
        $erro = "Selecione um nível de acesso válido.";
    }

    // Verifica se o e-mail já pertence a outro usuário
    if ($erro === "") {
        $stmt = $db->prepare(
            "SELECT id FROM funcionario WHERE email = ? AND id <> ?"
        );
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();

        $existe = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        if ($existe) {
            $erro = "Este e-mail já está cadastrado para outro usuário.";
        }
    }

    // Validação da nova foto, caso tenha sido enviada
    if (
        $erro === "" &&
        $arquivo !== null &&
        $arquivo['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($arquivo['error'] !== UPLOAD_ERR_OK) {
            $erro = "Erro no envio da imagem.";
        } elseif ($arquivo['size'] > 5 * 1024 * 1024) {
            $erro = "A imagem deve ter no máximo 5 MB.";
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $tipoMime = $finfo->file($arquivo['tmp_name']);

            if (
                !isset($extensoesPermitidas[$tipoMime]) ||
                getimagesize($arquivo['tmp_name']) === false
            ) {
                $erro = "Tipo de arquivo inválido. Use PNG ou JPG.";
            } else {
                $pastaFisica = __DIR__ . "/../assets/images/uploads/";

                if (
                    !is_dir($pastaFisica) &&
                    !mkdir($pastaFisica, 0755, true)
                ) {
                    $erro = "Não foi possível criar a pasta de imagens.";
                } else {
                    $novoNome = bin2hex(random_bytes(16))
                        . "."
                        . $extensoesPermitidas[$tipoMime];

                    $destino = $pastaFisica . $novoNome;

                    if (
                        move_uploaded_file(
                            $arquivo['tmp_name'],
                            $destino
                        )
                    ) {
                        $caminhoAvatar =
                            "assets/images/uploads/" . $novoNome;
                    } else {
                        $erro = "Erro ao salvar a imagem no servidor.";
                        $destino = null;
                    }
                }
            }
        }
    }

    // Atualiza os dados no banco
    if ($erro === "") {

        // Se a senha ficar vazia, mantém o hash atual
        if ($senha !== '') {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        } else {
            $senhaHash = $user['senha'];
        }

        $sql = "UPDATE funcionario
                SET nome = ?,
                    cep = ?,
                    cidade = ?,
                    rua = ?,
                    bairro = ?,
                    uf = ?,
                    email = ?,
                    senha = ?,
                    telefone = ?,
                    cargo = ?,
                    avatar = ?
                WHERE id = ?";

        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            $erro = "Erro ao preparar a atualização do usuário.";
        } else {
            // Todos os campos são strings, exceto o ID
            $stmt->bind_param(
                "sssssssssssi",
                $nome,
                $cep,
                $cidade,
                $rua,
                $bairro,
                $uf,
                $email,
                $senhaHash,
                $telefone,
                $acesso,
                $caminhoAvatar,
                $id
            );

            if ($stmt->execute()) {
                $stmt->close();

                // Atualiza os dados da sessão se for o próprio usuário
                if ((int) ($_SESSION['id'] ?? 0) === $id) {
                    $_SESSION['usuario'] = $nome;
                    $_SESSION['email'] = $email;
                    $_SESSION['telefone'] = $telefone;
                    $_SESSION['cargo'] = $acesso;
                    $_SESSION['avatar'] = $caminhoAvatar;
                }

                // Remove a foto antiga somente depois da atualização
                if (
                    $destino !== null &&
                    !empty($user['avatar']) &&
                    $user['avatar'] !== $caminhoAvatar
                ) {
                    $fotoAntiga = __DIR__ . "/../"
                        . ltrim($user['avatar'], '/');

                    if (
                        is_file($fotoAntiga) &&
                        realpath(dirname($fotoAntiga)) ===
                        realpath(__DIR__ . "/../assets/images/uploads")
                    ) {
                        unlink($fotoAntiga);
                    }
                }

                // Impede que um funcionário continue na área administrativa
                if (
                    (int) ($_SESSION['id'] ?? 0) === $id &&
                    $acesso !== 'admin'
                ) {
                    header("Location: hub.php");
                    exit();
                }

                header("Location: Admin.php");
                exit();

            } else {
                $erro = "Erro ao atualizar o usuário.";

                $stmt->close();

                // Remove a nova foto se a atualização falhar
                if ($destino !== null && is_file($destino)) {
                    unlink($destino);
                }
            }
        }
    }

    // Atualiza os dados exibidos caso a validação falhe
    $user['nome'] = $nome;
    $user['email'] = $email;
    $user['telefone'] = $telefone;
    $user['cep'] = $cep;
    $user['cidade'] = $cidade;
    $user['rua'] = $rua;
    $user['bairro'] = $bairro;
    $user['uf'] = $uf;
    $user['cargo'] = $acesso;
    $user['avatar'] = $caminhoAvatar;
}

$pageTitle = 'Editar Funcionário';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">

<head>
    <?php include 'components/head.php'; ?>
</head>

<body class="ops-app-body">

    <header>
        <?php include "components/navbar.php"; ?>
    </header>

    <main class="ops-shell" style="max-width: 960px;">

        <div class="ops-page-header">
            <div>
                <nav class="ops-breadcrumb" aria-label="Breadcrumb">
                    <a href="hub.php">Dashboard</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <a href="Admin.php">Funcionários</a>
                    <span class="ops-breadcrumb-sep">/</span>
                    <span>Editar <?= sprintf('#OP-%03d', (int) $user['id']) ?></span>
                </nav>
                <h1 class="ops-page-title">Editar Funcionário</h1>
            </div>
            <div class="ops-header-actions">
                <a href="Admin.php" class="ops-btn ops-btn-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Voltar
                </a>
                <a href="Usuario_Info.php?id=<?= (int) $user['id'] ?>" class="ops-btn ops-btn-ghost">
                    Ver Perfil
                </a>
            </div>

        </div>

        <?php if ($erro !== ""): ?>
            <div class="ops-alert ops-alert-danger" role="alert">
                <i class="bi bi-exclamation-octagon-fill" aria-hidden="true"></i>
                <div>
                    <strong class="d-block">Não foi possível concluir a operação</strong>
                    <span><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" id="CadastrarUsuario" enctype="multipart/form-data" class="ops-panel mb-4">
            <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">

            <div class="ops-panel-body p-3 p-md-4">

                <div class="ops-form-section-title">
                    Dados Pessoais e Acesso
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label class="ops-label" for="nome">Nome completo <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="text"
                            class="ops-input"
                            name="nome"
                            id="nome"
                            minlength="3"
                            maxlength="100"
                            pattern="[A-Za-zÀ-ÿ\s]+"
                            value="<?= htmlspecialchars($user['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="ops-label" for="email">E-mail corporativo <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="email"
                            class="ops-input"
                            name="email"
                            id="email"
                            maxlength="100"
                            value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="ops-label" for="senha">Nova senha (opcional)</label>
                        <div class="ops-password-wrap">
                            <input
                                type="password"
                                class="ops-input"
                                name="password"
                                id="senha"
                                minlength="6"
                                maxlength="255"
                                placeholder="Deixe em branco para manter a senha atual">
                            <button type="button" class="ops-password-toggle" id="btn-toggle-senha-edit" aria-label="Mostrar senha">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        <span class="ops-help">Preencha apenas se desejar redefinir a senha deste operador.</span>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="ops-label" for="telefone">Telefone com DDD <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="text"
                            class="ops-input mono-font"
                            name="telefone"
                            id="telefone"
                            minlength="10"
                            maxlength="11"
                            inputmode="numeric"
                            pattern="[0-9]{10,11}"
                            value="<?= htmlspecialchars($user['telefone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                </div>

                <div class="ops-form-section-title">
                    Endereço
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-5 col-md-4">
                        <label class="ops-label" for="cep">CEP <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="text"
                            class="ops-input mono-font"
                            name="cep"
                            id="cep"
                            maxlength="8"
                            minlength="8"
                            pattern="[0-9]{8}"
                            inputmode="numeric"
                            value="<?= htmlspecialchars($user['cep'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            onblur="pesquisacep(this.value);"
                            aria-describedby="cep-feedback"
                            required>
                        <span id="cep-feedback" class="ops-help d-none" role="status" aria-live="polite"></span>
                    </div>

                    <div class="col-8 col-sm-5 col-md-6">
                        <label class="ops-label" for="cidade">Cidade <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="text"
                            class="ops-input"
                            name="cidade"
                            id="cidade"
                            value="<?= htmlspecialchars($user['cidade'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="col-4 col-sm-2 col-md-2">
                        <label class="ops-label" for="uf">UF <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="text"
                            class="ops-input mono-font text-uppercase"
                            name="uf"
                            id="uf"
                            maxlength="2"
                            value="<?= htmlspecialchars($user['uf'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="col-12 col-md-7">
                        <label class="ops-label" for="rua">Rua / Logradouro <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="text"
                            class="ops-input"
                            name="rua"
                            id="rua"
                            value="<?= htmlspecialchars($user['rua'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="col-12 col-md-5">
                        <label class="ops-label" for="bairro">Bairro <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="text"
                            class="ops-input"
                            name="bairro"
                            id="bairro"
                            value="<?= htmlspecialchars($user['bairro'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                </div>

                <div class="ops-form-section-title">
                    Cargo e Foto de Perfil
                </div>

                <div class="row g-3 align-items-start">
                    <div class="col-12 col-md-7">
                        <span class="ops-label">Nível de acesso <span class="ops-required" aria-hidden="true">*</span></span>
                        <div class="ops-radio-grid">
                            <label class="ops-radio-card" for="funcionario">
                                <input
                                    type="radio"
                                    name="acesso"
                                    id="funcionario"
                                    value="funcionario"
                                    <?= (($user['cargo'] ?? '') === 'funcionario') ? 'checked' : '' ?>
                                    required>
                                <span>
                                    <strong class="d-block text-white small">Funcionário / Operador</strong>
                                    <span class="text-steel" style="font-size: 0.76rem;">Acesso de consulta e acompanhamento do sistema.</span>
                                </span>
                            </label>

                            <label class="ops-radio-card" for="administrador">
                                <input
                                    type="radio"
                                    name="acesso"
                                    id="administrador"
                                    value="admin"
                                    <?= (($user['cargo'] ?? '') === 'admin') ? 'checked' : '' ?>>
                                <span>
                                    <strong class="d-block text-white small">Administrador</strong>
                                    <span class="text-steel" style="font-size: 0.76rem;">Permissão administrativa integral.</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-md-5">
                        <label class="ops-label" for="avatar">Foto de perfil</label>
                        <?php if (!empty($user['avatar'])): ?>
                            <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded-1" style="background: var(--ops-ground); border: 1px solid var(--ops-border);">
                                <img
                                    src="../<?= htmlspecialchars(ltrim((string) $user['avatar'], '/'), ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Foto atual do usuário"
                                    width="48"
                                    height="48"
                                    class="rounded-circle object-fit-cover"
                                    onerror="this.onerror=null; this.src='../assets/icons/User.png';">
                                <span class="small text-steel">Foto atual registrada</span>
                            </div>
                        <?php endif; ?>

                        <input
                            type="file"
                            class="ops-input"
                            id="avatar"
                            name="avatar"
                            accept=".png,.jpg,.jpeg,image/png,image/jpeg">
                        <span class="ops-help">PNG ou JPG até 5 MB. Deixe vazio para manter a foto atual.</span>
                    </div>
                </div>

            </div>

            <div class="ops-panel-footer">
                <a href="Admin.php" class="ops-btn ops-btn-ghost">Cancelar</a>
                <button type="submit" name="CadastrarUsuario" value="Salvar" class="ops-btn ops-btn-primary">
                    Salvar Alterações
                </button>
            </div>
        </form>

        <section class="ops-danger-zone" aria-labelledby="titulo-danger-zone">
            <div>
                <h2 id="titulo-danger-zone" class="h6 fw-bold text-white mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-danger" aria-hidden="true"></i>Zona de Exclusão de Conta
                </h2>
                <p class="small text-steel mb-0">
                    Remove permanentemente o acesso de <strong class="text-white"><?= htmlspecialchars($user['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>. Contas vinculadas como responsáveis por trens ativos são protegidas contra exclusão acidental.
                </p>
            </div>

            <button
                type="button"
                id="botaoExcluirUsuario"
                class="ops-btn ops-btn-danger-outline"
                data-bs-toggle="modal"
                data-bs-target="#modalConfirmarExclusaoUsuario">
                Excluir Funcionário
            </button>
        </section>

    </main>

    <div class="modal fade ops-modal" id="modalConfirmarExclusaoUsuario" tabindex="-1" aria-labelledby="modalExclusaoUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5 fw-bold text-white d-flex align-items-center gap-2" id="modalExclusaoUsuarioLabel">
                        <i class="bi bi-exclamation-octagon-fill text-danger" aria-hidden="true"></i>Confirmar Exclusão
                    </h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">
                        Tem certeza de que deseja remover permanentemente o cadastro de <strong class="text-white"><?= htmlspecialchars($user['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong> (<code><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></code>)?
                    </p>
                    <p class="small text-steel mb-0">
                        Esta operação não poderá ser desfeita.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="ops-btn ops-btn-ghost" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" action="excluir_usuario.php?id=<?= (int) $user['id'] ?>" id="excluirUsuario" class="m-0">
                        <input type="hidden" name="id_excluir" value="<?= (int) $user['id'] ?>">
                        <button type="submit" class="ops-btn ops-btn-danger">
                            Confirmar Exclusão
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../scripts/script_cep.js"></script>
    <script src="../scripts/script_navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('btn-toggle-senha-edit');
            const input = document.getElementById('senha');
            if (btn && input) {
                btn.addEventListener('click', function () {
                    const oculto = input.type === 'password';
                    input.type = oculto ? 'text' : 'password';
                    btn.setAttribute('aria-label', oculto ? 'Ocultar senha' : 'Mostrar senha');
                    const icon = btn.querySelector('i');
                    if (icon) icon.className = oculto ? 'bi bi-eye-slash' : 'bi bi-eye';
                });
            }
        });
    </script>

</body>
</html>
