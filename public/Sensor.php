<?php
session_start();
if (($_SESSION['cargo'] ?? '') !== 'admin') {
    header('Location: hub.php');
    exit();
}

include '/../infra/conn.php';

$mensagemCadastro = '';
$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    $tipoDado = $_POST['tipo_dado'] ?? '';
    $tremVinculado = trim($_POST['trem_vinculado'] ?? '');
    $localizacao = trim($_POST['localizacao'] ?? '');

    if ($tipo === 'trem' && in_array($tipoDado, ['Velocidade', 'Temperatura', 'Falha'], true)
        && preg_match('/^[\p{L}\p{N} ._-]{1,100}$/u', $tremVinculado)) {
        $stmtBuscaTrem = $db->prepare('SELECT id FROM trem WHERE nome = ?');
        if ($stmtBuscaTrem) {
            $stmtBuscaTrem->bind_param('s', $tremVinculado);
            $stmtBuscaTrem->execute();
            $resultadoTrem = $stmtBuscaTrem->get_result();
            $trem = $resultadoTrem->fetch_assoc();
            $stmtBuscaTrem->close();

            if (!empty($trem['id'])) {
                $sql = 'INSERT INTO sensor (nome, tipo, trem_id) VALUES (?, ?, ?)';
                $stmt = $db->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param('ssi', $tremVinculado, $tipoDado, $trem['id']);
                    $stmt->execute();
                    $stmt->close();
                    $mensagemCadastro = 'Sensor cadastrado com sucesso!';
                }
            } else {
                $mensagemErro = 'Trem não encontrado. Cadastre o trem antes de vincular o sensor.';
            }
        }
    } elseif ($tipo === 'trem') {
        $mensagemErro = 'Informe um trem válido e um tipo de dado.';
    }

    if ($tipo === 'trilho' && $tipoDado !== '' && $localizacao !== '') {
        $sql = 'INSERT INTO trilho (nome, localizacao) VALUES (?, ?)';
        $stmt = $db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ss', $tremVinculado, $localizacao);
            $stmt->execute();
            $stmt->close();
            $mensagemCadastro = 'Trilho cadastrado com sucesso!';
        }
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Sensor</title>
</head>

<body class="cores-background " data-background="azure-escuro-fundo" class=" cores-background "
    data-background="azure-escuro-fundo">
    <header>

        <?php

        include("components/navbar.php");

        ?>


    </header>

    <main>
        <?php if (!empty($mensagemCadastro)): ?>
            <div class="alert alert-success mx-auto mt-3 w-50 text-center">
                <?php echo $mensagemCadastro; ?>
            </div>
        <?php endif; ?>
        <?php if ($mensagemErro !== ''): ?>
            <div class="alert alert-danger mx-auto mt-3 w-50 text-center">
                <?php echo $mensagemErro; ?>
            </div>
        <?php endif; ?>

        <form id="cadastrar-sensores" method="POST">
            <div class="block-centro titulo-sensor">
                <div class="cores-background p-2 rounded-4" data-background="azure-claro-fundo">
                    <h2 class="titulo-sensor">Cadastrar Sensor</h2>
                </div>
                <br>
                <div id="valor-tipo" class="cores-background p-4 w-25 rounded-4" data-background="azure-claro-fundo">
                    <div class="center-sensor">
                        <input type="radio" name="tipo" id="trem" value="trem" required> Trem
                        <input type="radio" name="tipo" id="trilho" value="trilho"> Trilho
                    </div>
                    <br>
                    <div class="column-sensor" id="tipos">
                        <label for="tipo-dado">Tipo de Dado</label>
                        <select name="tipo_dado" id="tipo-dado" required>
                            <option value="" disabled selected>Selecione</option>
                            <option value="Velocidade">Velocidade</option>
                            <option value="Temperatura">Temperatura</option>
                            <option value="Falha">Falha</option>
                        </select>
                        <br>
                        <div class="hiden" id="trem-vinculado-box">
                            <label for="trem-vinculado">Trem vinculado</label>
                            <br>
                            <input class="w-100" type="text" id="trem-vinculado" name="trem_vinculado" placeholder="Ex: S.TRM.1" required>
                        </div>

                        <div class="hiden" id="localizacao-box">
                            <label for="localizacao">Localização</label>
                            <br>
                            <input class="w-100" type="text" id="localizacao" name="localizacao" placeholder="Joinville">
                        </div>

                    </div>
                </div>
                <br>
                <button type="submit" class="border-none-buttom cores-background titulo-sensor p-2 rounded-3"
                    data-background="azure-claro-fundo">Cadastrar</button>
            </div>
        </form>


        <script src="../scripts/"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>

        <button id="voltarhub" class="btn btn-danger back-buttom"> Voltar </button>
    </main>
    <footer>

    </footer>
    <script src="../scripts/scriptVoltar.js"></script>
    <script src="../scripts/scriptSensor.js"></script>
    <script src="../scripts/scriptAdmin.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>
