
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
    $erro = "Não foi possível excluir o usuário: há algum trem cadastrado no nome dele(a).";
}

if ($id <= 0) {
    die("Usuário inválido.");
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
    die("Usuário não encontrado.");
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
?>

<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../assets/logo/icone.ico">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">

    <link rel="stylesheet" href="../Styles/style.css">

    <title>Editar usuário</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">

    <header>
        <?php include "components/navbar.php"; ?>
    </header>

    <main>
        <form
            method="POST"
            id="CadastrarUsuario"
            enctype="multipart/form-data">

            <input
                type="hidden"
                name="id"
                value="<?= (int) $user['id'] ?>">

            <div class="blockcentro titulo-Sensor">

                <div
                    class="cores-background p-2 rounded-4"
                    data-background="azure-claro-fundo">

                    <h2 class="titulo-Sensor">Editar Usuário</h2>
                </div>

                <?php if ($erro !== "") { ?>
                    <div class="alert alert-danger mt-3">
                        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php } ?>

                <br>

                <div
                    id="valortipo"
                    class="cores-background p-4 w-25 rounded-4"
                    data-background="azure-claro-fundo">

                    <div class="column-sensor" id="tipos">

                        <label for="nome">Nome:</label>
                        <input
                            type="text"
                            name="nome"
                            id="nome"
                            minlength="3"
                            maxlength="100"
                            pattern="[A-Za-zÀ-ÿ\s]+"
                            value="<?= htmlspecialchars($user['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>

                        <label for="email">E-mail:</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            maxlength="100"
                            value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>

                        <label for="senha">Senha:</label>
                        <input
                            type="password"
                            name="password"
                            id="senha"
                            minlength="6"
                            maxlength="255"
                            placeholder="Deixe vazio para manter a senha atual">

                        <label for="telefone">Telefone:</label>
                        <input
                            type="text"
                            name="telefone"
                            id="telefone"
                            minlength="10"
                            maxlength="11"
                            pattern="[0-9]{10,11}"
                            value="<?= htmlspecialchars($user['telefone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>

                        <label for="cep">CEP:</label>
                    <input type="text" name="cep" id="cep" maxlength="8" minlength="8" pattern="[0-9]{8}" inputmode="numeric" value="<?= htmlspecialchars($user['cep'] ?? '', ENT_QUOTES, 'UTF-8') ?>" onblur="pesquisacep(this.value);" title="Digite o CEP com exatamente 8 números, sem pontos ou traços." required>

                        <label for="rua">Rua:</label>
                        <input
                            type="text"
                            name="rua"
                            id="rua"
                            value="<?= htmlspecialchars($user['rua'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>

                        <label for="bairro">Bairro:</label>
                        <input
                            type="text"
                            name="bairro"
                            id="bairro"
                            value="<?= htmlspecialchars($user['bairro'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>

                        <label for="cidade">Cidade:</label>
                        <input
                            type="text"
                            name="cidade"
                            id="cidade"
                            value="<?= htmlspecialchars($user['cidade'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>

                        <label for="uf">Estado (UF):</label>
                        <input
                            type="text"
                            name="uf"
                            id="uf"
                            maxlength="2"
                            value="<?= htmlspecialchars($user['uf'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>

                        <label for="acesso">Nível de acesso:</label>

                        <div>
                            <input
                                type="radio"
                                name="acesso"
                                id="funcionario"
                                value="funcionario"
                                <?= (($user['cargo'] ?? '') === 'funcionario') ? 'checked' : '' ?>
                                required>
                            <label for="funcionario">Funcionário</label>

                            <input
                                type="radio"
                                name="acesso"
                                id="administrador"
                                value="admin"
                                <?= (($user['cargo'] ?? '') === 'admin') ? 'checked' : '' ?>>
                            <label for="administrador">Administrador</label>
                        </div>

                        <label for="avatar">Foto de perfil:</label>

                        <?php if (!empty($user['avatar'])) { ?>
                            <div class="mb-2">
                                <img
                                    src="../<?= htmlspecialchars($user['avatar'], ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Foto atual do usuário"
                                    style="max-width: 120px; max-height: 120px; object-fit: cover;">
                            </div>
                        <?php } ?>

                        <input
                            type="file"
                            id="avatar"
                            name="avatar"
                            accept=".png,.jpg,.jpeg,image/png,image/jpeg">

                        <small>PNG ou JPG, máximo de 5 MB. Deixe vazio para manter a foto atual.</small>

                    </div>
                </div>

                <br>

                <input
                    type="submit"
                    value="Salvar"
                    name="CadastrarUsuario"
                    class="border-none-buttom cores-background titulo-Sensor p-2 rounded-3"
                    data-background="azure-claro-fundo">

            </div>
        </form>

        <form
            method="POST"
            action="excluir_usuario.php?id=<?= (int) $user['id'] ?>"
            id="excluirUsuario"
            class="text-center mt-3"
            onsubmit="return confirm('Deseja mesmo excluir esse usuário?');">

            <input
                type="hidden"
                name="id_excluir"
                value="<?= (int) $user['id'] ?>">

            <button
                type="submit"
                id="botaoExcluirUsuario"
                class="btn btn-danger border-tabela p-1 w-auto">
                Deletar
            </button>
        </form>

        <a href="Admin.php" class="btn btn-danger back-buttom">Voltar</a>

    </main>

    <script src="../scripts/script_cep.js"></script>
    <script src="../scripts/scriptSensor.js"></script>
    <script src="../scripts/scriptAdmin.js"></script>
    <script src="../scripts/script_navbar.js"></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

</body>
</html>
