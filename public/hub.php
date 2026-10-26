<?php
session_start();

require_once "../infra/conn.php";
require_once "components/start.php";

function escapar_html($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
$id = (int) $_SESSION['id'];
// Buscar as coordenadas do funcionário logado
$stmt = $db->prepare(" SELECT cidade, uf, latitude, longitude FROM funcionario WHERE id = ?");

$stmt->bind_param("i", $id);
$stmt->execute();

$funcionario = $stmt->get_result()->fetch_assoc();
$stmt->close();


$cidade = $funcionario['cidade'];
$uf = $funcionario['uf'];

$url = "https://api.open-meteo.com/v1/forecast?" .
    http_build_query([
        'latitude' => $funcionario['latitude'],
        'longitude' => $funcionario['longitude'],
        'current' => 'temperature_2m,relative_humidity_2m,weather_code',
        'timezone' => 'auto'
    ]);

$resposta = @file_get_contents($url);

if ($resposta === false) {
    exit('Não foi possível consultar o clima agora.');
}

$dados = json_decode($resposta, true);

if (!isset($dados['current'])) {
    exit('A API não retornou os dados meteorológicos.');
}

$atual = $dados['current'];
$temperatura = $atual['temperature_2m'];
$umidade = $atual['relative_humidity_2m'];
$codigo = (int) $atual['weather_code'];

// Traduzir os códigos meteorológicos mais comuns
$condicoes = [
    0 => 'Céu limpo',
    1 => 'Predominantemente limpo',
    2 => 'Parcialmente nublado',
    3 => 'Nublado',
    45 => 'Neblina',
    48 => 'Neblina com geada',
    51 => 'Garoa leve',
    53 => 'Garoa moderada',
    55 => 'Garoa intensa',
    61 => 'Chuva leve',
    63 => 'Chuva moderada',
    65 => 'Chuva intensa',
    71 => 'Neve leve',
    73 => 'Neve moderada',
    75 => 'Neve intensa',
    80 => 'Pancadas de chuva leves',
    81 => 'Pancadas de chuva moderadas',
    82 => 'Pancadas de chuva fortes',
    95 => 'Trovoadas'
];

$descricao = $condicoes[$codigo] ?? 'Condição meteorológica variável';
// Dados de exemplo (os mesmos que estavam fixos no HTML).
// Quando a tabela de sensores existir, troque este array por uma consulta ao banco.
$sensores = [
    ['id' => 1, 'tipo' => 'trem', 'codigo' => 'S.TRM.001', 'velocidade' => 12, 'temperatura' => 56, 'status' => 'Funcionando'],
    ['id' => 2, 'tipo' => 'trem', 'codigo' => 'S.TRM.002', 'velocidade' => 26, 'temperatura' => 45, 'status' => 'Funcionando'],
    ['id' => 3, 'tipo' => 'trem', 'codigo' => 'S.TRM.003', 'velocidade' => 14, 'temperatura' => 24, 'status' => 'Funcionando'],
    ['id' => 4, 'tipo' => 'trem', 'codigo' => 'S.TRM.004', 'velocidade' => 1670, 'temperatura' => 5400, 'status' => 'Funcionando'],
    ['id' => 5, 'tipo' => 'trem', 'codigo' => 'S.TRM.005', 'velocidade' => 0, 'temperatura' => 12, 'status' => 'Não Funcionando'],
    ['id' => 6, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.001', 'temperatura' => 21, 'status' => 'Funcionando'],
    ['id' => 7, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.002', 'temperatura' => 19, 'status' => 'Funcionando'],
    ['id' => 8, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.003', 'temperatura' => 23, 'status' => 'Funcionando'],
    ['id' => 9, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.004', 'temperatura' => 17, 'status' => 'Funcionando'],
    ['id' => 10, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.005', 'temperatura' => 29, 'status' => 'Funcionando'],
];

// Uma coluna para trens e outra para trilhos
$colunas = ['trem' => [], 'trilho' => []];
foreach ($sensores as $sensor) {
    $colunas[$sensor['tipo']][] = $sensor;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Página Inicial</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">
    <header>
        <?php
        include("components/navbar.php");
        ?>
        <div class="clima">
    <h2>Clima em <?= htmlspecialchars($cidade) ?> - <?= htmlspecialchars($uf) ?></h2>

    <div class="temperatura">
        <?= htmlspecialchars((string) $temperatura) ?> °C
    </div>

    <p><?= htmlspecialchars($descricao) ?></p>

    <p class="detalhe">
        Umidade do ar: <?= htmlspecialchars((string) $umidade) ?>%
    </p>

</div>
    </header>

    <main>
        <hr class="opacity-100 cores-background hub-main-hr d-flex m-0" data-background="cinza-claro">
        <section class="container-fluid px-3 py-4 coinblock">
            <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                <h2 class="text-white mb-0">Relatórios em destaque</h2>
                <div class="hub-carousel-controls">
                    <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-target="#hubCarousel"
                        data-bs-slide="prev" aria-label="Gráfico anterior">&#8249;</button>
                    <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-target="#hubCarousel"
                        data-bs-slide="next" aria-label="Próximo gráfico">&#8250;</button>
                </div>
            </div>

            <div id="hubCarousel" class="carousel slide">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="hub-chart-panel">
                            <div class="hub-chart-header">
                                <span>Velocidade média por sensor</span>
                            </div>
                            <div id="chart-velocidade" class="hub-chart"></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="hub-chart-panel">
                            <div class="hub-chart-header">
                                <span>Temperatura média</span>
                            </div>
                            <div id="chart-temperatura" class="hub-chart"></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="hub-chart-panel">
                            <div class="hub-chart-header">
                                <span>Status dos sensores</span>
                            </div>
                            <div id="chart-status" class="hub-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Area sensores -->
        <div class="flex centralizar-tabela mt-4 cores-color" data-color="white">
            <?php foreach ($colunas as $lista): ?>
                <div class="d-flex flex-column">
                    <?php foreach ($lista as $sensor):
                        $id = (int) $sensor['id'];

                        $linhas = [];
                        if (isset($sensor['velocidade'])) {
                            $linhas['Velocidade média:'] = $sensor['velocidade'] . ' km/h';
                        }
                        $linhas['Temperatura média:'] = $sensor['temperatura'] . ' C°';
                        $linhas['Status médio:'] = $sensor['status'];
                        ?>
                        <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                            type="button" data-bs-toggle="collapse" data-bs-target="#lista-sensor<?= $id ?>"
                            aria-expanded="false" aria-controls="lista-sensor<?= $id ?>">
                            <span class="d-block align-items-center cores-color fw-bold" data-color="white">
                                <?= escapar_html($sensor['codigo']) ?>
                                <img src="../assets/icons/seta-para-baixo.png" alt="" class="Home-page-arrow">
                            </span>
                        </button>

                        <div class="collapse" id="lista-sensor<?= $id ?>">
                            <div class="d-flex flex-column">
                                <?php foreach ($linhas as $rotulo => $valor): ?>
                                    <div class="border-tabela grid-element cores-background justify-content-center align-items-center rounded-2 fw-bold"
                                        data-background="verde-escuro">
                                        <div class="justify-content-center align-items-center mt-2 d-flex">
                                            <p class="text-center me-1"><?= escapar_html($rotulo) ?></p>
                                            <p><?= escapar_html($valor) ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <a href="#"
                                    class="border-tabela grid-element cores-background d-flex justify-content-center align-items-center rounded-2 fw-bold text-reset text-decoration-none"
                                    data-background="azul-pastel">
                                    <span class="mt-2">Detalhes</span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Area botoes de modificação -->
        <?php if (($_SESSION['cargo'] ?? '') === 'admin'): ?>
            <div class="justify-content-center align-items-center d-flex mt-5">
                <a href="Sensor.php"
                    class="border-none cores-background me-4 rounded-pill cores-color px-3 py-2 fw-bold hub-main-sensor-management d-inline-block text-decoration-none"
                    data-background="azure-claro-fundo" data-color="white" id="AddSensor">
                    Adicionar sensor
                </a>
                <a href="sensor_delete.php" id="excluirsensor"
                    class="border-none cores-background me-0 rounded-pill cores-color px-3 py-2 fw-bold hub-main-sensor-management d-inline-block text-decoration-none"
                    data-background="azure-claro-fundo" data-color="white">
                    Excluir sensor
                </a>
            </div>
        <?php endif; ?>
    </main>

    <footer>

    </footer>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', { packages: ['corechart'], language: 'pt-BR' });

        const dadosGraficos = [
            {
                id: 'chart-velocidade',
                type: 'LineChart',
                data: [
                    ['Sensor', 'Velocidade média'],
                    ['S.TRM.001', 12],
                    ['S.TRM.002', 26],
                    ['S.TRM.003', 14],
                    ['S.TRM.004', 18]
                ],
                options: {
                    title: 'Velocidade média semanal',
                    backgroundColor: 'transparent',
                    legend: { position: 'none' },
                    hAxis: { textStyle: { color: '#fff' } },
                    vAxis: { textStyle: { color: '#fff' }, minValue: 0 },
                    titleTextStyle: { color: '#fff', fontSize: 18 },
                    colors: ['#67a4ff'],
                    chartArea: { width: '85%', height: '70%' },
                    pointSize: 5
                }
            },
            {
                id: 'chart-temperatura',
                type: 'LineChart',
                data: [
                    ['Semana', 'Temperatura'],
                    ['Seg', 24],
                    ['Ter', 28],
                    ['Qua', 26],
                    ['Qui', 29],
                    ['Sex', 31],
                    ['Sáb', 27]
                ],
                options: {
                    title: 'Temperatura média semanal',
                    backgroundColor: 'transparent',
                    legend: { position: 'none' },
                    hAxis: { textStyle: { color: '#fff' } },
                    vAxis: { textStyle: { color: '#fff' }, minValue: 0 },
                    titleTextStyle: { color: '#fff', fontSize: 18 },
                    colors: ['#67a4ff'],
                    chartArea: { width: '85%', height: '70%' },
                    pointSize: 5
                }
            },
            {
                id: 'chart-status',
                type: 'PieChart',
                data: [
                    ['Status', 'Quantidade'],
                    ['Funcionando', 74],
                    ['Em alerta', 16],
                    ['Manutenção', 10]
                ],
                options: {
                    title: 'Status dos sensores',
                    backgroundColor: 'transparent',
                    legend: { textStyle: { color: '#fff' } },
                    titleTextStyle: { color: '#fff', fontSize: 18 },
                    colors: ['#00bf63', '#ff5757', '#67a4ff'],
                    chartArea: { width: '85%', height: '70%' },
                    pieSliceTextStyle: { color: '#fff' }
                }
            }
        ];

        function desenharGrafico({ id, type, data, options }) {
            const elemento = document.getElementById(id);

            if (!elemento) return;

            const tabela = google.visualization.arrayToDataTable(data);
            const chart = new google.visualization[type](elemento);

            chart.draw(tabela, {
                ...options,
                width: elemento.clientWidth,
                height: 350
            });
        }

        google.charts.setOnLoadCallback(() => {

            dadosGraficos.forEach(desenharGrafico);

            window.addEventListener('resize', () => {
                dadosGraficos.forEach(desenharGrafico);
            });

            const carousel = document.getElementById('hubCarousel');

            carousel.addEventListener('slid.bs.carousel', () => {
                const slideAtivo = carousel.querySelector('.carousel-item.active');

                if (!slideAtivo) return;

                const grafico = slideAtivo.querySelector('.hub-chart');

                if (!grafico) return;

                const dados = dadosGraficos.find(item => item.id === grafico.id);

                if (dados) {
                    desenharGrafico(dados);
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/scriptNavbar.js"></script>
</body>

</html>