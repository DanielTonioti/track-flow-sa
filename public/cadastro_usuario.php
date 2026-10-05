<?php
session_start();
require_once 'components/start.php';
require_once '../infra/conn.php';

if (($_SESSION['cargo'] ?? '') !== 'admin') {
    header("Location: Login.php");
    exit();
}

$erro = "";
$sucesso = isset($_GET['sucesso']);

// Extensão definida pelo MIME real do arquivo (não pelo nome enviado)
$extensoesPermitidas = [
    'image/png'  => 'png',
    'image/jpeg' => 'jpg',
];

if (isset($_POST['CadastrarUsuario'])) {

    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $senha    = $_POST['password'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');
    $acesso   = $_POST['acesso'] ?? '';
    $arquivo  = $_FILES['avatar'] ?? null;
    $tipoMime = '';

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
    } elseif ($acesso !== "funcionario" && $acesso !== "admin") {
        $erro = "Selecione um nível de acesso válido.";
    }

    // 2. Validação do avatar (ainda sem mover o arquivo)
    if ($erro === "") {
        if (!$arquivo || $arquivo['error'] !== UPLOAD_ERR_OK) {
            $erro = "A foto de perfil é obrigatória.";
        } elseif ($arquivo['size'] > 5 * 1024 * 1024) {
            $erro = "Seu arquivo excede o tamanho máximo permitido (5MB).";
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $tipoMime = $finfo->file($arquivo['tmp_name']);

            if (
                !isset($extensoesPermitidas[$tipoMime]) ||
                getimagesize($arquivo['tmp_name']) === false
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
            $pastaFisica = __DIR__ . "/../assets/images/uploads/";

            if (!is_dir($pastaFisica)) {
                mkdir($pastaFisica, 0755, true);
            }

            $novoNome = bin2hex(random_bytes(16)) . "." . $extensoesPermitidas[$tipoMime];
            $destino  = $pastaFisica . $novoNome;

            if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
                $erro = "Erro ao mover a imagem para o servidor.";
            } else {
                // Caminho salvo no banco (sem "../" e sempre minúsculo)
                $caminhoAvatar = "assets/images/uploads/" . $novoNome;
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $stmt = $db->prepare(
                    "INSERT INTO funcionario (nome, email, senha, telefone, cargo, avatar)
                     VALUES (?, ?, ?, ?, ?, ?)"
                );
                $stmt->bind_param("ssssss", $nome, $email, $senhaHash, $telefone, $acesso, $caminhoAvatar);

                if ($stmt->execute()) {
                    $stmt->close();
                    header("Location: cadastro_usuario.php?sucesso=1");
                    exit();
                }

                $stmt->close();
                unlink($destino); // desfaz o upload se o banco falhar
                $erro = "Erro ao cadastrar o usuário no banco de dados.";
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
                        <label for="acesso">Nível de acesso:</label>
                        <div>
                            <input type="radio" name="acesso" id="funcionario" value="funcionario"
                                <?= (($_POST['acesso'] ?? '') === 'funcionario') ? 'checked' : '' ?> required>
                            Funcionário
                            <input type="radio" name="acesso" id="administrador" value="admin"
                                <?= (($_POST['acesso'] ?? '') === 'admin') ? 'checked' : '' ?>>
                            Administrador
                        </div>
                        <label for="avatar" id="avatar-label">Foto de perfil:</label>
                        <input type="file" id="avatar" name="avatar" accept=".png, .jpg, .jpeg" required>
                    </div>
                </div>
                <br>
                <input type="submit" value="Cadastrar" name="CadastrarUsuario"
                    class="border-none-buttom cores-background titulo-Sensor p-2 rounded-3"
                    data-background="azure-claro-fundo">
            </div>
        </form>
        <button id="voltaradmin" class="btn btn-danger back-buttom"> Voltar </button>
    </main>
    <script src="../scripts/scriptvoltarAdmin.js"></script>
    <script src="../scripts/scriptSensor.js"></script>
    <script src="../scripts/scriptAdmin.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>

</body>
</html>