<?php
session_start();
require_once 'components/start.php';
require_once '../infra/conn.php';

redirecionarSeNaoAdmin();

$erro = "";
$sucesso = isset($_GET['sucesso']);

// Extensão definida pelo MIME real do arquivo (não pelo nome enviado)
$extensoesPermitidas = [
    'image/png' => 'png',
    'image/jpeg' => 'jpg',
];

if (isset($_POST['CadastrarUsuario'])) {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['password'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');
    $acesso = $_POST['acesso'] ?? '';
    $arquivo = $_FILES['avatar'] ?? null;
    $cep = trim($_POST['cep'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $rua = trim($_POST['rua'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $uf = strtoupper(trim($_POST['uf'] ?? ''));
    $tipoMime = '';
    $caminhoAvatar = null;

    // 1. Validação dos campos de texto
    if ($nome === "") {
        $erro = "O nome é obrigatório.";
    } elseif (mb_strlen($nome) < 3 || mb_strlen($nome) > 100) {
        $erro = "O nome deve ter entre 3 e 100 caracteres.";
    } elseif (!preg_match("/^[A-Za-zÀ-ÿ\s]+$/u", $nome)) {
        $erro = "O nome deve conter apenas letras e espaços.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Digite um e-mail válido.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } elseif (!preg_match("/^[0-9]{10,11}$/", $telefone)) {
        $erro = "O telefone deve conter apenas números e ter 10 ou 11 dígitos.";
    } elseif ($cep === '') {
        $erro = "O CEP é obrigatório.";
    } elseif (!preg_match('/^[0-9]+$/', $cep)) {
        $erro = "O CEP deve conter apenas números.";
    } elseif (strlen($cep) !== 8) {
        $erro = "O CEP deve conter exatamente 8 números.";
    } elseif ($cidade === '') {
        $erro = "A cidade é obrigatória.";
    } elseif ($rua === '') {
        $erro = "A rua é obrigatória.";
    } elseif ($bairro === '') {
        $erro = "O bairro é obrigatório.";
    } elseif ($uf === '') {
        $erro = "O estado (UF) é obrigatório.";
    } elseif ($acesso !== "funcionario" && $acesso !== "admin") {
        $erro = "Selecione um nível de acesso válido.";
    }

    // 2. Validação do avatar (ainda sem mover o arquivo)
    if ($erro === "" && $arquivo !== null && $arquivo['error'] !== UPLOAD_ERR_NO_FILE) {

        if ($arquivo['error'] !== UPLOAD_ERR_OK) {
            $erro = "Erro no envio da imagem.";

        } elseif ($arquivo['size'] > 5 * 1024 * 1024) {
            $erro = "Seu arquivo excede o tamanho máximo permitido (5 MB).";

        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $tipoMime = $finfo->file($arquivo['tmp_name']);

            if (
                !isset($extensoesPermitidas[$tipoMime]) || getimagesize($arquivo['tmp_name']) === false
            ) {
                $erro = "Tipo de arquivo inválido ou corrompido. Use PNG ou JPG.";
            }
        }
    }

    // 3. Banco de dados + upload (o arquivo só é movido depois de validar tudo)
    if ($erro === "") {

        $stmt = $db->prepare("SELECT id FROM funcionario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $existe = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        if ($existe) {
            $erro = "Este e-mail já está cadastrado.";
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $caminhoAvatar = null;
            $destino = null;

            // Upload somente se uma imagem foi enviada
            if (
                $arquivo !== null && $arquivo['error'] === UPLOAD_ERR_OK
            ) {
                $pastaFisica = __DIR__ . "/../assets/images/uploads/";

                if (
                    !is_dir($pastaFisica) && !mkdir($pastaFisica, 0755, true)
                ) {
                    $erro = "Não foi possível criar a pasta de imagens.";
                } else {
                    $novoNome = bin2hex(random_bytes(16)) . "." . $extensoesPermitidas[$tipoMime];

                    $destino = $pastaFisica . $novoNome;

                    if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
                        $erro = "Erro ao mover a imagem para o servidor.";
                    } else {
                        $caminhoAvatar = "assets/images/uploads/" . $novoNome;
                    }
                }
            }

            // Cadastra somente se o upload não apresentou erro
            if ($erro === "") {

                $stmt = $db->prepare("INSERT INTO funcionario(nome, cep, cidade, rua, bairro, uf, email, senha, telefone, cargo, avatar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param("sssssssssss", $nome, $cep, $cidade, $rua, $bairro, $uf, $email, $senhaHash, $telefone, $acesso, $caminhoAvatar);

                if ($stmt->execute()) {
                    $stmt->close();

                    header("Location: latitude.php");
                    exit();
                }

                $erro = "Erro ao cadastrar o usuário no banco de dados: " . $stmt->error;
                $stmt->close();

                // Remove a imagem se o cadastro no banco falhar
                if ($destino !== null && file_exists($destino)) {
                    unlink($destino);
                }
            }
        }
    }
}

$pageTitle = 'Cadastrar Funcionário';
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
                    <span>Novo Cadastro</span>
                </nav>
                <h1 class="ops-page-title">Cadastrar Funcionário</h1>
            </div>

            <div class="ops-header-actions">
                <a href="Admin.php" class="ops-btn ops-btn-ghost">
                    Voltar
                </a>
            </div>
        </div>

        <?php if ($erro !== ""): ?>
            <div class="ops-alert ops-alert-danger" role="alert">
                <div>
                    <strong class="d-block">Revise os dados informados</strong>
                    <span><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="ops-alert ops-alert-success" role="status">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 w-100">
                    <div>
                        <strong class="d-block">Funcionário cadastrado com sucesso</strong>
                    </div>
                    <a href="Admin.php" class="ops-btn ops-btn-sm">Ver Quadro de Equipe</a>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" id="CadastrarUsuario" enctype="multipart/form-data" class="ops-panel">
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
                            placeholder="Ex.: Mariana Souza"
                            value="<?= htmlspecialchars($_POST['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                        <span class="ops-help">Apenas letras e espaços (3 a 100 caracteres).</span>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="ops-label" for="email">E-mail corporativo <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="email"
                            class="ops-input"
                            name="email"
                            id="email"
                            maxlength="100"
                            placeholder="mariana.souza@trackflow.com.br"
                            value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="ops-label" for="senha">Senha inicial <span class="ops-required" aria-hidden="true">*</span></label>
                        <div class="ops-password-wrap">
                            <input
                                type="password"
                                class="ops-input"
                                name="password"
                                id="senha"
                                minlength="6"
                                maxlength="255"
                                placeholder="Mínimo de 6 caracteres"
                                required>
                        </div>
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
                            placeholder="47999999999"
                            value="<?= htmlspecialchars($_POST['telefone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                        <span class="ops-help">Somente números, 10 ou 11 dígitos sem traços.</span>
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
                            placeholder="89201000"
                            value="<?= htmlspecialchars($_POST['cep'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            onblur="pesquisacep(this.value);"
                            aria-describedby="cep-help cep-feedback"
                            required>
                        <span id="cep-help" class="ops-help">8 dígitos numéricos (autopreenche rua e cidade).</span>
                        <span id="cep-feedback" class="ops-help d-none" role="status" aria-live="polite"></span>
                    </div>

                    <div class="col-8 col-sm-5 col-md-6">
                        <label class="ops-label" for="cidade">Cidade <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="text"
                            class="ops-input"
                            name="cidade"
                            id="cidade"
                            placeholder="Joinville"
                            value="<?= htmlspecialchars($_POST['cidade'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
                            placeholder="SC"
                            value="<?= htmlspecialchars($_POST['uf'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="col-12 col-md-7">
                        <label class="ops-label" for="rua">Rua / Logradouro <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="text"
                            class="ops-input"
                            name="rua"
                            id="rua"
                            placeholder="Rua XV de Novembro"
                            value="<?= htmlspecialchars($_POST['rua'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="col-12 col-md-5">
                        <label class="ops-label" for="bairro">Bairro <span class="ops-required" aria-hidden="true">*</span></label>
                        <input
                            type="text"
                            class="ops-input"
                            name="bairro"
                            id="bairro"
                            placeholder="Centro"
                            value="<?= htmlspecialchars($_POST['bairro'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                </div>

                <div class="ops-form-section-title">
                    Cargo e Foto de Perfil
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-7">
                        <span class="ops-label">Nível de acesso <span class="ops-required" aria-hidden="true">*</span></span>
                        <div class="ops-radio-grid">
                            <label class="ops-radio-card" for="funcionario">
                                <input
                                    type="radio"
                                    name="acesso"
                                    id="funcionario"
                                    value="funcionario"
                                    <?= (($_POST['acesso'] ?? 'funcionario') === 'funcionario') ? 'checked' : '' ?>
                                    required>
                                <span>
                                    <strong class="d-block text-white small">Funcionário / Operador</strong>
                                    <span class="text-steel" style="font-size: 0.76rem;">Acesso ao painel, sensores e relatórios.</span>
                                </span>
                            </label>

                            <label class="ops-radio-card" for="administrador">
                                <input
                                    type="radio"
                                    name="acesso"
                                    id="administrador"
                                    value="admin"
                                    <?= (($_POST['acesso'] ?? '') === 'admin') ? 'checked' : '' ?>>
                                <span>
                                    <strong class="d-block text-white small">Administrador</strong>
                                    <span class="text-steel" style="font-size: 0.76rem;">Gestão completa de equipe, trens, rotas e sensores.</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-md-5">
                        <label class="ops-label" for="avatar" id="avatar-label">Foto de perfil (opcional)</label>
                        <input
                            type="file"
                            class="ops-input"
                            id="avatar"
                            name="avatar"
                            accept=".png,.jpg,.jpeg,image/png,image/jpeg"
                            aria-describedby="avatar-help">
                        <span id="avatar-help" class="ops-help">Formatos PNG ou JPG (tamanho máximo: 5 MB).</span>
                    </div>
                </div>

            </div>

            <div class="ops-panel-footer">
                <a href="Admin.php" class="ops-btn ops-btn-ghost">Cancelar</a>
                <button type="submit" name="CadastrarUsuario" value="Cadastrar" class="ops-btn ops-btn-primary">
                    Cadastrar Funcionário
                </button>
            </div>
        </form>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_cep.js"></script>
    <script src="../scripts/script_navbar.js"></script>
    <script src="../scripts/script_auth.js"></script>
</body>

</html>
