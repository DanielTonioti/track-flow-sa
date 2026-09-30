<?php
session_start();
require_once '../infra/conn.php';

if (($_SESSION['cargo'] ?? '') !== 'admin') {
    header("Location: Login.php");
    exit();
}

$erro = "";

if (isset($_POST['CadastrarUsuario'])) {

    $erro = ""; // Inicializa a variável de erro
    
    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $senha    = $_POST['password'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');
    $acesso   = $_POST['acesso'] ?? '';
    $caminhoAvatar = null; // Valor padrão caso não envie foto

    // 1. Validações dos Campos de Texto
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

    // 2. Validação e Upload do Avatar (apenas se os dados anteriores estiverem válidos)
    if ($erro === "" && !empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {

        $nomeAvatar     = $_FILES['avatar']['name'];
        $nomeTemporario = $_FILES['avatar']['tmp_name'];
        $tamanho        = $_FILES['avatar']['size'];

        // Tamanho máximo (5MB)
        if ($tamanho > (1024 * 1024 * 5)) {
            $erro = "Seu arquivo excede o tamanho máximo permitido (5MB).";
        }

        // Extensão
        $extensao = strtolower(pathinfo($nomeAvatar, PATHINFO_EXTENSION));
        $arquivosPermitidos = ["png", "jpg", "jpeg"];
        if (!in_array($extensao, $arquivosPermitidos)) {
            $erro = "Extensão de arquivo não permitida. Use PNG, JPG ou JPEG.";
        }

        // Tipo MIME real
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipoMime = finfo_file($finfo, $nomeTemporario);
        finfo_close($finfo);

        $typesPermitidos = ["image/png", "image/jpeg", "image/jpg"];
        if (!in_array($tipoMime, $typesPermitidos)) {
            $erro = "Tipo de arquivo inválido ou corrompido.";
        }

        // Mover arquivo se não houver erros no upload
        if ($erro === "") {
            $caminho = "../Assets/images/uploads/";
            $novoNome = date("d-m-Y_H-i-s") . "." . $extensao;
            $destino = $caminho . $novoNome;

            if (move_uploaded_file($nomeTemporario, $destino)) {
                $caminhoAvatar = $destino; // Salva o caminho para gravar no banco
            } else {
                $erro = "Erro ao mover a imagem para o servidor.";
            }
        }
    }

    // 3. Processamento no Banco de Dados
    if ($erro === "") {

        // Verifica se o e-mail já existe
        $sql = "SELECT id FROM funcionario WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultadoEmail = $stmt->get_result();

        if ($resultadoEmail->num_rows > 0) {
            $erro = "Este e-mail já está cadastrado.";
        } else {
            // Insere o funcionário incluindo o avatar
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO funcionario (nome, email, senha, telefone, cargo, avatar) VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ssssss", $nome, $email, $senhaHash, $telefone, $acesso, $caminhoAvatar);

            if ($stmt->execute()) {
                header("Location: cadastro_usuario.php?sucesso=1");
                exit();
            } else {
                $erro = "Erro ao cadastrar o usuário no banco de dados.";
            }
        }

        $stmt->close();
    }
}

$sql = "SELECT id, nome, email, senha, telefone, cargo FROM funcionario";
$resultado = $db->query($sql);
?>

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

                    <h2 class="titulo-Sensor">
                        Cadastrar Usuário
                    </h2>

                </div>

                <?php if ($erro !== "") { ?>

                    <div class="alert alert-danger mt-3">
                        <?= htmlspecialchars($erro) ?>
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
                            <input type="radio" name="acesso" id="funcionario" value="funcionario" <?= (($_POST['acesso'] ?? '') === 'funcionario') ? 'checked' : '' ?> required> Funcionário
                            <input type="radio" name="acesso" id="administrador" value="admin" <?= (($_POST['acesso'] ?? '') === 'admin') ? 'checked' : '' ?>> Administrador
                        </div>
                        <label for="avatar" id="avatar-label">Foto de perfil:</label>
                        <input type="file" id="avatar" name="avatar"
                            value="<?= htmlspecialchars($_POST['avatar'] ?? '') ?>" accept=".png, .jpg, .jpeg" required>
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
```