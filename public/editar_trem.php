<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id <= 0) {
    header('Location: cadastro_trens.php');
    exit;
}

$erro = '';
$sucesso = false;
$responsavelId = '';

$stmt = $db->prepare('SELECT id, nome, modelo, responsavel_usuario_id FROM trem WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$trem = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$trem) {
    header('Location: cadastro_trens.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar-trem'])) {
    $nome = trim($_POST['nome'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $responsavelId = $_POST['responsavel_usuario_id'] ?? '';

    if ($nome === '') {
        $erro = 'O nome do trem é obrigatório.';
    } elseif (strlen($nome) < 2 || strlen($nome) > 100) {
        $erro = 'O nome do trem deve ter entre 2 e 100 caracteres.';
    } elseif ($modelo === '') {
        $erro = 'O modelo do trem é obrigatório.';
    } elseif (strlen($modelo) < 2 || strlen($modelo) > 50) {
        $erro = 'O modelo do trem deve ter entre 2 e 50 caracteres.';
    } elseif ($responsavelId !== '' && (!is_numeric($responsavelId) || (int) $responsavelId <= 0)) {
        $erro = 'Selecione um responsável válido.';
    }

    if ($erro === '') {
        $responsavel = $responsavelId !== '' ? (int) $responsavelId : null;

        if ($responsavel !== null) {
            $check = $db->prepare('SELECT id FROM funcionario WHERE id = ?');
            $check->bind_param('i', $responsavel);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                $erro = 'Esse funcionário não existe.';
            }
            $check->close();
        }
    }

    if ($erro === '') {
        if ($responsavel === null) {
            $update = $db->prepare('UPDATE trem SET nome = ?, modelo = ?, responsavel_usuario_id = NULL WHERE id = ?');
            $update->bind_param('ssi', $nome, $modelo, $id);
        } else {
            $update = $db->prepare('UPDATE trem SET nome = ?, modelo = ?, responsavel_usuario_id = ? WHERE id = ?');
            $update->bind_param('ssii', $nome, $modelo, $responsavel, $id);
        }

        if ($update->execute()) {
            $trem['nome'] = $nome;
            $trem['modelo'] = $modelo;
            $trem['responsavel_usuario_id'] = $responsavel;
            $sucesso = true;
        } else {
            $erro = 'Erro ao atualizar o trem.';
        }

        $update->close();
    }
}

$usuarios = $db->query('SELECT id, nome, cargo FROM funcionario ORDER BY nome ASC');
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Editar Trem</title>
</head>
<body class="cores-background" data-background="azure-escuro-fundo">
    <header><?php include 'components/navbar.php'; ?></header>
    <main class="container py-4">
        <a href="cadastro_trens.php" class="btn btn-danger back-buttom">Voltar</a>
        <div class="text-center mb-4">
            <div class="cores-background p-3 rounded-4 d-inline-block" data-background="azure-claro-fundo">
                <h1 class="titulo-Sensor mb-0">Editar Trem</h1>
            </div>
        </div>

        <?php if ($erro !== ''): ?>
            <div class="alert alert-danger mx-auto" style="max-width: 720px;">
                <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success mx-auto" style="max-width: 720px;">
                Trem atualizado com sucesso.
            </div>
        <?php endif; ?>

        <form method="POST" class="mx-auto" style="max-width: 720px;">
            <div class="cores-background p-4 rounded-4" data-background="azure-claro-fundo">
                <div class="mb-3">
                    <label for="nome" class="form-label text-white">Nome do trem</label>
                    <input type="text" id="nome" name="nome" class="form-control" maxlength="100" value="<?= htmlspecialchars((string) ($trem['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="modelo" class="form-label text-white">Modelo</label>
                    <input type="text" id="modelo" name="modelo" class="form-control" maxlength="50" value="<?= htmlspecialchars((string) ($trem['modelo'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="responsavel_usuario_id" class="form-label text-white">Responsável</label>
                    <select id="responsavel_usuario_id" name="responsavel_usuario_id" class="form-select">
                        <option value="">Sem responsável</option>
                        <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                            <option value="<?= (int) $usuario['id'] ?>" <?= ((string) ($trem['responsavel_usuario_id'] ?? '') === (string) $usuario['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($usuario['cargo'], ENT_QUOTES, 'UTF-8') ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" name="atualizar-trem" class="btn btn-primary w-100">Salvar alterações</button>
            </div>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
</body>
</html>
