<?php
session_start();
require_once 'components/start.php';
require_once '../infra/conn.php';
redirecionarSeNaoAdmin();

$trens = $db->query(
    "SELECT t.*, f.nome AS responsavel_nome
     FROM trem t
     LEFT JOIN funcionario f ON f.id = t.responsavel_usuario_id
     ORDER BY t.id DESC"
);

$rotas = $db->query(
    "SELECT r.*, t.nome AS trem_nome
     FROM rota r
     INNER JOIN trem t ON t.id = r.trem_id
     ORDER BY r.id DESC"
);

$usuarioEhAdmin = (($_SESSION['cargo'] ?? '') === 'admin');
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
    <title>Trens e rotas</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">
    <header>
        <?php include("components/navbar.php"); ?>
    </header>
    <main class="container py-4">
       
        <div class="text-center mb-4">
            <div class="cores-background p-3 rounded-4 d-inline-block" data-background="azure-claro-fundo">
                <h2 class="titulo-Sensor mb-0">Trens e rotas</h2>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="cores-background p-3 rounded-4 h-100" data-background="azure-claro-fundo">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Trens</h3>
                        <?php if ($usuarioEhAdmin): ?>
                            <a href="cadastro_trens.php" class="btn btn-sm btn-primary">Gerenciar trens</a>
                        <?php endif; ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Modelo</th>
                                    <th>Responsável</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($trens && $trens->num_rows > 0): ?>
                                    <?php while ($trem = $trens->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars((string) ($trem['id'] ?? '')) ?></td>
                                            <td><?= htmlspecialchars((string) ($trem['nome'] ?? '')) ?></td>
                                            <td><?= htmlspecialchars((string) ($trem['modelo'] ?? '')) ?></td>
                                            <td><?= htmlspecialchars((string) ($trem['responsavel_nome'] ?? 'Sem responsável')) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Nenhum trem cadastrado.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="cores-background p-3 rounded-4 h-100" data-background="azure-claro-fundo">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Rotas</h3>
                        <?php if ($usuarioEhAdmin): ?>
                            <a href="cadastro_rotas.php" class="btn btn-sm btn-primary">Gerenciar rotas</a>
                        <?php endif; ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Distância</th>
                                    <th>Tempo</th>
                                    <th>Trem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rotas && $rotas->num_rows > 0): ?>
                                    <?php while ($rota = $rotas->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars((string) ($rota['id'] ?? '')) ?></td>
                                            <td><?= htmlspecialchars((string) ($rota['nome'] ?? '')) ?></td>
                                            <td><?= htmlspecialchars((string) ($rota['distancia_km'] ?? '')) ?> km</td>
                                            <td><?= htmlspecialchars((string) ($rota['tempo_previsto_min'] ?? '')) ?> min</td>
                                            <td><?= htmlspecialchars((string) ($rota['trem_nome'] ?? '')) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Nenhuma rota cadastrada.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
         
    </main>
<a href="hub.php" class="btn btn-danger back-buttom">Voltar</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
</body>

</html>