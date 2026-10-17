<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

// Token CSRF: o excluir_sensor.php precisa conferir este valor (veja a explicação na conversa)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function escapar_html($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$sensores = [
    ['id' => 1, 'tipo' => 'trem', 'codigo' => 'S.TRM.001', 'status' => 'Funcionando'],
    ['id' => 2, 'tipo' => 'trem', 'codigo' => 'S.TRM.002', 'status' => 'Funcionando'],
    ['id' => 3, 'tipo' => 'trem', 'codigo' => 'S.TRM.003', 'status' => 'Funcionando'],
    ['id' => 4, 'tipo' => 'trem', 'codigo' => 'S.TRM.004', 'status' => 'Funcionando'],
    ['id' => 5, 'tipo' => 'trem', 'codigo' => 'S.TRM.005', 'status' => 'Não Funcionando'],
    ['id' => 6, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.001', 'status' => 'Funcionando'],
    ['id' => 7, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.002', 'status' => 'Funcionando'],
    ['id' => 8, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.003', 'status' => 'Funcionando'],
    ['id' => 9, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.004', 'status' => 'Funcionando'],
    ['id' => 10, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.005', 'status' => 'Funcionando'],
];

// Ordem das seções na tela: trilhos primeiro, depois trens
$secoes = ['trilho' => 'Sensores trilhos', 'trem' => 'Sensores trens'];

$porTipo = ['trilho' => [], 'trem' => []];
foreach ($sensores as $sensor) {
    $porTipo[$sensor['tipo']][] = $sensor;
}
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
    <title>Exclusão de Sensores</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">
    <header>
        <?php
        include("components/navbar.php");
        ?>
    </header>
    <main>
        <div class="flex justify-content-center">
            <div class="admin-titulo d-flex justify-content-center align-items-center cores-background rounded-pill"
                data-background="azure-claro-fundo">
                <p class="admin-titulo-texto cores-color fw-bold mx-3" data-color="white">
                    Sensores
                </p>
            </div>
        </div>

        <?php foreach ($secoes as $tipo => $titulo):
            $listaId = 'lista-' . $tipo;
            ?>
            <div class="text-center cores-color p-3" data-color="white">
                <button class="btn text-white fw-bold" type="button" data-bs-toggle="collapse"
                    data-bs-target="#<?= $listaId ?>" aria-expanded="false" aria-controls="<?= $listaId ?>">
                    <span class="d-flex align-items-center">
                        <?= escapar_html($titulo) ?>
                        <img src="../assets/icons/seta-para-baixo.png" alt="" class="users-page-arrow">
                    </span>
                </button>

                <div class="collapse" id="<?= $listaId ?>">
                    <div class="d-flex flex-column mt-2">
                        <?php if (empty($porTipo[$tipo])): ?>
                            <p>Nenhum sensor cadastrado.</p>
                        <?php endif; ?>

                        <?php foreach ($porTipo[$tipo] as $sensor): ?>
                            <div class="flex centralizar-tabela">
                                <p class="border-tabela admin-tabela tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    <?= escapar_html($sensor['codigo']) ?>
                                </p>
                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    <?= escapar_html($sensor['status']) ?>
                                </p>
                                <form action="excluir_sensor.php" method="POST" class="d-inline"
                                    onsubmit="return confirm('Excluir este sensor?');">
                                    <input type="hidden" name="id" value="<?= (int) $sensor['id'] ?>">
                                    <input type="hidden" name="csrf_token"
                                        value="<?= escapar_html($_SESSION['csrf_token']) ?>">
                                    <button type="submit"
                                        class="admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                        data-color="white">
                                        Deletar
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <a href="hub.php" class="btn btn-danger back-buttom">Voltar</a>
    </main>
    <footer>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/scriptNavbar.js"></script>
</body>

</html>