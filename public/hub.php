<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../infra/conn.php";
require_once "components/start.php";

function escapar_html($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function formatarAlocacaoComposicao(string $raw): string
{
    $partes = array_map('trim', explode('//', $raw));
    $comp = $partes[0] ?? '';
    $local = $partes[1] ?? '';

    $substituicoes = [
        'Truque Diant.' => 'truque dianteiro',
        'Mancal Tras.' => 'mancal traseiro',
        'Rolamento E1' => 'rolamento E1',
        'Rolamento E4' => 'rolamento E4',
        'Freio Dinâm.' => 'freio dinâmico'
    ];

    if (isset($substituicoes[$local])) {
        $local = $substituicoes[$local];
    }

    return $local !== '' ? ($comp . ', ' . $local) : $comp;
}


$idUsuario = (int) ($_SESSION['id'] ?? 0);
$nomeUsuario = $_SESSION['usuario'] ?? 'Operador Ferroviário';
$cargoUsuario = $_SESSION['cargo'] ?? 'funcionario';
$ehAdmin = ($cargoUsuario === 'admin');
$nivelAcessoLabel = $ehAdmin ? 'Administrador' : 'Operador Ferroviário';

// Localização e coordenadas padrão com fallback seguro (Joinville / SC)
$cidade = 'Joinville';
$uf = 'SC';
$localizacaoPadrao = 'Joinville / SC';
$latitudePadrao = -26.3045;
$longitudePadrao = -48.8487;

// Busca coordenadas e cidade do funcionário no banco
if ($idUsuario > 0 && isset($db) && $db instanceof mysqli) {
    try {
        $stmtCoord = $db->prepare("SELECT cidade, uf, latitude, longitude FROM funcionario WHERE id = ?");
        if ($stmtCoord) {
            $stmtCoord->bind_param("i", $idUsuario);
            if ($stmtCoord->execute()) {
                $resCoord = $stmtCoord->get_result();
                if ($rowCoord = $resCoord->fetch_assoc()) {
                    if (!empty($rowCoord['cidade'])) {
                        $cidade = trim($rowCoord['cidade']);
                    }
                    if (!empty($rowCoord['uf'])) {
                        $uf = strtoupper(trim($rowCoord['uf']));
                    }
                    if (!empty($rowCoord['latitude']) && !empty($rowCoord['longitude'])) {
                        $latVal = (float) $rowCoord['latitude'];
                        $lonVal = (float) $rowCoord['longitude'];
                        if ($latVal >= -90 && $latVal <= 90 && $lonVal >= -180 && $lonVal <= 180) {
                            $latitudePadrao = $latVal;
                            $longitudePadrao = $lonVal;
                        }
                    }
                }
            }
            $stmtCoord->close();
        }
    } catch (Throwable $e) {
        // Fallback seguro caso ocorra erro na busca
    }
}
$localizacaoPadrao = $cidade . ' / ' . $uf;

// Dados meteorológicos com valores nominais padrão para renderização imediata
$temperatura = 21.6;
$sensacaoTermica = 21.1;
$umidade = 57;
$codigoClima = 1;
$descricaoClima = 'Predominantemente Limpo';
$iconeClimaClasse = 'bi-cloud-sun-fill text-warning';
$horaAtualizacaoClima = date('H:i:s');

// Consulta resiliente em PHP (sem exit() para nunca travar a interface)
$urlOpenMeteo = "https://api.open-meteo.com/v1/forecast?" . http_build_query([
    'latitude' => $latitudePadrao,
    'longitude' => $longitudePadrao,
    'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,weather_code',
    'timezone' => 'America/Sao_Paulo'
]);

$ctx = stream_context_create([
    'http' => [
        'timeout' => 3,
        'ignore_errors' => true
    ]
]);

$resClima = @file_get_contents($urlOpenMeteo, false, $ctx);
if ($resClima !== false && $resClima !== '') {
    $dadosClima = json_decode($resClima, true);
    if (!empty($dadosClima['current'])) {
        $atualClima = $dadosClima['current'];
        if (isset($atualClima['temperature_2m'])) {
            $temperatura = (float) $atualClima['temperature_2m'];
        }
        if (isset($atualClima['apparent_temperature'])) {
            $sensacaoTermica = (float) $atualClima['apparent_temperature'];
        } else {
            $sensacaoTermica = $temperatura;
        }
        if (isset($atualClima['relative_humidity_2m'])) {
            $umidade = (int) $atualClima['relative_humidity_2m'];
        }
        if (isset($atualClima['weather_code'])) {
            $codigoClima = (int) $atualClima['weather_code'];
            $mapaWMO = [
                0 => ['bi-sun-fill text-warning', 'Céu Limpo'],
                1 => ['bi-cloud-sun-fill text-warning', 'Predominantemente Limpo'],
                2 => ['bi-cloud-sun text-cyan', 'Parcialmente Nublado'],
                3 => ['bi-clouds-fill text-steel', 'Nublado'],
                45 => ['bi-cloud-haze2-fill text-steel', 'Nevoeiro na Via'],
                51 => ['bi-cloud-drizzle-fill text-cyan', 'Garoa Leve'],
                53 => ['bi-cloud-drizzle-fill text-cyan', 'Garoa Moderada'],
                55 => ['bi-cloud-rain-fill text-cyan', 'Garoa Densa'],
                61 => ['bi-cloud-rain text-cyan', 'Chuva Leve'],
                63 => ['bi-cloud-rain-fill text-cyan', 'Chuva Moderada'],
                65 => ['bi-cloud-rain-heavy-fill text-warning', 'Chuva Forte'],
                80 => ['bi-cloud-drizzle text-cyan', 'Pancadas de Chuva'],
                81 => ['bi-cloud-rain-fill text-cyan', 'Pancadas Moderadas'],
                82 => ['bi-cloud-rain-heavy-fill text-warning', 'Pancadas Fortes'],
                95 => ['bi-cloud-lightning-rain-fill text-danger', 'Tempestade Elétrica'],
                96 => ['bi-cloud-hail-fill text-danger', 'Tempestade com Granizo'],
                99 => ['bi-cloud-lightning-rain-fill text-danger', 'Tempestade Severa']
            ];
            if (isset($mapaWMO[$codigoClima])) {
                $iconeClimaClasse = $mapaWMO[$codigoClima][0];
                $descricaoClima = $mapaWMO[$codigoClima][1];
            }
        }
    }
}

// Sensores com vocabulário operacional ferroviário real
$sensores = [
    ['id' => 1, 'tipo' => 'trem', 'codigo' => 'S.TRM.001', 'subdivisao' => 'Locomotiva C-01 // Truque Diant.', 'velocidade' => 62, 'temperatura' => 52.0, 'status' => 'Funcionando'],
    ['id' => 2, 'tipo' => 'trem', 'codigo' => 'S.TRM.002', 'subdivisao' => 'Locomotiva C-01 // Mancal Tras.',   'velocidade' => 74, 'temperatura' => 45.0, 'status' => 'Funcionando'],
    ['id' => 3, 'tipo' => 'trem', 'codigo' => 'S.TRM.003', 'subdivisao' => 'Vagão Granel V-12 // Rolamento E1', 'velocidade' => 58, 'temperatura' => 49.0, 'status' => 'Funcionando'],
    ['id' => 4, 'tipo' => 'trem', 'codigo' => 'S.TRM.004', 'subdivisao' => 'Vagão Granel V-14 // Rolamento E4', 'velocidade' => 81, 'temperatura' => 88.0, 'status' => 'Em Alerta'],
    ['id' => 5, 'tipo' => 'trem', 'codigo' => 'S.TRM.005', 'subdivisao' => 'Vagão Tanque T-08 // Freio Dinâm.',  'velocidade' => 0,  'temperatura' => 24.0, 'status' => 'Não Funcionando'],
    ['id' => 6, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.001', 'subdivisao' => 'KM 12+400', 'local' => 'Curva Sul (Bitola Larga)', 'velocidade' => null, 'temperatura' => 21.0, 'status' => 'Funcionando'],
    ['id' => 7, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.002', 'subdivisao' => 'KM 14+850', 'local' => 'Junta de Dilatação A',     'velocidade' => null, 'temperatura' => 19.5, 'status' => 'Funcionando'],
    ['id' => 8, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.003', 'subdivisao' => 'KM 18+200', 'local' => 'Ponte Ferroviária Rio 1',   'velocidade' => null, 'temperatura' => 23.0, 'status' => 'Funcionando'],
    ['id' => 9, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.004', 'subdivisao' => 'KM 22+100', 'local' => 'AMV Pátio de Manobras',    'velocidade' => null, 'temperatura' => 17.5, 'status' => 'Funcionando'],
    ['id' => 10, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.005', 'subdivisao' => 'KM 25+900', 'local' => 'Trecho em Túnel da Serra',  'velocidade' => null, 'temperatura' => 29.0, 'status' => 'Funcionando'],
];

// Consultas seguras ao banco
$trensEmCirculacao = 0;
$rotasAtivasCount = 0;
$relatoriosRecentes = 0;

if (isset($db) && $db instanceof mysqli) {
    try {
        $resTrensCount = @$db->query("SELECT COUNT(*) AS total FROM trem");
        if ($resTrensCount && ($rowT = $resTrensCount->fetch_assoc())) {
            $trensEmCirculacao = (int) $rowT['total'];
        }

        $resRotasCount = @$db->query("SELECT COUNT(*) AS total FROM rota");
        if ($resRotasCount && ($rowRt = $resRotasCount->fetch_assoc())) {
            $rotasAtivasCount = (int) $rowRt['total'];
        }

        $resRelatoriosDb = @$db->query("SELECT COUNT(*) AS total FROM dados_sensor");
        if ($resRelatoriosDb && ($rowR = $resRelatoriosDb->fetch_assoc())) {
            $relatoriosRecentes = (int) $rowR['total'];
        }

        $resSensoresLista = @$db->query(
            "SELECT s.id, s.nome, s.tipo, s.trem_id, s.trilho_id,
                    t.nome AS trem_nome, tr.nome AS trilho_nome, tr.localizacao AS trilho_local
             FROM sensor s
             LEFT JOIN trem t ON t.id = s.trem_id
             LEFT JOIN trilho tr ON tr.id = s.trilho_id
             ORDER BY s.id ASC"
        );
        if ($resSensoresLista) {
            $codigosExistentes = array_column($sensores, 'codigo');
            while ($sRow = $resSensoresLista->fetch_assoc()) {
                $codDb = trim((string) ($sRow['nome'] ?? ''));
                if ($codDb === '' || in_array($codDb, $codigosExistentes, true)) {
                    continue;
                }
                $ehTrilho = !empty($sRow['trilho_id']) && empty($sRow['trem_id']);
                $statusDb = (stripos((string) ($sRow['tipo'] ?? ''), 'Falha') !== false) ? 'Em Alerta' : 'Funcionando';
                $sensores[] = [
                    'id' => (int) $sRow['id'],
                    'tipo' => $ehTrilho ? 'trilho' : 'trem',
                    'codigo' => $codDb,
                    'subdivisao' => $ehTrilho
                        ? ($sRow['trilho_nome'] ?: 'Via Permanente')
                        : (($sRow['trem_nome'] ?: 'Composição') . ' // ' . ($sRow['tipo'] ?: 'Telemetria')),
                    'local' => $sRow['trilho_local'] ?: 'Malha Principal',
                    'velocidade' => $ehTrilho ? null : 64,
                    'temperatura' => $ehTrilho ? 22.5 : 48.0,
                    'status' => $statusDb,
                ];
            }
        }
    } catch (Throwable $e) {
        // Fallback gracioso
    }
}

$colunas = ['trem' => [], 'trilho' => []];
$sensoresAtivosCount = 0;
$alertasPendentesCount = 0;
$sensoresCriticos = [];

foreach ($sensores as $s) {
    $colunas[$s['tipo']][] = $s;
    if ($s['status'] === 'Funcionando') {
        $sensoresAtivosCount++;
    } else {
        $alertasPendentesCount++;
    }

    if ($s['temperatura'] >= 85 || $s['status'] === 'Em Alerta' || $s['status'] === 'Não Funcionando') {
        $sensoresCriticos[] = $s;
    }
}

$temAlertaCritico = !empty($sensoresCriticos);

// Data e hora estritamente formatadas em America/Sao_Paulo
date_default_timezone_set('America/Sao_Paulo');
$fusoSP = new DateTimeZone('America/Sao_Paulo');
$agoraSP = new DateTime('now', $fusoSP);
$diasSemana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
$mesesAno = [1 => 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
$diaSemanaTexto = $diasSemana[(int) $agoraSP->format('w')];
$mesTexto = $mesesAno[(int) $agoraSP->format('n')];
$dataAtualFormatada = sprintf('%s, %d de %s de %s', $diaSemanaTexto, (int) $agoraSP->format('d'), $mesTexto, $agoraSP->format('Y'));
$horaAtualizacaoClima = $agoraSP->format('H:i:s');

$pageTitle = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">

<head>
    <?php include 'components/head.php'; ?>
</head>

<body class="cco-dashboard-body">

    <!-- CABEÇALHO GLOBAL & NAVBAR PADRONIZADA -->
    <header>
        <?php include("components/navbar.php"); ?>
    </header>

    <main class="container-fluid px-3 px-xl-4 py-3" style="max-width: 1600px; margin: 0 auto;">

        <section class="row g-3 mb-3 align-items-stretch" aria-label="Identificação Operacional e Condições Meteorológicas da Malha">

            <!-- Painel Esquerdo: Operador Ativo e Subdivisão Ferroviária -->
            <div class="col-12 col-lg-6">
                <div class="ops-panel h-100 d-flex flex-column justify-content-between p-3 p-md-4">
                    <div>
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <span class="ops-role-badge <?= $ehAdmin ? 'ops-role-admin' : 'ops-role-func' ?>">
                                <span class="ops-role-dot"></span><?= escapar_html($nivelAcessoLabel) ?>
                            </span>
                            <span class="text-steel small">
                                <?= escapar_html($cidade) ?>, <?= escapar_html($uf) ?>
                            </span>
                        </div>

                        <h1 class="h3 fw-bold text-white mb-1 brand-font">
                            <?= escapar_html($nomeUsuario) ?>
                        </h1>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between pt-3 mt-3 border-top border-light border-opacity-10 gap-2">
                        <span class="text-steel small">
                            <i class="bi bi-calendar3 me-1 text-warning" aria-hidden="true"></i><?= escapar_html($dataAtualFormatada) ?>
                        </span>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="Usuario_Info.php" class="ops-btn ops-btn-sm" aria-label="Acessar Perfil Operacional">
                                <i class="bi bi-person-vcard" aria-hidden="true"></i>Meu Perfil
                            </a>
                            <?php if ($ehAdmin): ?>
                                <a href="trens_trilhos.php" class="ops-btn ops-btn-primary ops-btn-sm">
                                    <i class="bi bi-train-front" aria-hidden="true"></i>Frota e Rotas
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Painel Direito: Estação Meteorológica da Malha (Open-Meteo) -->
            <div class="col-12 col-lg-6">
                <div class="ops-panel h-100 d-flex flex-column justify-content-between p-3 p-md-4"
                    id="widget-clima-cco"
                    data-lat="<?= escapar_html($latitudePadrao) ?>"
                    data-lon="<?= escapar_html($longitudePadrao) ?>"
                    data-local="<?= escapar_html($localizacaoPadrao) ?>">

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt-fill text-warning" aria-hidden="true"></i>
                            <strong id="clima-localizacao" class="text-white small">
                                <?= escapar_html($localizacaoPadrao) ?>
                            </strong>
                            <span class="text-steel small">Via Principal</span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span id="clima-atualizacao" class="text-steel mono-font" style="font-size: 0.74rem;">
                                Sincronizado às <?= escapar_html($horaAtualizacaoClima) ?>
                            </span>
                            <button type="button" id="btn-atualizar-clima" class="ops-btn p-0"
                                style="width: 38px; min-height: 38px;"
                                title="Sincronizar telemetria meteorológica da via" aria-label="Atualizar dados meteorológicos Open-Meteo">
                                <span id="clima-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <i id="clima-btn-icon" class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Dados Meteorológicos Estritos (Temperatura, Condição, Sensação e Umidade) -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 my-auto py-2">
                        <div class="d-flex align-items-center gap-3">
                            <div id="clima-icone" class="fs-1 d-flex align-items-center justify-content-center" aria-hidden="true" style="width: 48px; height: 48px;">
                                <i class="bi <?= escapar_html($iconeClimaClasse) ?>"></i>
                            </div>
                            <div>
                                <span id="clima-temperatura" class="fs-3 fw-bold text-white mono-font d-block" style="line-height: 1.1;">
                                    <?= number_format($temperatura, 1, '.', '') ?> °C
                                </span>
                                <span id="clima-condicao" class="small text-steel">
                                    <?= escapar_html($descricaoClima) ?>
                                </span>
                            </div>
                        </div>

                        <div class="ops-weather-box ms-auto">
                            <div class="ops-weather-metric">
                                <span class="d-block text-steel small" style="font-size: 0.72rem;">Sensação</span>
                                <strong id="clima-sensacao" class="text-white mono-font fs-6"><?= number_format($sensacaoTermica, 1, '.', '') ?> °C</strong>
                            </div>
                            <div class="ops-weather-metric">
                                <span class="d-block text-steel small" style="font-size: 0.72rem;">Umidade</span>
                                <strong id="clima-umidade" class="text-white mono-font fs-6"><?= (int) $umidade ?>%</strong>
                            </div>
                        </div>
                    </div>

                    <div id="clima-erro-box" class="ops-alert ops-alert-warning py-2 px-3 mb-0 mt-2 small d-none" role="status">
                        <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                        <span id="clima-erro-mensagem"></span>
                    </div>

                    <!-- Alerta Dinâmico para Eventos Meteorológicos Severos (WMO 95/96/99) -->
                    <div id="clima-alerta-extremo" class="ops-alert-banner mt-2 d-none" role="alert" style="border: 1px solid rgba(239, 68, 68, 0.45); border-radius: 4px; padding: 0.5rem 0.85rem;">
                        <div class="d-flex align-items-center gap-2 small">
                            <i class="bi bi-lightning-fill text-danger" aria-hidden="true"></i>
                            <strong class="text-white mono-font">ALERTA CLIMÁTICO SEVERO:</strong>
                            <span class="text-steel">Condição de tempestade detectada na via. Redobrar atenção à frenagem.</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="row g-3 mb-3" aria-label="Curvas de Telemetria e Monitoramento em Tempo Real">

            <!-- Gráfico 1: Velocidade por Composição (km/h) -->
            <div class="col-12 col-lg-4">
                <div class="ops-panel h-100 d-flex flex-column">
                    <div class="ops-panel-header">
                        <h2 class="ops-panel-title mb-0">Velocidade por composição</h2>
                    </div>
                    <div class="ops-card-metric">
                        <span class="ops-metric-val">1</span>
                        <span class="ops-metric-sub">acima do limite de 80 km/h</span>
                    </div>
                    <div class="ops-panel-body flex-grow-1 p-2">
                        <div id="chart-velocidade" class="ops-chart-container"></div>
                    </div>
                </div>
            </div>

            <!-- Gráfico 2: Curva Térmica da Malha (°C) -->
            <div class="col-12 col-lg-4">
                <div class="ops-panel h-100 d-flex flex-column">
                    <div class="ops-panel-header">
                        <h2 class="ops-panel-title mb-0">Curva térmica da malha</h2>
                    </div>
                    <div class="ops-card-metric">
                        <span class="ops-metric-val">64 °C</span>
                        <span class="ops-metric-sub">pico registrado na semana</span>
                    </div>
                    <div class="ops-panel-body flex-grow-1 p-2">
                        <div id="chart-temperatura" class="ops-chart-container"></div>
                    </div>
                </div>
            </div>

            <!-- Gráfico 3: Disponibilidade de Sensores (%) -->
            <div class="col-12 col-lg-4">
                <div class="ops-panel h-100 d-flex flex-column">
                    <div class="ops-panel-header">
                        <h2 class="ops-panel-title mb-0">Disponibilidade de sensores</h2>
                    </div>
                    <div class="ops-card-metric">
                        <span class="ops-metric-val">8 de 10</span>
                        <span class="ops-metric-sub">sensores operando normalmente</span>
                    </div>
                    <div class="ops-panel-body flex-grow-1 p-2">
                        <div class="ops-donut-wrap">
                            <div id="chart-status" class="ops-chart-container"></div>
                            <div class="ops-donut-center">
                                <span class="ops-donut-count">10</span>
                                <span class="ops-donut-label">sensores</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <section class="ops-panel mb-3" aria-label="Sensores da malha ferroviária">

            <div class="ops-panel-header">
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="ops-panel-title mb-0">Sensores da malha</h2>
                    <span class="text-steel small"><?= count($sensores) ?> sensores</span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="Sensor_Data.php" class="ops-btn ops-btn-primary ops-btn-sm">
                        Ver todos
                    </a>
                </div>
            </div>

            <?php if ($temAlertaCritico): ?>
                <div class="ops-anomaly-banner d-flex flex-wrap align-items-center justify-content-between px-3 py-2 border-bottom border-light border-opacity-10 gap-2" role="alert">
                    <span class="text-white small">
                        <span class="mono-font fw-semibold"><?= escapar_html($sensoresCriticos[0]['codigo']) ?></span> está a <span class="mono-font fw-semibold"><?= number_format((float) $sensoresCriticos[0]['temperatura'], 1, '.', '') ?> °C</span>, acima do limite de <span class="mono-font">85 °C</span>.
                    </span>
                    <a href="Sensor_Data.php?filtro=alerta" class="ops-btn ops-btn-sm">
                        Inspecionar
                    </a>
                </div>
            <?php endif; ?>

            <div class="ops-panel-body p-0">
                <div class="row g-0">

                    <!-- Painel 1: Em composições -->
                    <div class="col-12 col-lg-6 border-end border-light border-opacity-10">
                        <div class="py-2 px-3 border-bottom border-light border-opacity-10" style="background: rgba(255,255,255,0.015);">
                            <span class="text-white fw-semibold small">Em composições</span>
                        </div>

                        <div class="table-responsive">
                            <table class="ops-table ops-table-compact" aria-label="Tabela de sensores em composições">
                                <thead>
                                    <tr>
                                        <th scope="col">Sensor e alocação</th>
                                        <th scope="col" class="text-end">Velocidade</th>
                                        <th scope="col" class="text-end">Temperatura</th>
                                        <th scope="col" class="text-end">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($colunas['trem'] as $s):
                                        $statusLabel = 'Nominal';
                                        $statusClass = 'status-nominal';
                                        $rowClass = '';

                                        if ($s['status'] === 'Em Alerta') {
                                            $statusLabel = 'Alerta';
                                            $statusClass = 'status-alerta';
                                            $rowClass = 'row-sensor-alerta';
                                        } elseif ($s['status'] === 'Não Funcionando' || $s['status'] === 'Inoperante') {
                                            $statusLabel = 'Inoperante';
                                            $statusClass = 'status-inoperante';
                                            $rowClass = 'row-sensor-inoperante';
                                        }

                                        $alocacaoFormatada = formatarAlocacaoComposicao($s['subdivisao'] ?? '');
                                    ?>
                                        <tr class="<?= $rowClass ?>">
                                            <td>
                                                <strong class="mono-font text-white d-block"><?= escapar_html($s['codigo']) ?></strong>
                                                <span class="text-steel small d-block"><?= escapar_html($alocacaoFormatada) ?></span>
                                            </td>
                                            <td class="text-end mono-font">
                                                <?= isset($s['velocidade']) ? ((int) $s['velocidade'] . ' <span class="text-steel" style="font-size: 0.72rem;">km/h</span>') : '&mdash;' ?>
                                            </td>
                                            <td class="text-end mono-font">
                                                <span class="<?= $s['temperatura'] >= 85 ? 'text-warning fw-bold' : ($s['temperatura'] >= 60 ? 'text-warning' : 'text-white') ?>">
                                                    <?= number_format((float) $s['temperatura'], 1, '.', '') ?> °C
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="ops-sensor-status <?= $statusClass ?>">
                                                    <span class="status-dot"></span>
                                                    <span><?= $statusLabel ?></span>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Painel 2: Na via -->
                    <div class="col-12 col-lg-6">
                        <div class="py-2 px-3 border-bottom border-light border-opacity-10" style="background: rgba(255,255,255,0.015);">
                            <span class="text-white fw-semibold small">Na via</span>
                        </div>

                        <div class="table-responsive">
                            <table class="ops-table ops-table-compact" aria-label="Tabela de sensores fixos na via">
                                <thead>
                                    <tr>
                                        <th scope="col">Sensor e quilometragem</th>
                                        <th scope="col">Ponto</th>
                                        <th scope="col" class="text-end">Temperatura</th>
                                        <th scope="col" class="text-end">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($colunas['trilho'] as $s):
                                        $statusLabel = 'Nominal';
                                        $statusClass = 'status-nominal';
                                        $rowClass = '';

                                        if ($s['status'] === 'Em Alerta') {
                                            $statusLabel = 'Alerta';
                                            $statusClass = 'status-alerta';
                                            $rowClass = 'row-sensor-alerta';
                                        } elseif ($s['status'] === 'Não Funcionando' || $s['status'] === 'Inoperante') {
                                            $statusLabel = 'Inoperante';
                                            $statusClass = 'status-inoperante';
                                            $rowClass = 'row-sensor-inoperante';
                                        }
                                    ?>
                                        <tr class="<?= $rowClass ?>">
                                            <td>
                                                <strong class="mono-font text-white d-block"><?= escapar_html($s['codigo']) ?></strong>
                                                <span class="mono-font text-steel small"><?= escapar_html($s['subdivisao']) ?></span>
                                            </td>
                                            <td>
                                                <span class="text-steel small"><?= escapar_html($s['local'] ?? 'Via permanente') ?></span>
                                            </td>
                                            <td class="text-end mono-font">
                                                <span class="<?= $s['temperatura'] >= 50 ? 'text-warning' : 'text-white' ?>">
                                                    <?= number_format((float) $s['temperatura'], 1, '.', '') ?> °C
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="ops-sensor-status <?= $statusClass ?>">
                                                    <span class="status-dot"></span>
                                                    <span><?= $statusLabel ?></span>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Rodapé Técnico Sóbrio -->
            <div class="ops-panel-footer">
                <div class="text-steel small">
                    Última leitura às <?= $agoraSP->format('H:i') ?>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-3">
                    <a href="Relatories.php" class="ops-link-subtle small">
                        Ver histórico
                    </a>
                    <?php if ($ehAdmin): ?>
                        <a href="Sensor.php" id="AddSensor" class="ops-btn ops-btn-sm ops-btn-ghost">
                            Cadastrar sensor
                        </a>
                        <a href="Sensor_Manage.php" id="gerenciarsensor" class="ops-btn ops-btn-sm ops-btn-ghost">
                            Gerenciar sensores
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </section>

    </main>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', { packages: ['corechart'], language: 'pt-BR' });

        function desenharGraficos() {
            if (typeof google === 'undefined' || !google.visualization) return;

            // 1. Velocidade por composição (Barras + Linha de teto 80 km/h)
            const elVelocidade = document.getElementById('chart-velocidade');
            if (elVelocidade) {
                const dataVel = google.visualization.arrayToDataTable([
                    ['Composição', 'Velocidade (km/h)', { role: 'style' }, { role: 'annotation' }, 'Teto (80 km/h)'],
                    ['C-01', 62, '#64748b', null, 80],
                    ['C-02', 74, '#64748b', null, 80],
                    ['V-12', 58, '#64748b', null, 80],
                    ['V-14', 81, '#f59e0b', '81 km/h', 80],
                    ['T-08', 4,  '#334155', 'Parada', 80]
                ]);

                const optVel = {
                    title: '',
                    backgroundColor: 'transparent',
                    legend: { position: 'none' },
                    seriesType: 'bars',
                    series: {
                        1: {
                            type: 'line',
                            color: '#ef4444',
                            lineDashStyle: [4, 4],
                            lineWidth: 1.5,
                            pointSize: 0,
                            visibleInLegend: false
                        }
                    },
                    hAxis: {
                        textStyle: { color: '#94a3b8', fontName: 'Inter', fontSize: 11 },
                        gridlines: { color: 'transparent' }
                    },
                    vAxis: {
                        minValue: 0,
                        maxValue: 100,
                        textStyle: { color: '#94a3b8', fontName: 'Inter', fontSize: 11 },
                        gridlines: { count: 6, color: 'rgba(255,255,255,0.05)' },
                        minorGridlines: { count: 0 },
                        baselineColor: 'rgba(255,255,255,0.1)'
                    },
                    chartArea: { left: 38, top: 18, right: 16, bottom: 32, width: '92%', height: '80%' },
                    annotations: {
                        textStyle: { fontName: 'Inter', fontSize: 10, color: '#f8fafc', bold: true }
                    }
                };

                const chartVel = new google.visualization.ComboChart(elVelocidade);
                chartVel.draw(dataVel, optVel);
            }

            // 2. Curva térmica da malha (Linha Âmbar + Linha limite 85 °C)
            const elTemperatura = document.getElementById('chart-temperatura');
            if (elTemperatura) {
                const dataTemp = google.visualization.arrayToDataTable([
                    ['Dia', 'Média da Via (°C)', 'Limite Seguro (85 °C)'],
                    ['Seg', 52, 85],
                    ['Ter', 56, 85],
                    ['Qua', 54, 85],
                    ['Qui', 61, 85],
                    ['Sex', 64, 85],
                    ['Sáb', 55, 85],
                    ['Dom', 48, 85]
                ]);

                const optTemp = {
                    title: '',
                    backgroundColor: 'transparent',
                    legend: {
                        position: 'top',
                        alignment: 'end',
                        textStyle: { color: '#94a3b8', fontName: 'Inter', fontSize: 10 }
                    },
                    hAxis: {
                        textStyle: { color: '#94a3b8', fontName: 'Inter', fontSize: 11 },
                        gridlines: { color: 'transparent' }
                    },
                    vAxis: {
                        minValue: 20,
                        maxValue: 100,
                        textStyle: { color: '#94a3b8', fontName: 'Inter', fontSize: 11 },
                        gridlines: { count: 5, color: 'rgba(255,255,255,0.05)' },
                        minorGridlines: { count: 0 },
                        baselineColor: 'rgba(255,255,255,0.1)'
                    },
                    colors: ['#f59e0b', '#ef4444'],
                    chartArea: { left: 38, top: 24, right: 16, bottom: 32, width: '92%', height: '76%' },
                    pointSize: 4,
                    lineWidth: 2,
                    series: {
                        1: { lineDashStyle: [4, 4], pointSize: 0, lineWidth: 1.5 }
                    }
                };

                const chartTemp = new google.visualization.LineChart(elTemperatura);
                chartTemp.draw(dataTemp, optTemp);
            }

            // 3. Disponibilidade de sensores (Donut refinado com total no centro)
            const elStatus = document.getElementById('chart-status');
            if (elStatus) {
                const dataSt = google.visualization.arrayToDataTable([
                    ['Status', 'Quantidade'],
                    ['Nominais', 8],
                    ['Alerta térmico', 1],
                    ['Inoperante', 1]
                ]);

                const optSt = {
                    title: '',
                    backgroundColor: 'transparent',
                    legend: {
                        position: 'bottom',
                        alignment: 'center',
                        textStyle: { color: '#94a3b8', fontName: 'Inter', fontSize: 11 }
                    },
                    colors: ['#38bdf8', '#f59e0b', '#ef4444'],
                    chartArea: { left: 10, top: 6, right: 10, bottom: 28, width: '94%', height: '84%' },
                    pieHole: 0.72,
                    pieSliceBorderColor: '#0b1523',
                    pieSliceText: 'none'
                };

                const chartSt = new google.visualization.PieChart(elStatus);
                chartSt.draw(dataSt, optSt);
            }
        }

        google.charts.setOnLoadCallback(() => {
            desenharGraficos();

            let timerRedim;
            window.addEventListener('resize', () => {
                clearTimeout(timerRedim);
                timerRedim = setTimeout(desenharGraficos, 120);
            });
        });
    </script>

    <!-- Script de Integração com Clima Open-Meteo -->
    <script src="../scripts/scriptClima.js"></script>

    <!-- Bootstrap Bundle JS e Script da Navbar -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
</body>

</html>
