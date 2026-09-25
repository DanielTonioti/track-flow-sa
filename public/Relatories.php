<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: Login.php');
    exit();
}

include "../infra/conn.php";

$sensorId = (int) ($_GET['sensor_id'] ?? 0);
$inicio = $_GET['inicio'] ?? '';
$fim = $_GET['fim'] ?? '';
$dataInicial = $inicio !== '' ? $inicio : '1000-01-01';
$dataFinal = $fim !== '' ? $fim : '9999-12-31';

$sensores = $db->query('SELECT id, nome FROM sensor ORDER BY nome')->fetch_all(MYSQLI_ASSOC);
$sql = 'SELECT dados_sensor.id, sensor.nome AS sensor, trem.nome AS trem,
               dados_sensor.valor, dados_sensor.data_registro
        FROM dados_sensor
        JOIN sensor ON sensor.id = dados_sensor.sensor_id
        JOIN trem ON trem.id = sensor.trem_id
        WHERE (? = 0 OR dados_sensor.sensor_id = ?)
          AND DATE(dados_sensor.data_registro) BETWEEN ? AND ?
        ORDER BY dados_sensor.data_registro DESC';
$stmt = $db->prepare($sql);
$stmt->bind_param('iiss', $sensorId, $sensorId, $dataInicial, $dataFinal);
$stmt->execute();
$relatorios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Relatório</title>
</head>

<body class="cores-background " data-background="azure-escuro-fundo">

    <head>
        <header>

            <?php

            include("components/navbar.php");

            ?>


        </header>

    </head>

    <main class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="text-white mb-1">Relatórios</h1>
                <p class="text-white-50 mb-0">Cadastre e consulte relatórios do sistema.</p>
            </div>
            <a class="btn btn-light fw-bold" href="Relatories_New.php">Novo relatório</a>
        </div>

        <section class="cores-background rounded-4 p-4 mb-4" data-background="azure-claro-fundo">
            <h2 class="h4 text-white mb-3">Leituras dos sensores</h2>
            <form method="GET" class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label text-white" for="sensor_id">Sensor</label>
                    <select class="form-select" name="sensor_id" id="sensor_id">
                        <option value="">Todos</option>
                        <?php foreach ($sensores as $sensor): ?>
                            <option value="<?= (int) $sensor['id'] ?>" <?= $sensorId === (int) $sensor['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sensor['nome'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white" for="inicio">De</label>
                    <input class="form-control" type="date" name="inicio" id="inicio" value="<?= htmlspecialchars($inicio ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white" for="fim">Até</label>
                    <input class="form-control" type="date" name="fim" id="fim" value="<?= htmlspecialchars($fim ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-light w-100" type="submit">Filtrar</button>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Sensor</th>
                            <th scope="col">Trem</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($relatorios as $relatorio): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($relatorio['sensor'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($relatorio['trem'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($relatorio['valor'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($relatorio['data_registro'], ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$relatorios): ?>
                            <tr><td colspan="4">Nenhuma leitura encontrada.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <button id="voltarhub" class="btn btn-danger back-buttom">Voltar</button>
    </main>
    <script src="../scripts/scriptVoltar.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>
