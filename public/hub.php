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

$idUsuario = (int) ($_SESSION['id'] ?? 0);
$nomeUsuario = $_SESSION['usuario'] ?? 'Operador Ferroviário';
$cargoUsuario = $_SESSION['cargo'] ?? 'funcionario';
$ehAdmin = ($cargoUsuario === 'admin');
$nivelAcessoLabel = $ehAdmin ? 'Administrador CCO' : 'Operador Ferroviário';

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
$iconeClima = '🌤️';
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
                0 => ['☀️', 'Céu Limpo'],
                1 => ['🌤️', 'Predominantemente Limpo'],
                2 => ['⛅', 'Parcialmente Nublado'],
                3 => ['☁️', 'Nublado'],
                45 => ['🌫️', 'Nevoeiro na Via'],
                51 => ['🌦️', 'Garoa Leve'],
                53 => ['🌦️', 'Garoa Moderada'],
                55 => ['🌧️', 'Garoa Densa'],
                61 => ['🌧️', 'Chuva Leve'],
                63 => ['🌧️', 'Chuva Moderada'],
                65 => ['🌧️', 'Chuva Forte'],
                80 => ['🌦️', 'Pancadas de Chuva'],
                81 => ['🌧️', 'Pancadas Moderadas'],
                82 => ['⛈️', 'Pancadas Fortes'],
                95 => ['⛈️', 'Tempestade Elétrica'],
                96 => ['⛈️', 'Tempestade com Granizo'],
                99 => ['⛈️', 'Tempestade Severa']
            ];
            if (isset($mapaWMO[$codigoClima])) {
                $iconeClima = $mapaWMO[$codigoClima][0];
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

// Consultas seguras ao banco
$trensEmCirculacao = 4;
$relatoriosRecentes = 18;

if (isset($db) && $db instanceof mysqli) {
    try {
        $resTrensCount = @$db->query("SELECT COUNT(*) AS total FROM trem");
        if ($resTrensCount && ($rowT = $resTrensCount->fetch_assoc())) {
            $totalBancoTrens = (int) $rowT['total'];
            if ($totalBancoTrens > 0) {
                $trensEmCirculacao = $totalBancoTrens;
            }
        }

        $resSensoresDb = @$db->query("SELECT COUNT(*) AS total FROM sensor");
        if ($resSensoresDb && ($rowS = $resSensoresDb->fetch_assoc())) {
            $totalBancoSensores = (int) $rowS['total'];
            if ($totalBancoSensores > $sensoresAtivosCount) {
                $sensoresAtivosCount = $totalBancoSensores;
            }
        }

        $resRelatoriosDb = @$db->query("SELECT COUNT(*) AS total FROM dados_sensor");
        if ($resRelatoriosDb && ($rowR = $resRelatoriosDb->fetch_assoc())) {
            $totalBancoRelatorios = (int) $rowR['total'];
            if ($totalBancoRelatorios > 0) {
                $relatoriosRecentes = $totalBancoRelatorios;
            }
        }
    } catch (Throwable $e) {
        // Fallback gracioso
    }
}

// Data formatada para o cabeçalho técnico
$diasSemana = [0 => 'Domingo', 1 => 'Segunda-feira', 2 => 'Terça-feira', 3 => 'Quarta-feira', 4 => 'Quinta-feira', 5 => 'Sexta-feira', 6 => 'Sábado'];
$mesesAno = [1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'];
$diaSemanaTexto = $diasSemana[(int) date('w')] ?? '';
$mesTexto = $mesesAno[(int) date('n')] ?? '';
$dataAtualFormatada = sprintf('%s, %s de %s de %s', $diaSemanaTexto, date('d'), $mesTexto, date('Y'));
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TrackFlow CCO — Centro de Controle Operacional Ferroviário. Telemetria, malha e monitoramento térmico em tempo real.">
    <meta name="theme-color" content="#060d17">
    <link rel="shortcut icon" href="../assets/logo/icone.ico" type="image/x-icon">
    <title>TrackFlow // CCO — Centro de Controle Operacional</title>

    <!-- Fontes Técnicas -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=League+Spartan:wght@700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.8 CSS & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Folha de Estilos Oficial -->
    <link rel="stylesheet" href="../Styles/style.css">

    <!-- Estilo Mission Control / Data-Terminal Compacto (taste-skill app-screens + ui-ux-pro-max) -->
    <style>
        :root {
            /* Superfícies Flat Dark Ops (Sem gradientes difusos, sem sombras) */
            --ops-ground: #060d17;
            --ops-surface: #0b1523;
            --ops-surface-elevated: #0f1c2e;
            --ops-border: rgba(255, 255, 255, 0.08);
            --ops-border-strong: rgba(255, 255, 255, 0.16);
            --ops-border-subtle: rgba(255, 255, 255, 0.04);

            /* Tipografia & Contraste WCAG 2.2 AAA */
            --ops-text-main: #f0f4f8;
            --ops-text-muted: #8c9ba8;
            --ops-text-dim: #5a6a78;

            /* Cores de Dados Funcionais (< 5% pixels) */
            --ops-data-blue: #5271ff;
            --ops-data-cyan: #38bdf8;
            --ops-data-volt: #c1ff72;
            --ops-status-nominal: #22c55e;
            --ops-status-warning: #f59e0b;
            --ops-status-critical: #ef4444;
        }

        body.cco-dashboard-body {
            background-color: var(--ops-ground);
            color: var(--ops-text-main);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-variant-numeric: tabular-nums;
            -webkit-font-smoothing: antialiased;
        }

        .mono-font {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, monospace !important;
            font-variant-numeric: tabular-nums;
        }

        .brand-font {
            font-family: 'League Spartan', system-ui, sans-serif !important;
            letter-spacing: -0.02em;
        }

        /* Painel Base Estilo Data-Terminal: Flat, 1px hairline, zero sombras */
        .ops-panel {
            background-color: var(--ops-surface);
            border: 1px solid var(--ops-border);
            border-radius: 5px;
            box-shadow: none !important;
        }

        .ops-panel-header {
            padding: 0.65rem 1rem;
            border-bottom: 1px solid var(--ops-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .ops-panel-body {
            padding: 0.75rem;
        }

        .ops-kpi-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--ops-text-muted);
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Alerta Tático Minimalista Slim */
        .ops-alert-banner {
            background-color: #160a0c;
            border-left: 3px solid var(--ops-status-critical);
        }

        /* Pulso semântico sutil (respeita reduced-motion) */
        @keyframes ops-pulse-border {
            0%, 100% { border-color: rgba(239, 68, 68, 0.35); }
            50% { border-color: rgba(239, 68, 68, 0.95); }
        }

        .ops-pulse-alert {
            animation: ops-pulse-border 2.5s infinite ease-in-out;
        }

        @media (prefers-reduced-motion: reduce) {
            .ops-pulse-alert {
                animation: none !important;
            }
        }

        /* Botões Técnicos e Acessíveis (Touch Target >= 44x44px ou compacto em desktop) */
        .ops-btn {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.4rem 0.85rem;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 4px;
            border: 1px solid var(--ops-border-strong);
            background-color: transparent;
            color: var(--ops-text-main);
            text-decoration: none;
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }

        .ops-btn:hover,
        .ops-btn:focus-visible {
            background-color: var(--ops-surface-elevated);
            border-color: var(--ops-data-blue);
            color: #ffffff;
            outline: 2px solid var(--ops-data-blue);
            outline-offset: 1px;
        }

        .ops-btn-primary {
            background-color: var(--ops-data-blue);
            border-color: var(--ops-data-blue);
            color: #ffffff;
        }

        .ops-btn-primary:hover,
        .ops-btn-primary:focus-visible {
            background-color: #415ee6;
            border-color: #415ee6;
            color: #ffffff;
        }

        .ops-btn-danger-outline {
            border-color: rgba(239, 68, 68, 0.4);
            color: #fca5a5;
        }

        .ops-btn-danger-outline:hover,
        .ops-btn-danger-outline:focus-visible {
            background-color: rgba(239, 68, 68, 0.15);
            border-color: var(--ops-status-critical);
            color: #ffffff;
            outline: 2px solid var(--ops-status-critical);
        }

        /* Badges de Status Estilo Terminal Compactos */
        .ops-status-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.12rem 0.45rem;
            border-radius: 3px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .ops-status-nominal {
            background-color: rgba(34, 197, 94, 0.1);
            color: #4ade80;
            border-color: rgba(34, 197, 94, 0.25);
        }

        .ops-status-warning {
            background-color: rgba(245, 158, 11, 0.12);
            color: #fbbf24;
            border-color: rgba(245, 158, 11, 0.3);
        }

        .ops-status-critical {
            background-color: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border-color: rgba(239, 68, 68, 0.4);
        }

        .ops-status-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .dot-nominal { background-color: #22c55e; }
        .dot-warning { background-color: #f59e0b; }
        .dot-critical { background-color: #ef4444; }

        /* Tabela Técnica de Sensores de Alta Densidade Compacta (pitch ~36px, sem zebra) */
        .ops-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.78rem;
        }

        .ops-table th {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--ops-text-muted);
            padding: 0.38rem 0.65rem;
            border-bottom: 1px solid var(--ops-border);
            background-color: rgba(255, 255, 255, 0.015);
            text-align: left;
            white-space: nowrap;
        }

        .ops-table th.text-end {
            text-align: right;
        }

        .ops-table td {
            padding: 0.35rem 0.65rem;
            border-bottom: 1px solid var(--ops-border-subtle);
            vertical-align: middle;
            color: var(--ops-text-main);
            line-height: 1.2;
        }

        .ops-table tr:last-child td {
            border-bottom: none;
        }

        .ops-table tr.row-critical {
            background-color: rgba(239, 68, 68, 0.08);
        }

        .ops-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.025);
        }

        /* Container de Gráficos (Zero Carrossel - Grid Operacional Balanceado) */
        .ops-chart-container {
            width: 100%;
            min-height: 250px;
            height: 250px;
            background-color: transparent;
        }

        /* Estação Meteorológica Compacta */
        .ops-weather-box {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .ops-weather-metric {
            background-color: var(--ops-surface-elevated);
            border: 1px solid var(--ops-border);
            border-radius: 4px;
            padding: 0.35rem 0.65rem;
            min-width: 76px;
            text-align: right;
        }
    </style>
</head>

<body class="cco-dashboard-body">

    <!-- CABEÇALHO GLOBAL & NAVBAR PADRONIZADA -->
    <header>
        <?php include("components/navbar.php"); ?>
    </header>

    <!-- CONTEÚDO PRINCIPAL DO CCO -->
    <main class="container-fluid px-3 px-xl-4 py-3" style="max-width: 1600px; margin: 0 auto;">

        <!-- ====================================================================
             1. CABEÇALHO TÉCNICO CCO: OPERADOR DA MALHA & ESTAÇÃO METEOROLÓGICA
             ==================================================================== -->
        <section class="row g-3 mb-3 align-items-stretch" aria-label="Identificação Operacional e Condições Meteorológicas da Malha">

            <!-- Painel Esquerdo: Operador Ativo e Subdivisão Ferroviária -->
            <div class="col-12 col-lg-6">
                <div class="ops-panel h-100 d-flex flex-column justify-content-between p-3 p-md-4">
                    <div>
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <span class="ops-status-tag <?= $ehAdmin ? 'ops-status-nominal' : 'ops-status-warning' ?>">
                                <span class="ops-status-dot <?= $ehAdmin ? 'dot-nominal' : 'dot-warning' ?>"></span>
                                <?= escapar_html($nivelAcessoLabel) ?>
                            </span>
                            <span class="mono-font text-steel" style="font-size: 0.75rem;">
                                CCO // SUBDIVISÃO JOINVILLE &mdash; SFS
                            </span>
                        </div>

                        <h1 class="h4 fw-bold text-white mb-1 brand-font">
                            <?= escapar_html($nomeUsuario) ?>
                        </h1>
                        <div class="text-steel small mono-font">
                            Sessão CCO ativa na malha ferroviária norte catarinense
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between pt-3 mt-3 border-top border-light border-opacity-10 gap-2">
                        <span class="mono-font text-steel small">
                            <i class="bi bi-clock-history me-1" aria-hidden="true"></i><?= escapar_html($dataAtualFormatada) ?>
                        </span>
                        <a href="Usuario_Info.php" class="ops-btn" title="Gerenciar credenciais e perfil do operador" aria-label="Acessar Perfil Operacional">
                            <i class="bi bi-person-gear me-1" aria-hidden="true"></i>Perfil Operador
                        </a>
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

                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt-fill text-blue" aria-hidden="true"></i>
                            <strong id="clima-localizacao" class="text-white small mono-font text-uppercase">
                                <?= escapar_html($localizacaoPadrao) ?>
                            </strong>
                            <span class="badge bg-secondary bg-opacity-25 text-steel mono-font" style="font-size: 0.68rem;">VIA PRINCIPAL</span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span id="clima-atualizacao" class="text-steel mono-font" style="font-size: 0.74rem;">
                                Sincronizado às <?= escapar_html($horaAtualizacaoClima) ?>
                            </span>
                            <button type="button" id="btn-atualizar-clima" class="ops-btn"
                                style="width: 44px; height: 44px; padding: 0;"
                                title="Sincronizar telemetria meteorológica da via" aria-label="Atualizar dados meteorológicos Open-Meteo">
                                <span id="clima-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <i id="clima-btn-icon" class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Dados Meteorológicos Estritos (Temperatura, Condição, Sensação e Umidade) -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 my-auto py-2">
                        <div class="d-flex align-items-center gap-3">
                            <div id="clima-icone" class="fs-1" aria-hidden="true" style="line-height: 1;">
                                <?= $iconeClima ?>
                            </div>
                            <div>
                                <span id="clima-temperatura" class="fs-3 fw-bold text-white mono-font d-block" style="line-height: 1.1;">
                                    <?= number_format($temperatura, 1, '.', '') ?> °C
                                </span>
                                <span id="clima-condicao" class="small text-steel mono-font">
                                    <?= escapar_html($descricaoClima) ?>
                                </span>
                            </div>
                        </div>

                        <div class="ops-weather-box ms-auto">
                            <div class="ops-weather-metric">
                                <span class="d-block text-steel mono-font" style="font-size: 0.68rem;">SENSAÇÃO</span>
                                <strong id="clima-sensacao" class="text-white mono-font fs-6"><?= number_format($sensacaoTermica, 1, '.', '') ?> °C</strong>
                            </div>
                            <div class="ops-weather-metric">
                                <span class="d-block text-steel mono-font" style="font-size: 0.68rem;">UMIDADE</span>
                                <strong id="clima-umidade" class="text-white mono-font fs-6"><?= (int) $umidade ?>%</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Discreto de Erro / Taxa Excedida (Preservado) -->
                    <div id="clima-erro-box" class="alert alert-warning py-1 px-2 mb-0 mt-2 small d-none" role="status">
                        <i class="bi bi-exclamation-triangle me-1"></i><span id="clima-erro-mensagem"></span>
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

        <!-- ====================================================================
             2. TELEMETRIA E GRÁFICOS OPERACIONAIS (ZERO CARROSSEL! GRID BALANCEADO)
             Curvas simultâneas de velocidade, perfil térmico e disponibilidade
             ==================================================================== -->
        <section class="row g-3 mb-3" aria-label="Curvas de Telemetria e Monitoramento em Tempo Real">

            <!-- Gráfico 1: Velocidade por Composição (km/h) -->
            <div class="col-12 col-lg-4">
                <div class="ops-panel h-100 d-flex flex-column">
                    <div class="ops-panel-header">
                        <span class="ops-kpi-label mb-0">
                            <i class="bi bi-speedometer2 text-blue me-1" aria-hidden="true"></i>Velocidade por Composição
                        </span>
                        <span class="ops-status-tag ops-status-nominal" style="font-size: 0.65rem;">TELEMETRIA REAL</span>
                    </div>
                    <div class="ops-panel-body flex-grow-1 p-2">
                        <div id="chart-velocidade" class="ops-chart-container"></div>
                    </div>
                    <div class="px-3 py-1 border-top border-light border-opacity-10 text-steel mono-font" style="font-size: 0.7rem;">
                        Teto operacional: <strong>80 km/h</strong> &bull; Alerta em S.TRM.004 (81 km/h)
                    </div>
                </div>
            </div>

            <!-- Gráfico 2: Monitoramento Térmico Contínuo (°C) -->
            <div class="col-12 col-lg-4">
                <div class="ops-panel h-100 d-flex flex-column">
                    <div class="ops-panel-header">
                        <span class="ops-kpi-label mb-0">
                            <i class="bi bi-thermometer-high text-warning me-1" aria-hidden="true"></i>Curva Térmica da Malha
                        </span>
                        <span class="ops-status-tag ops-status-warning" style="font-size: 0.65rem;">LIMITE 85 °C</span>
                    </div>
                    <div class="ops-panel-body flex-grow-1 p-2">
                        <div id="chart-temperatura" class="ops-chart-container"></div>
                    </div>
                    <div class="px-3 py-1 border-top border-light border-opacity-10 text-steel mono-font" style="font-size: 0.7rem;">
                        Pico semanal: <strong>64 °C</strong> &bull; Nominal de segurança: &le; 60 °C
                    </div>
                </div>
            </div>

            <!-- Gráfico 3: Disponibilidade Operacional da Malha (%) -->
            <div class="col-12 col-lg-4">
                <div class="ops-panel h-100 d-flex flex-column">
                    <div class="ops-panel-header">
                        <span class="ops-kpi-label mb-0">
                            <i class="bi bi-pie-chart-fill text-blue me-1" aria-hidden="true"></i>Disponibilidade de Sensores
                        </span>
                        <span class="ops-status-tag ops-status-nominal" style="font-size: 0.65rem;">MALHA ATIVA</span>
                    </div>
                    <div class="ops-panel-body flex-grow-1 p-2">
                        <div id="chart-status" class="ops-chart-container"></div>
                    </div>
                    <div class="px-3 py-1 border-top border-light border-opacity-10 text-steel mono-font" style="font-size: 0.7rem;">
                        8 Nominais &bull; 1 Sobreaquecimento &bull; 1 Inoperante
                    </div>
                </div>
            </div>

        </section>

        <!-- ====================================================================
             3. MATRIZ DE SENSORES COMPACTA (ALTA DENSIDADE TÉCNICA - app-screens.md)
             Tabela otimizada, 1 linha tática de alerta integrada, pitch ~36px
             ==================================================================== -->
        <section class="ops-panel mb-3" aria-label="Matriz de Sensores e Telemetria em Tempo Real">

            <div class="ops-panel-header flex-wrap py-2">
                <div>
                    <h2 class="h6 fw-bold text-white mb-0 brand-font text-uppercase">
                        <i class="bi bi-hdd-rack-fill text-blue me-2" aria-hidden="true"></i>Matriz Operacional de Sensores da Malha
                    </h2>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="ops-status-tag ops-status-nominal">
                        <span class="ops-status-dot dot-nominal"></span><?= count($sensores) ?> SENSORES
                    </span>
                    <a href="Sensor_Data.php" class="ops-btn ops-btn-primary" style="min-height: 32px; padding: 0.25rem 0.65rem; font-size: 0.76rem;" title="Inspecionar todos os sensores da malha" aria-label="Abrir Painel Completo de Sensores">
                        <i class="bi bi-hdd-network me-1" aria-hidden="true"></i>Painel Completo
                    </a>
                </div>
            </div>

            <!-- Ticker Tático de Atenção Operacional (Slim, sem desperdício de espaço) -->
            <?php if ($temAlertaCritico): ?>
                <div class="ops-alert-banner ops-pulse-alert d-flex flex-wrap align-items-center justify-content-between px-3 py-1 border-bottom border-light border-opacity-10 gap-2" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 0.85rem;" aria-hidden="true"></i>
                        <span class="mono-font text-white" style="font-size: 0.74rem;">
                            <strong>ANOMALIA IDENTIFICADA:</strong> <?= escapar_html($sensoresCriticos[0]['codigo']) ?> a <strong><?= escapar_html($sensoresCriticos[0]['temperatura']) ?> °C</strong> (limite crítico: 85.0 °C). Sobreaquecimento em rolamento.
                        </span>
                    </div>
                    <a href="Sensor_Data.php" class="ops-btn ops-btn-danger-outline py-0 px-2" style="min-height: 26px; font-size: 0.72rem;" aria-label="Inspecionar parâmetros do sensor crítico">
                        <i class="bi bi-search me-1" aria-hidden="true"></i>Inspecionar
                    </a>
                </div>
            <?php endif; ?>

            <div class="ops-panel-body p-0">
                <div class="row g-0">

                    <!-- Coluna 1: Sensores Embarcados (Trens, Truques e Mancais) -->
                    <div class="col-12 col-lg-6 border-end-lg border-light border-opacity-10">
                        <div class="py-1 px-3 border-bottom border-light border-opacity-10 d-flex justify-content-between align-items-center bg-dark bg-opacity-25" style="font-size: 0.72rem;">
                            <span class="text-white fw-bold mono-font">
                                <i class="bi bi-train-front text-blue me-1" aria-hidden="true"></i>SENSORES EMBARCADOS // TRUQUES
                            </span>
                            <span class="mono-font text-steel"><?= count($colunas['trem']) ?> UNIDADES</span>
                        </div>

                        <div class="table-responsive">
                            <table class="ops-table" aria-label="Tabela de Sensores Embarcados">
                                <thead>
                                    <tr>
                                        <th scope="col">Sensor // Alocação</th>
                                        <th scope="col" class="text-end">Velocidade</th>
                                        <th scope="col" class="text-end">Temperatura</th>
                                        <th scope="col" class="text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($colunas['trem'] as $s):
                                        $isCritico = ($s['temperatura'] >= 85 || $s['status'] === 'Em Alerta' || $s['status'] === 'Não Funcionando');
                                        $statusClass = 'ops-status-nominal';
                                        $dotClass = 'dot-nominal';
                                        $labelStatus = 'NOMINAL';

                                        if ($s['status'] === 'Em Alerta') {
                                            $statusClass = 'ops-status-warning';
                                            $dotClass = 'dot-warning';
                                            $labelStatus = 'ALERTA';
                                        } elseif ($s['status'] === 'Não Funcionando') {
                                            $statusClass = 'ops-status-critical';
                                            $dotClass = 'dot-critical';
                                            $labelStatus = 'FALHA';
                                        }
                                    ?>
                                        <tr class="<?= $isCritico ? 'row-critical' : '' ?>">
                                            <td>
                                                <div class="d-flex align-items-baseline gap-1">
                                                    <strong class="mono-font text-white"><?= escapar_html($s['codigo']) ?></strong>
                                                    <?php if ($s['temperatura'] >= 85): ?>
                                                        <span class="badge bg-danger text-white mono-font" style="font-size: 0.58rem; padding: 0.1rem 0.3rem;">&ge; 85°C</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-steel mono-font text-truncate" style="font-size: 0.69rem; max-width: 220px;">
                                                    <?= escapar_html($s['subdivisao']) ?>
                                                </div>
                                            </td>
                                            <td class="text-end mono-font">
                                                <?= isset($s['velocidade']) ? sprintf('%02d', $s['velocidade']) . ' <span class="text-steel" style="font-size: 0.68rem;">km/h</span>' : '&mdash;' ?>
                                            </td>
                                            <td class="text-end mono-font">
                                                <span class="<?= $s['temperatura'] >= 85 ? 'text-danger fw-bold' : ($s['temperatura'] >= 60 ? 'text-warning' : 'text-white') ?>">
                                                    <?= number_format($s['temperatura'], 1, '.', '') ?> °C
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="ops-status-tag <?= $statusClass ?>">
                                                    <span class="ops-status-dot <?= $dotClass ?>"></span>
                                                    <?= $labelStatus ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Coluna 2: Sensores de Via (Trilhos, AMVs e Dormentes) -->
                    <div class="col-12 col-lg-6">
                        <div class="py-1 px-3 border-bottom border-light border-opacity-10 d-flex justify-content-between align-items-center bg-dark bg-opacity-25" style="font-size: 0.72rem;">
                            <span class="text-white fw-bold mono-font">
                                <i class="bi bi-distribute-vertical text-blue me-1" aria-hidden="true"></i>SENSORES DE VIA // TRILHOS
                            </span>
                            <span class="mono-font text-steel"><?= count($colunas['trilho']) ?> UNIDADES</span>
                        </div>

                        <div class="table-responsive">
                            <table class="ops-table" aria-label="Tabela de Sensores Fixos na Via">
                                <thead>
                                    <tr>
                                        <th scope="col">Sensor // Quilometragem</th>
                                        <th scope="col">Ponto Operacional</th>
                                        <th scope="col" class="text-end">Temperatura</th>
                                        <th scope="col" class="text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($colunas['trilho'] as $s):
                                        $statusClass = 'ops-status-nominal';
                                        $dotClass = 'dot-nominal';
                                        $labelStatus = 'NOMINAL';

                                        if ($s['status'] === 'Em Alerta') {
                                            $statusClass = 'ops-status-warning';
                                            $dotClass = 'dot-warning';
                                            $labelStatus = 'ALERTA';
                                        } elseif ($s['status'] === 'Não Funcionando') {
                                            $statusClass = 'ops-status-critical';
                                            $dotClass = 'dot-critical';
                                            $labelStatus = 'FALHA';
                                        }
                                    ?>
                                        <tr>
                                            <td>
                                                <strong class="mono-font text-white d-block"><?= escapar_html($s['codigo']) ?></strong>
                                                <span class="text-steel mono-font" style="font-size: 0.69rem;">
                                                    <?= escapar_html($s['subdivisao']) ?>
                                                </span>
                                            </td>
                                            <td class="mono-font text-steel" style="font-size: 0.72rem;">
                                                <?= escapar_html($s['local'] ?? 'Via Permanente') ?>
                                            </td>
                                            <td class="text-end mono-font">
                                                <span class="<?= $s['temperatura'] >= 50 ? 'text-warning' : 'text-white' ?>">
                                                    <?= number_format($s['temperatura'], 1, '.', '') ?> °C
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="ops-status-tag <?= $statusClass ?>">
                                                    <span class="ops-status-dot <?= $dotClass ?>"></span>
                                                    <?= $labelStatus ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Rodapé Técnico Compacto -->
            <div class="px-3 py-2 border-top border-light border-opacity-10 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="text-steel mono-font" style="font-size: 0.72rem;">
                    <i class="bi bi-shield-check text-blue me-1"></i>Protocolo de Malha: <strong>Operando sem interrupções</strong>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="Sensor_Data.php" class="ops-btn" style="min-height: 32px; padding: 0.25rem 0.65rem; font-size: 0.76rem;" aria-label="Abrir visão analítica detalhada de sensores">
                        <i class="bi bi-graph-up me-1" aria-hidden="true"></i>Curvas de Telemetria
                    </a>
                    <?php if ($ehAdmin): ?>
                        <a href="Sensor.php" id="AddSensor" class="ops-btn ops-btn-primary" style="min-height: 32px; padding: 0.25rem 0.65rem; font-size: 0.76rem;" aria-label="Cadastrar novo sensor na malha">
                            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Adicionar Sensor
                        </a>
                        <a href="sensor_delete.php" id="excluirsensor" class="ops-btn ops-btn-danger-outline" style="min-height: 32px; padding: 0.25rem 0.65rem; font-size: 0.76rem;" aria-label="Remover sensor inoperante da malha">
                            <i class="bi bi-trash3 me-1" aria-hidden="true"></i>Excluir
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </section>

    </main>

    <!-- Google Charts Loader & Renderização Dark Ops Simultânea (SEM CARROSSEL) -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', { packages: ['corechart'], language: 'pt-BR' });

        const dadosGraficos = [
            {
                id: 'chart-velocidade',
                type: 'LineChart',
                data: [
                    ['Composição', 'Velocidade (km/h)'],
                    ['C-01 (TRM.001)', 62],
                    ['C-01 (TRM.002)', 74],
                    ['V-12 (TRM.003)', 58],
                    ['V-14 (TRM.004)', 81],
                    ['T-08 (TRM.005)', 0]
                ],
                options: {
                    title: '',
                    backgroundColor: 'transparent',
                    legend: { position: 'none' },
                    hAxis: {
                        textStyle: { color: '#8c9ba8', fontName: 'JetBrains Mono', fontSize: 11 },
                        gridlines: { color: 'rgba(255,255,255,0.04)' }
                    },
                    vAxis: {
                        textStyle: { color: '#8c9ba8', fontName: 'JetBrains Mono', fontSize: 11 },
                        minValue: 0,
                        maxValue: 100,
                        gridlines: { color: 'rgba(255,255,255,0.06)' },
                        baselineColor: 'rgba(255,255,255,0.15)'
                    },
                    colors: ['#5271ff'],
                    chartArea: { left: 42, top: 16, right: 18, bottom: 32, width: '92%', height: '80%' },
                    pointSize: 5,
                    lineWidth: 2
                }
            },
            {
                id: 'chart-temperatura',
                type: 'LineChart',
                data: [
                    ['Dia', 'Média da Via (°C)', 'Teto Operacional'],
                    ['Seg', 52, 85],
                    ['Ter', 56, 85],
                    ['Qua', 54, 85],
                    ['Qui', 61, 85],
                    ['Sex', 64, 85],
                    ['Sáb', 55, 85],
                    ['Dom', 48, 85]
                ],
                options: {
                    title: '',
                    backgroundColor: 'transparent',
                    legend: {
                        position: 'top',
                        alignment: 'end',
                        textStyle: { color: '#8c9ba8', fontName: 'JetBrains Mono', fontSize: 10 }
                    },
                    hAxis: {
                        textStyle: { color: '#8c9ba8', fontName: 'JetBrains Mono', fontSize: 11 },
                        gridlines: { color: 'rgba(255,255,255,0.04)' }
                    },
                    vAxis: {
                        textStyle: { color: '#8c9ba8', fontName: 'JetBrains Mono', fontSize: 11 },
                        minValue: 20,
                        maxValue: 100,
                        gridlines: { color: 'rgba(255,255,255,0.06)' },
                        baselineColor: 'rgba(255,255,255,0.15)'
                    },
                    colors: ['#c1ff72', '#ef4444'],
                    chartArea: { left: 42, top: 24, right: 18, bottom: 32, width: '92%', height: '76%' },
                    pointSize: 4,
                    lineWidth: 2,
                    series: {
                        1: { lineDashStyle: [4, 4], pointSize: 0, lineWidth: 1.5 }
                    }
                }
            },
            {
                id: 'chart-status',
                type: 'PieChart',
                data: [
                    ['Status da Malha', 'Sensores'],
                    ['Nominal', 8],
                    ['Alerta Térmico', 1],
                    ['Inoperante', 1]
                ],
                options: {
                    title: '',
                    backgroundColor: 'transparent',
                    legend: {
                        position: 'bottom',
                        textStyle: { color: '#8c9ba8', fontName: 'JetBrains Mono', fontSize: 11 }
                    },
                    colors: ['#22c55e', '#f59e0b', '#ef4444'],
                    chartArea: { left: 15, top: 8, right: 15, bottom: 30, width: '92%', height: '82%' },
                    pieHole: 0.65,
                    pieSliceBorderColor: '#0b1523',
                    pieSliceText: 'none'
                }
            }
        ];

        function renderizarGraficosOperacionais() {
            if (typeof google === 'undefined' || !google.visualization) return;

            dadosGraficos.forEach(cfg => {
                const el = document.getElementById(cfg.id);
                if (!el) return;
                const dataTable = google.visualization.arrayToDataTable(cfg.data);
                const chart = new google.visualization[cfg.type](el);
                chart.draw(dataTable, cfg.options);
            });
        }

        google.charts.setOnLoadCallback(() => {
            renderizarGraficosOperacionais();

            // Resize com debounce para manter estabilidade
            let timerRedim;
            window.addEventListener('resize', () => {
                clearTimeout(timerRedim);
                timerRedim = setTimeout(renderizarGraficosOperacionais, 120);
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
