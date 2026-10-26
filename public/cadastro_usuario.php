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
    $uf = trim($_POST['uf'] ?? '');
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
    $erro = "CEP é obrigatório.";
    } elseif (!preg_match('/^[0-9]+$/', $cep)) {
    $erro = "O CEP deve conter apenas números.";
    } elseif (strlen($cep) !== 8) 
    {
    $erro = "O CEP deve conter exatamente 8 números."; 
    } elseif ($cidade === '') {
         $erro = "cidade é obrigatorio.";
    }
    elseif ($rua === '') {
         $erro = "rua é obrigatorio.";
    } 
    elseif ($bairro === '') {
         $erro = "bairro é obrigatorio.";
    }
    elseif ($uf === '') {
         $erro = "estado é obrigatorio.";
    }
    
     elseif ($acesso !== "funcionario" && $acesso !== "admin") {
        $erro = "Selecione um nível de acesso válido.";
    }

    // 2. Validação do avatar (ainda sem mover o arquivo)
    if ($arquivo !== null && $arquivo['error'] !== UPLOAD_ERR_NO_FILE) {

        if ($arquivo['error'] !== UPLOAD_ERR_OK) {
            $erro = "Erro no envio da imagem.";

        } elseif ($arquivo['size'] > 5 * 1024 * 1024) {
            $erro = "Seu arquivo excede o tamanho máximo permitido (5MB).";

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

                $stmt->bind_param("sdsssssssss", $nome, $cep, $cidade, $rua, $bairro, $uf, $email, $senhaHash, $telefone, $acesso, $caminhoAvatar);

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
$resultado = $db->query("SELECT id, nome, email, telefone, cargo FROM funcionario");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Cadastrar Usuário</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">
    <header>
        <?php include "components/navbar.php"; ?>
    </header>
    <main>
        <form method="POST" id="CadastrarUsuario" enctype="multipart/form-data">
            <div class="blockcentro titulo-Sensor">
                <div class="cores-background p-2 rounded-4" data-background="azure-claro-fundo">
                    <h2 class="titulo-Sensor">Cadastrar Usuário</h2>
                </div>
                <?php if ($erro !== "") { ?>
                    <div class="alert alert-danger mt-3">
                        <?= htmlspecialchars($erro) ?>
                    </div>
                <?php } ?>
                <?php if ($sucesso) { ?>
                    <div class="alert alert-success mt-3">
                        Usuário cadastrado com sucesso!
                    </div>
                <?php } ?>
                <br>
                <div id="valortipo" class="cores-background p-4 w-25 rounded-4" data-background="azure-claro-fundo">
                    <br>
                    <div class="column-sensor" id="tipos">
                        <label for="nome">Nome:</label>
                        <input type="text" name="nome" id="nome" minlength="3" maxlength="100" pattern="[A-Za-zÀ-ÿ\s]+"
                            value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
                        <label for="email">E-mail:</label>
                        <input type="email" name="email" id="email" maxlength="100"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        <label for="senha">Senha:</label>
                        <input type="password" name="password" id="senha" minlength="6" maxlength="255"
                            placeholder="Mínimo de 6 caracteres" required>
                        <label for="telefone">Telefone:</label>
                        <input type="text" name="telefone" id="telefone" minlength="10" maxlength="11"
                            pattern="[0-9]{10,11}" placeholder="Ex: 47999999999"
                            value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>" required>
                       <label for="cep">CEP:</label>
    <input type="text" name="cep" id="cep" maxlength="8" minlength="8" pattern="[0-9]{8}" inputmode="numeric" value="<?= htmlspecialchars($user['cep'] ?? '', ENT_QUOTES, 'UTF-8') ?>" onblur="pesquisacep(this.value);"
    title="Digite o CEP com exatamente 8 números, sem pontos ou traços." required>
                        <label>Rua:
                            <input name="rua" type="text" id="rua" size="60" /></label><br />
                        <label>Bairro:
                            <input name="bairro" type="text" id="bairro" size="40" /></label><br />
                        <label>Cidade:
                            <input name="cidade" type="text" id="cidade" size="40" /></label><br />
                        <label>Estado:
                            <input name="uf" type="text" id="uf" size="2" /></label><br />
                        <label for="acesso">Nível de acesso:</label>
                        <div>
                            <input type="radio" name="acesso" id="funcionario" value="funcionario" <?= (($_POST['acesso'] ?? '') === 'funcionario') ? 'checked' : '' ?> required>
                            Funcionário
                            <input type="radio" name="acesso" id="administrador" value="admin" <?= (($_POST['acesso'] ?? '') === 'admin') ? 'checked' : '' ?>>
                            Administrador
                        </div>
                        <label for="avatar" id="avatar-label">Foto de perfil:</label>
                        <input type="file" id="avatar" name="avatar" accept=".png, .jpg, .jpeg">
                    </div>
                </div>
                <br>
                <input type="submit" value="Cadastrar" name="CadastrarUsuario"
                    class="border-none-buttom cores-background titulo-Sensor p-2 rounded-3"
                    data-background="azure-claro-fundo">
            </div>
        </form>
        <a href="Admin.php" class="btn btn-danger back-buttom">Voltar</a>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_cep.js"></script>
    <script src="../scripts/scriptSensor.js"></script>
    <script src="../scripts/scriptAdmin.js"></script>
    <script src="../scripts/script_navbar.js"></script>

</body>

</html>