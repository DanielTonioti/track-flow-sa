<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id <= 0) {
    header('Location: cadastro_rotas.php');
    exit;
}

$stmt = $db->prepare('SELECT id, nome, descricao, distancia_km, tempo_previsto_min, trem_id FROM rota WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$rota = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$rota) {
    header('Location: cadastro_rotas.php');
    exit;
}

$erro = '';
$sucesso = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar-rota'])) {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $distancia = trim($_POST['distancia_km'] ?? '');
    $tempo = trim($_POST['tempo_previsto_min'] ?? '');
    $tremId = filter_input(INPUT_POST, 'trem_id', FILTER_VALIDATE_INT);

    if ($nome === '') {
        $erro = 'O nome da rota é obrigatório.';
    } elseif (mb_strlen($nome) < 2 || mb_strlen($nome) > 100) {
        $erro = 'O nome da rota deve ter entre 2 e 100 caracteres.';
    } elseif ($descricao === '') {
        $erro = 'A descrição da rota é obrigatória.';
    } elseif (mb_strlen($descricao) < 5 || mb_strlen($descricao) > 500) {
        $erro = 'A descrição da rota deve ter entre 5 e 500 caracteres.';
    } elseif (!is_numeric($distancia) || (float) $distancia <= 0) {
        $erro = 'A distância deve ser um número maior que zero.';
    } elseif (!ctype_digit((string) $tempo) || (int) $tempo <= 0) {
        $erro = 'O tempo previsto deve ser um número inteiro maior que zero.';
    } elseif ($tremId === false || $tremId <= 0) {
        $erro = 'Selecione um trem válido para vincular a rota.';
    } else {
        $checkTrem = $db->prepare('SELECT id FROM trem WHERE id = ?');
        $checkTrem->bind_param('i', $tremId);
        $checkTrem->execute();
        if ($checkTrem->get_result()->num_rows === 0) {
            $erro = 'O trem selecionado não existe.';
        }
        $checkTrem->close();
    }

    if ($erro === '') {
        $update = $db->prepare('UPDATE rota SET nome = ?, descricao = ?, distancia_km = ?, tempo_previsto_min = ?, trem_id = ? WHERE id = ?');
        $update->bind_param('ssdiii', $nome, $descricao, $distancia, $tempo, $tremId, $id);

        if ($update->execute()) {
            $rota['nome'] = $nome;
            $rota['descricao'] = $descricao;
            $rota['distancia_km'] = $distancia;
            $rota['tempo_previsto_min'] = $tempo;
            $rota['trem_id'] = $tremId;
            $sucesso = true;
        } else {
            $erro = 'Erro ao atualizar a rota.';
        }

        $update->close();
    }
}

$trens = $db->query('SELECT id, nome, modelo FROM trem ORDER BY nome ASC');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Editar Rota</title>
</head>
<body class="cores-background" data-background="azure-escuro-fundo">
    <header><?php include "components/navbar.php"; ?></header>
    <main class="container py-4">
        <?php include "components/back-button-hub.php"; ?>

        <div class="text-center mb-4">
            <div class="cores-background p-3 rounded-4 d-inline-block" data-background="azure-claro-fundo">
                <h1 class="titulo-Sensor mb-0">Editar Rota</h1>
            </div>
        </div>

        <?php if ($erro !== ''): ?>
            <div class="alert alert-danger mx-auto" style="max-width: 840px;">
                <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success mx-auto" style="max-width: 840px;">
                Rota atualizada com sucesso.
            </div>
        <?php endif; ?>

        <form method="POST" class="mx-auto" style="max-width: 840px;">
            <div class="cores-background p-4 rounded-4" data-background="azure-claro-fundo">
                <div class="mb-3">
                    <label for="nome" class="form-label text-white">Nome da rota</label>
                    <input type="text" id="nome" name="nome" class="form-control" maxlength="100" value="<?= htmlspecialchars((string) ($rota['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label text-white">Descrição</label>
                    <textarea id="descricao" name="descricao" class="form-control" rows="4" maxlength="500" required><?= htmlspecialchars((string) ($rota['descricao'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="distancia_km" class="form-label text-white">Distância (km)</label>
                        <input type="number" id="distancia_km" name="distancia_km" class="form-control" min="0.01" step="0.01" value="<?= htmlspecialchars((string) ($rota['distancia_km'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tempo_previsto_min" class="form-label text-white">Tempo previsto (minutos)</label>
                        <input type="number" id="tempo_previsto_min" name="tempo_previsto_min" class="form-control" min="1" step="1" value="<?= htmlspecialchars((string) ($rota['tempo_previsto_min'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="trem_id" class="form-label text-white">Trem vinculado</label>
                    <select id="trem_id" name="trem_id" class="form-select" required>
                        <option value="">Selecione um trem</option>
                        <?php while ($trem = $trens->fetch_assoc()): ?>
                            <option value="<?= (int) $trem['id'] ?>" <?= ((string) ($rota['trem_id'] ?? '') === (string) $trem['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($trem['nome'], ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($trem['modelo'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" name="atualizar-rota" class="btn btn-primary w-100">Salvar alterações</button>
            </div>
        </form>
    </main>
</body>
</html>
