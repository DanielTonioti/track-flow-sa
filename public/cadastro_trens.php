<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

$erro = '';
$sucesso = false;
$responsavelId = '';
$excluir_erro = isset($_GET['excluir_erro']);
if ($excluir_erro){$erro = "Não foi possível excluir trem: Há alguma rota dependente de sua existência";}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar-trem'])) {
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

        if ($responsavel === null) {
            $stmt = $db->prepare('INSERT INTO trem (nome, modelo, responsavel_usuario_id) VALUES (?, ?, NULL)');
            $stmt->bind_param('ss', $nome, $modelo);
        } else {
            $stmt = $db->prepare('INSERT INTO trem (nome, modelo, responsavel_usuario_id) VALUES (?, ?, ?)');
            $stmt->bind_param('ssi', $nome, $modelo, $responsavel);
        }

        if ($stmt->execute()) {
            $sucesso = true;
            $nome = '';
            $modelo = '';
            $responsavelId = '';
        } else {
            $erro = 'Erro ao cadastrar o trem.';
        }

        $stmt->close();
    }
}

$usuarios = $db->query('SELECT id, nome, cargo FROM funcionario ORDER BY nome ASC');
$trens = $db->query(
    "SELECT t.*, f.nome AS responsavel_nome
     FROM trem t
     LEFT JOIN funcionario f ON f.id = t.responsavel_usuario_id
     ORDER BY t.id DESC"
);
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Cadastrar Trem</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">
    <header>
        <?php include 'components/navbar.php'; ?>
    </header>

    <main class="container py-4">
        <a href="trens_trilhos.php" class="btn btn-danger back-buttom">Voltar</a>
        <div class="text-center mb-4">
            <div class="cores-background p-3 rounded-4 d-inline-block" data-background="azure-claro-fundo">
                <h1 class="titulo-Sensor mb-0">Cadastrar Trem</h1>
            </div>
        </div>

        <?php if ($erro !== ''): ?>
            <div class="alert alert-danger mx-auto" style="max-width: 720px;">
                <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success mx-auto" style="max-width: 720px;">
                Trem cadastrado com sucesso.
            </div>
        <?php endif; ?>

        <form method="POST" class="mx-auto" style="max-width: 720px;">
            <div class="cores-background p-4 rounded-4" data-background="azure-claro-fundo">
                <div class="mb-3">
                    <label for="nome" class="form-label text-white">Nome do trem</label>
                    <input type="text" id="nome" name="nome" class="form-control" maxlength="100" value="<?= htmlspecialchars($_POST['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="modelo" class="form-label text-white">Modelo</label>
                    <input type="text" id="modelo" name="modelo" class="form-control" maxlength="50" value="<?= htmlspecialchars($_POST['modelo'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="responsavel_usuario_id" class="form-label text-white">Responsável</label>
                    <select id="responsavel_usuario_id" name="responsavel_usuario_id" class="form-select">
                        <option value="Sem responsável">Sem responsável</option>
                        <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                            <option value="<?= (int) $usuario['id'] ?>" <?= ($responsavelId !== '' && (string) $responsavelId === (string) $usuario['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($usuario['cargo'], ENT_QUOTES, 'UTF-8') ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" name="cadastrar-trem" class="btn btn-primary w-100">Cadastrar trem</button>
            </div>
        </form>

        <div class="mx-auto mt-4" style="max-width: 960px;">
            <div class="cores-background p-3 rounded-4" data-background="azure-claro-fundo">
                <h2 class="titulo-Sensor mb-3">Trens cadastrados</h2>
                <div class="table-responsive">
                    <table class="table table-dark table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Modelo</th>
                                <th>Responsável</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($trens && $trens->num_rows > 0): ?>
                                <?php while ($trem = $trens->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= (int) $trem['id'] ?></td>
                                        <td><?= htmlspecialchars((string) ($trem['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) ($trem['modelo'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) ($trem['responsavel_nome'] ?? 'Sem responsável'), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <a href="editar_trem.php?id=<?= (int) $trem['id'] ?>" class="btn btn-sm btn-warning me-2">Editar</a>
                                            <form action="excluir_trem.php" method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?= (int) $trem['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir este trem?');">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Nenhum trem cadastrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
</body>

</html>