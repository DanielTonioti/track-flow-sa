<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

$erro = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar-rota'])) {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $distancia = trim($_POST['distancia_km'] ?? '');
    $tempo = trim($_POST['tempo_previsto_min'] ?? '');
    $tremId = $_POST['trem_id'] ?? '';

    if ($nome === '') {
        $erro = 'O nome da rota é obrigatório.';
    } elseif (strlen($nome) < 2 || strlen($nome) > 100) {
        $erro = 'O nome da rota deve ter entre 2 e 100 caracteres.';
    } elseif ($descricao === '') {
        $erro = 'A descrição da rota é obrigatória.';
    } elseif (strlen($descricao) < 5 || strlen($descricao) > 500) {
        $erro = 'A descrição da rota deve ter entre 5 e 500 caracteres.';
    } elseif (!is_numeric($distancia) || (float) $distancia <= 0) {
        $erro = 'A distância deve ser maior que zero.';
    } elseif (!ctype_digit((string) $tempo) || $tempo <= 0) {
        $erro = 'O tempo previsto deve ser um número maior que zero.';
    } elseif ($tremId === '' || !is_numeric($tremId) || $tremId <= 0) {
        $erro = 'Selecione um trem válido.';
    }

    if ($erro === '') {
        $tremId = (int) $tremId;
        $checkTrem = $db->prepare('SELECT id FROM trem WHERE id = ?');
        $checkTrem->bind_param('i', $tremId);
        $checkTrem->execute();

        if ($checkTrem->get_result()->num_rows === 0) {
            $erro = 'Esse trem não existe.';
        }

        $checkTrem->close();
    }

    if ($erro === '') {
        $insert = $db->prepare('INSERT INTO rota (nome, descricao, distancia_km, tempo_previsto_min, trem_id) VALUES (?, ?, ?, ?, ?)');
        $insert->bind_param('ssdii', $nome, $descricao, $distancia, $tempo, $tremId);

        if ($insert->execute()) {
            $sucesso = true;
        } else {
            $erro = 'Erro ao cadastrar a rota.';
        }

        $insert->close();
    }
}

$trens = $db->query('SELECT id, nome, modelo FROM trem ORDER BY nome ASC');
$rotas = $db->query("SELECT rota.*, trem.nome FROM rota INNER JOIN trem ON trem.id = rota.trem_id ORDER BY rota.id DESC");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Cadastrar Rota</title>
</head>
<body class="cores-background" data-background="azure-escuro-fundo">
    <header><?php include 'components/navbar.php'; ?></header>
    <main class="container py-4">
        <?php include 'components/back-button-hub.php'; ?>

        <div class="text-center mb-4">
            <div class="cores-background p-3 rounded-4 d-inline-block" data-background="azure-claro-fundo">
                <h1 class="titulo-Sensor mb-0">Cadastrar Rota</h1>
            </div>
        </div>

        <?php if ($erro !== ''): ?>
            <div class="alert alert-danger mx-auto" style="max-width: 840px;">
                <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success mx-auto" style="max-width: 840px;">
                Rota cadastrada com sucesso.
            </div>
        <?php endif; ?>

        <form method="POST" class="mx-auto" style="max-width: 840px;">
            <div class="cores-background p-4 rounded-4" data-background="azure-claro-fundo">
                <div class="mb-3">
                    <label for="nome" class="form-label text-white">Nome da rota</label>
                    <input type="text" id="nome" name="nome" class="form-control" maxlength="100" value="<?= htmlspecialchars($_POST['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label text-white">Descrição</label>
                    <textarea id="descricao" name="descricao" class="form-control" rows="4" maxlength="500" required><?= htmlspecialchars($_POST['descricao'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="distancia_km" class="form-label text-white">Distância (km)</label>
                        <input type="number" id="distancia_km" name="distancia_km" class="form-control" min="0.01" step="0.01" value="<?= htmlspecialchars($_POST['distancia_km'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tempo_previsto_min" class="form-label text-white">Tempo previsto (minutos)</label>
                        <input type="number" id="tempo_previsto_min" name="tempo_previsto_min" class="form-control" min="1" step="1" value="<?= htmlspecialchars($_POST['tempo_previsto_min'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="trem_id" class="form-label text-white">Trem vinculado</label>
                    <select id="trem_id" name="trem_id" class="form-select" required>
                        <option value="">Selecione um trem</option>
                        <?php while ($trem = $trens->fetch_assoc()): ?>
                            <option value="<?= (int) $trem['id'] ?>" <?= ((string) ($_POST['trem_id'] ?? '') === (string) $trem['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($trem['nome'], ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($trem['modelo'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" name="cadastrar-rota" class="btn btn-primary w-100">Cadastrar rota</button>
            </div>
        </form>

        <div class="mx-auto mt-4" style="max-width: 1100px;">
            <div class="cores-background p-3 rounded-4" data-background="azure-claro-fundo">
                <h2 class="titulo-Sensor mb-3">Rotas cadastradas</h2>
                <div class="table-responsive">
                    <table class="table table-dark table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Distância</th>
                                <th>Tempo</th>
                                <th>Trem</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($rotas && $rotas->num_rows > 0): ?>
                                <?php while ($rota = $rotas->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= (int) $rota['id'] ?></td>
                                        <td><?= htmlspecialchars((string) ($rota['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) ($rota['descricao'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) ($rota['distancia_km'] ?? ''), ENT_QUOTES, 'UTF-8') ?> km</td>
                                        <td><?= htmlspecialchars((string) ($rota['tempo_previsto_min'] ?? ''), ENT_QUOTES, 'UTF-8') ?> min</td>
                                        <td><?= htmlspecialchars((string) ($rota['trem_nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <a href="editar_rota.php?id=<?= (int) $rota['id'] ?>" class="btn btn-sm btn-warning me-2">Editar</a>
                                            <form action="excluir_rota.php" method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?= (int) $rota['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir esta rota?');">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Nenhuma rota cadastrada.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
