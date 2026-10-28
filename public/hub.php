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
$nivelAcessoLabel = $ehAdmin ? 'Administrador' : 'Funcionário';

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
                45 => ['🌫️', 'Nevoeiro'],
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

// Sensores simulados e dados operacionais nominais
$sensores = [
    ['id' => 1, 'tipo' => 'trem', 'codigo' => 'S.TRM.001', 'velocidade' => 62, 'temperatura' => 52, 'status' => 'Funcionando'],
    ['id' => 2, 'tipo' => 'trem', 'codigo' => 'S.TRM.002', 'velocidade' => 74, 'temperatura' => 45, 'status' => 'Funcionando'],
    ['id' => 3, 'tipo' => 'trem', 'codigo' => 'S.TRM.003', 'velocidade' => 58, 'temperatura' => 49, 'status' => 'Funcionando'],
    ['id' => 4, 'tipo' => 'trem', 'codigo' => 'S.TRM.004', 'velocidade' => 81, 'temperatura' => 88, 'status' => 'Em Alerta'],
    ['id' => 5, 'tipo' => 'trem', 'codigo' => 'S.TRM.005', 'velocidade' => 0,  'temperatura' => 24, 'status' => 'Não Funcionando'],
    ['id' => 6, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.001', 'temperatura' => 21, 'status' => 'Funcionando'],
    ['id' => 7, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.002', 'temperatura' => 19, 'status' => 'Funcionando'],
    ['id' => 8, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.003', 'temperatura' => 23, 'status' => 'Funcionando'],
    ['id' => 9, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.004', 'temperatura' => 17, 'status' => 'Funcionando'],
    ['id' => 10, 'tipo' => 'trilho', 'codigo' => 'S.TRLH.005', 'temperatura' => 29, 'status' => 'Funcionando'],
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

// Consultas seguras ao banco para KPIs
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

// Data formatada para o cabeçalho
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
    <meta name="description" content="TrackFlow — Centro de Controle Operacional Ferroviário (CCO).">
    <meta name="theme-color" content="#081220">
    <link rel="shortcut icon" href="../assets/logo/icone.ico" type="image/x-icon">
    <title>TrackFlow // CCO — Dashboard Operacional</title>

    <!-- Fontes Técnicas Oficiais -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=League+Spartan:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.8 CSS & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Folha de Estilos Oficial -->
    <link rel="stylesheet" href="../Styles/style.css">

    <!-- Estilos Específicos do Dashboard CCO (Cores Fiéis ao Projeto TrackFlow: Azul Real & Volt) -->
    <style>
        :root {
            --cco-bg-primary: #081220;
            --cco-bg-secondary: #0d1b2e;
            --cco-bg-card: #0d1b2e;
            --cco-bg-elevated: #112238;
            --cco-blue: #5271ff;
            --cco-blue-pastel: #67a4ff;
            --cco-blue-soft: rgba(82, 113, 255, 0.12);
            --cco-blue-border: rgba(82, 113, 255, 0.35);
            --cco-volt: #c1ff72;
            --cco-border: rgba(255, 255, 255, 0.1);
            --cco-text-main: #f4f7fa;
            --cco-text-muted: #b8c7d9;
            --cco-danger: #ef4444;
            --cco-warning: #f59e0b;
        }

        body.cco-dashboard-body {
            background-color: var(--cco-bg-primary);
            color: var(--cco-text-main);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
        }

        .brand-font {
            font-family: 'League Spartan', system-ui, sans-serif !important;
            letter-spacing: -0.02em;
        }

        .mono-font {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, monospace !important;
            letter-spacing: 0.02em;
        }

        .text-blue {
            color: var(--cco-blue-pastel) !important;
        }

        .text-steel {
            color: var(--cco-text-muted) !important;
        }

        /* Painéis e Cards Técnicos Limpos (Cores sólidas e fieis à marca TrackFlow) */
        .cco-panel {
            background-color: var(--cco-bg-card);
            border: 1px solid var(--cco-border);
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
        }

        .cco-welcome-card {
            border-left: 4px solid var(--cco-blue);
        }

        .cco-weather-card {
            border: 1px solid var(--cco-border);
            background-color: var(--cco-bg-card);
        }

        .weather-mini-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            background-color: var(--cco-blue-soft);
            border: 1px solid var(--cco-blue-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
        }

        .weather-compact-stat {
            background-color: #081220;
            border: 1px solid var(--cco-border);
            min-width: 90px;
        }

        .cco-badge-role {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.85rem;
            border-radius: 50rem;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .cco-badge-admin {
            background-color: rgba(82, 113, 255, 0.15);
            color: var(--cco-blue-pastel);
            border: 1px solid var(--cco-blue-border);
        }

        .cco-badge-func {
            background-color: rgba(193, 255, 114, 0.14);
            color: var(--cco-volt);
            border: 1px solid rgba(193, 255, 114, 0.35);
        }

        .btn-blue {
            background-color: var(--cco-blue);
            color: #ffffff;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-blue:hover,
        .btn-blue:focus-visible {
            background-color: #415de6;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(82, 113, 255, 0.35);
        }

        .btn-outline-blue {
            background-color: transparent;
            color: var(--cco-blue-pastel);
            border: 1px solid var(--cco-blue-border);
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-outline-blue:hover,
        .btn-outline-blue:focus-visible {
            background-color: var(--cco-blue-soft);
            border-color: var(--cco-blue-pastel);
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        /* Banner de Alerta Operacional Sólido (Livre de gradientes pesados) */
        .railway-weather-alert {
            background-color: #171018;
            border: 1px solid rgba(239, 68, 68, 0.45);
            border-left: 4px solid var(--cco-danger);
            color: #fecaca;
            border-radius: 0.85rem;
            padding: 0.95rem 1.25rem;
        }

        /* Cards de KPIs com Fundo Sólido e Bordas Limpas */
        .kpi-card {
            background-color: var(--cco-bg-card);
            border: 1px solid var(--cco-border);
            border-radius: 1rem;
            padding: 1.2rem 1.25rem;
            height: 100%;
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .kpi-card:hover {
            transform: translateY(-2px);
            border-color: var(--cco-blue-border);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.4);
        }

        .kpi-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .kpi-icon-blue {
            background-color: rgba(82, 113, 255, 0.14);
            color: var(--cco-blue-pastel);
            border: 1px solid var(--cco-blue-border);
        }

        .kpi-icon-green {
            background-color: rgba(34, 197, 94, 0.12);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .kpi-icon-amber {
            background-color: rgba(245, 158, 11, 0.14);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.35);
        }

        .kpi-icon-volt {
            background-color: rgba(193, 255, 114, 0.12);
            color: var(--cco-volt);
            border: 1px solid rgba(193, 255, 114, 0.3);
        }

        .kpi-value {
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1.1;
            color: #ffffff;
        }

        /* Caixa de Gráficos de Telemetria */
        .cco-chart-box {
            background-color: #081220;
            border: 1px solid var(--cco-border);
            border-radius: 0.9rem;
            padding: 1.25rem;
            min-height: 380px;
        }

        .hub-chart {
            width: 100%;
            min-height: 330px;
        }

        /* Botão de colapso de sensores */
        .sensor-collapse-btn {
            width: 100%;
            background-color: #081220;
            border: 1px solid var(--cco-border);
            border-radius: 0.75rem;
            padding: 0.85rem 1.1rem;
            color: #ffffff;
            text-align: left;
            transition: all 0.2s ease;
        }

        .sensor-collapse-btn:hover,
        .sensor-collapse-btn[aria-expanded="true"] {
            border-color: var(--cco-blue-border);
            background-color: var(--cco-blue-soft);
        }

        .sensor-collapse-body {
            background-color: #081220;
            border: 1px solid var(--cco-border);
            border-top: none;
            border-radius: 0 0 0.75rem 0.75rem;
            padding: 0.85rem 1.1rem;
        }
    </style>
</head>

<body class="cco-dashboard-body">

    <!-- CABEÇALHO GLOBAL & NAVBAR PADRONIZADA -->
    <header>
        <?php include("components/navbar.php"); ?>
    </header>

    <!-- CONTEÚDO PRINCIPAL DO CCO -->
    <main class="container-fluid px-3 px-lg-4 py-4" style="max-width: 1600px; margin: 0 auto;">

        <!-- ====================================================================
             1. CABEÇALHO: OPERADOR & WIDGET DE CLIMA LADO A LADO
             ==================================================================== -->
        <section class="row g-3 align-items-stretch mb-4" aria-label="Identificação do Operador e Monitoramento Climático">
            <!-- Coluna 1: Card de Boas-Vindas e Acesso do Usuário -->
            <div class="col-12 col-lg-6">
                <div class="cco-panel cco-welcome-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-blue border border-primary border-opacity-25 mono-font fw-semibold px-2 py-1">
                                <i class="bi bi-broadcast me-1" aria-hidden="true"></i>CCO // TRACKFLOW
                            </span>
                            <span class="cco-badge-role <?= $ehAdmin ? 'cco-badge-admin' : 'cco-badge-func' ?>">
                                <i class="bi <?= $ehAdmin ? 'bi-shield-lock-fill' : 'bi-person-badge-fill' ?>" aria-hidden="true"></i>
                                <?= escapar_html($nivelAcessoLabel) ?>
                            </span>
                        </div>
                        <h1 class="h3 fw-bold text-white mb-1 brand-font">
                            <?= escapar_html($nomeUsuario) ?>
                        </h1>
                        <p class="text-steel small mb-0">
                            <i class="bi bi-person-check text-blue me-1"></i>Sessão ativa no Centro de Controle Operacional Ferroviário
                        </p>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between pt-3 mt-3 border-top border-light border-opacity-10 gap-2">
                        <span class="text-steel small mono-font">
                            <i class="bi bi-calendar3 me-1 text-blue" aria-hidden="true"></i><?= escapar_html($dataAtualFormatada) ?>
                        </span>
                        <a href="Usuario_Info.php" class="btn btn-outline-blue btn-sm rounded-pill px-3 py-1">
                            <i class="bi bi-person-gear me-1"></i>Perfil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Coluna 2: Bloco de Informações Meteorológicas (Open-Meteo) -->
            <div class="col-12 col-lg-6">
                <div class="cco-panel cco-weather-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between"
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
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span id="clima-atualizacao" class="text-steel mono-font" style="font-size: 0.78rem;">
                                Atualizado às <?= escapar_html($horaAtualizacaoClima) ?>
                            </span>
                            <button type="button" id="btn-atualizar-clima" class="btn btn-outline-blue btn-sm rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 32px; height: 32px;" title="Atualizar dados climáticos" aria-label="Atualizar dados climáticos">
                                <span id="clima-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <i id="clima-btn-icon" class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Dados Estritos: Temperatura, Ícone, Sensação e Umidade -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 my-auto py-2">
                        <div class="d-flex align-items-center gap-3">
                            <div id="clima-icone" class="weather-mini-icon" aria-hidden="true">
                                <?= $iconeClima ?>
                            </div>
                            <div>
                                <div class="d-flex align-items-baseline gap-2">
                                    <span id="clima-temperatura" class="fs-2 fw-bold text-white mono-font"><?= number_format($temperatura, 1, '.', '') ?> °C</span>
                                </div>
                                <span id="clima-condicao" class="small text-blue fw-semibold d-block">
                                    <?= escapar_html($descricaoClima) ?>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <div class="weather-compact-stat px-3 py-2 rounded-3 text-center">
                                <small class="text-steel d-block mono-font" style="font-size: 0.72rem;">SENSAÇÃO</small>
                                <strong id="clima-sensacao" class="text-white mono-font fs-6"><?= number_format($sensacaoTermica, 1, '.', '') ?> °C</strong>
                            </div>
                            <div class="weather-compact-stat px-3 py-2 rounded-3 text-center">
                                <small class="text-steel d-block mono-font" style="font-size: 0.72rem;">UMIDADE</small>
                                <strong id="clima-umidade" class="text-white mono-font fs-6"><?= (int) $umidade ?>%</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Feedback discreto de erro/429 se necessário -->
                    <div id="clima-erro-box" class="alert alert-warning py-1 px-2 mb-0 mt-2 small d-none" role="status">
                        <i class="bi bi-exclamation-circle me-1"></i><span id="clima-erro-mensagem"></span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ====================================================================
             2. PAINEL DE GRÁFICOS DE TELEMETRIA (ACIMA DA DIVISÃO DE SENSOR E KPIS)
             ==================================================================== -->
        <section class="mb-4" aria-label="Telemetria e Gráficos da Malha">
            <div class="cco-panel p-3 p-md-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                    <div>
                        <h2 class="h4 fw-bold text-white mb-0 brand-font text-uppercase">
                            <i class="bi bi-graph-up-arrow text-blue me-2" aria-hidden="true"></i>Telemetria e Curvas de Desempenho
                        </h2>
                        <small class="text-steel">Velocidade média por sensor, monitoramento térmico contínuo e status da malha</small>
                    </div>

                    <!-- Controles e Pílulas de Navegação dos Gráficos -->
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="btn-group" role="group" aria-label="Alternar gráfico de telemetria">
                            <button type="button" class="btn btn-outline-blue btn-sm px-3 mono-font active"
                                data-bs-target="#hubCarousel" data-bs-slide-to="0">
                                01 // Velocidade
                            </button>
                            <button type="button" class="btn btn-outline-blue btn-sm px-3 mono-font"
                                data-bs-target="#hubCarousel" data-bs-slide-to="1">
                                02 // Temperatura
                            </button>
                            <button type="button" class="btn btn-outline-blue btn-sm px-3 mono-font"
                                data-bs-target="#hubCarousel" data-bs-slide-to="2">
                                03 // Status
                            </button>
                        </div>

                        <div class="d-flex align-items-center gap-1 ms-2">
                            <button class="btn btn-outline-blue btn-sm rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 34px; height: 34px;" type="button" data-bs-target="#hubCarousel"
                                data-bs-slide="prev" aria-label="Gráfico anterior">
                                <i class="bi bi-chevron-left" aria-hidden="true"></i>
                            </button>
                            <button class="btn btn-outline-blue btn-sm rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 34px; height: 34px;" type="button" data-bs-target="#hubCarousel"
                                data-bs-slide="next" aria-label="Próximo gráfico">
                                <i class="bi bi-chevron-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Carrossel de Gráficos -->
                <div id="hubCarousel" class="carousel slide" data-bs-ride="false">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="cco-chart-box">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-white small mono-font text-uppercase">
                                        <i class="bi bi-speedometer2 text-blue me-1" aria-hidden="true"></i>Velocidade Média por Sensor (km/h)
                                    </span>
                                    <span class="badge bg-primary bg-opacity-10 text-blue mono-font">SÉRIE SEMANAL</span>
                                </div>
                                <div id="chart-velocidade" class="hub-chart"></div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="cco-chart-box">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-white small mono-font text-uppercase">
                                        <i class="bi bi-thermometer-half text-blue me-1" aria-hidden="true"></i>Temperatura Média dos Sensores (°C)
                                    </span>
                                    <span class="badge bg-primary bg-opacity-10 text-blue mono-font">MONITORAMENTO TÉRMICO</span>
                                </div>
                                <div id="chart-temperatura" class="hub-chart"></div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="cco-chart-box">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-white small mono-font text-uppercase">
                                        <i class="bi bi-pie-chart-fill text-blue me-1" aria-hidden="true"></i>Disponibilidade Operacional dos Sensores (%)
                                    </span>
                                    <span class="badge bg-success bg-opacity-10 text-success mono-font">DIAGNÓSTICO</span>
                                </div>
                                <div id="chart-status" class="hub-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ====================================================================
             3. DIVISÃO LADO A LADO:
                COLUNA ESQUERDA: "ESSES CARAS DA IMAGEM" (Alerta + Os 4 KPIs Operacionais)
                COLUNA DIREITA:  LISTA DE SENSORES DA MALHA (Sensores de Trem e Trilho)
             ==================================================================== -->
        <section class="row g-4 mb-4" aria-label="Operação, Métricas e Sensores">

            <!-- COLUNA ESQUERDA: OS CARAS DA IMAGEM (ALERTA OPERACIONAL + 4 CARDS DE KPIS) -->
            <div class="col-12 col-lg-6">
                <div class="d-flex flex-column gap-3 h-100">

                    <!-- Banner de Alerta Operacional (sem gradientes feios) -->
                    <?php if ($temAlertaCritico): ?>
                        <div id="alerta-operacional-sensores" class="railway-weather-alert" role="alert">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="kpi-icon-box bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50">
                                        <i class="bi bi-exclamation-octagon-fill fs-4" aria-hidden="true"></i>
                                    </div>
                                    <div>
                                        <strong class="d-block text-white text-uppercase small mono-font">Atenção Operacional: Sensores Fora da Faixa Segura</strong>
                                        <span class="small text-steel">
                                            Há <?= count($sensoresCriticos) ?> sensor(es) demandando inspeção imediata na via (ex: <?= escapar_html($sensoresCriticos[0]['codigo']) ?> a <?= escapar_html($sensoresCriticos[0]['temperatura']) ?> °C). Redobrar atenção aos sensores de aderência e frenagem!
                                        </span>
                                    </div>
                                </div>
                                <a href="Sensor_Data.php" class="btn btn-outline-danger btn-sm rounded-pill px-3 text-nowrap">
                                    Inspecionar Sensores
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Alerta Dinâmico para Eventos Meteorológicos Severos (Oculto por padrão) -->
                    <div id="clima-alerta-extremo" class="railway-weather-alert d-none" role="alert">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-cloud-lightning-rain-fill fs-3 text-danger flex-shrink-0" aria-hidden="true"></i>
                            <div>
                                <strong class="d-block text-white text-uppercase small mono-font">Alerta Climático Crítico na Malha</strong>
                                <span class="small text-steel">
                                    Atenção Operacional: Tempestade severa identificada na região do pátio! Redobrar atenção aos sensores de aderência e frenagem.
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Grid 2x2 com os 4 Cards de KPIs da Imagem -->
                    <div class="row g-3 flex-grow-1">
                        <!-- 1. Trens em Circulação -->
                        <div class="col-12 col-sm-6">
                            <div class="kpi-card d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="text-steel small mono-font text-uppercase d-block">Trens em Circulação</span>
                                            <div class="kpi-value mono-font mt-1"><?= escapar_html($trensEmCirculacao) ?></div>
                                        </div>
                                        <div class="kpi-icon-box kpi-icon-blue" aria-hidden="true">
                                            <i class="bi bi-train-front-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light border-opacity-10 mt-auto">
                                    <span class="badge bg-primary bg-opacity-10 text-blue mono-font">MALHA ATIVA</span>
                                    <small class="text-steel">Monitoramento contínuo</small>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Sensores Ativos -->
                        <div class="col-12 col-sm-6">
                            <div class="kpi-card d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="text-steel small mono-font text-uppercase d-block">Sensores Ativos</span>
                                            <div class="kpi-value mono-font mt-1"><?= escapar_html($sensoresAtivosCount) ?></div>
                                        </div>
                                        <div class="kpi-icon-box kpi-icon-green" aria-hidden="true">
                                            <i class="bi bi-cpu-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light border-opacity-10 mt-auto">
                                    <span class="badge bg-success bg-opacity-10 text-success mono-font">ONLINE 24/7</span>
                                    <small class="text-steel">Trens e trilhos</small>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Alertas Pendentes -->
                        <div class="col-12 col-sm-6">
                            <div class="kpi-card d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="text-steel small mono-font text-uppercase d-block">Alertas Pendentes</span>
                                            <div class="kpi-value mono-font mt-1"><?= escapar_html($alertasPendentesCount) ?></div>
                                        </div>
                                        <div class="kpi-icon-box kpi-icon-amber" aria-hidden="true">
                                            <i class="bi bi-exclamation-diamond-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light border-opacity-10 mt-auto">
                                    <span class="badge bg-warning bg-opacity-10 text-warning mono-font">DIAGNÓSTICO</span>
                                    <small class="text-steel"><?= $temAlertaCritico ? 'Requer atenção imediata' : 'Tudo regular' ?></small>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Relatórios Recentes -->
                        <div class="col-12 col-sm-6">
                            <div class="kpi-card d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="text-steel small mono-font text-uppercase d-block">Relatórios Recentes</span>
                                            <div class="kpi-value mono-font mt-1"><?= escapar_html($relatoriosRecentes) ?></div>
                                        </div>
                                        <div class="kpi-icon-box kpi-icon-volt" aria-hidden="true">
                                            <i class="bi bi-journal-text"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light border-opacity-10 mt-auto">
                                    <span class="badge bg-light bg-opacity-10 text-white mono-font">TELEMETRIA</span>
                                    <a href="Relatories.php" class="small text-blue text-decoration-none fw-semibold">Consultar &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Barra de Atalho Operacional Rápido -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 p-3 cco-panel">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-blue"></i>
                            <span class="small text-steel">Integridade da Malha: <strong>Operando</strong></span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="trens_trilhos.php" class="btn btn-outline-blue btn-sm rounded-pill px-3">
                                <i class="bi bi-train-front me-1"></i>Trens e Trilhos
                            </a>
                            <a href="Relatories.php" class="btn btn-outline-light btn-sm rounded-pill px-3">
                                <i class="bi bi-journal-text me-1"></i>Relatórios
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- COLUNA DIREITA: LISTAGEM DE SENSORES DA MALHA -->
            <div class="col-12 col-lg-6">
                <div class="cco-panel p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <div>
                                <h2 class="h4 fw-bold text-white mb-0 brand-font text-uppercase">
                                    <i class="bi bi-hdd-rack-fill text-blue me-2" aria-hidden="true"></i>Sensores da Malha
                                </h2>
                                <small class="text-steel">Clique em um sensor para inspecionar os parâmetros ao vivo</small>
                            </div>
                            <span class="badge bg-secondary bg-opacity-25 text-steel mono-font">
                                <?= count($sensores) ?> UNIDADES
                            </span>
                        </div>

                        <!-- Sensores Divididos em Trem e Trilho -->
                        <div class="row g-3">
                            <?php foreach ($colunas as $tipoColuna => $lista): ?>
                                <div class="col-12 col-sm-6">
                                    <h3 class="h6 text-blue mono-font text-uppercase mb-2">
                                        <i class="bi <?= $tipoColuna === 'trem' ? 'bi-train-front' : 'bi-distribute-vertical' ?> me-1" aria-hidden="true"></i>
                                        <?= $tipoColuna === 'trem' ? 'Sensores de Trem' : 'Sensores de Trilho' ?>
                                    </h3>

                                    <div class="d-flex flex-column gap-2">
                                        <?php foreach ($lista as $sensor):
                                            $idSensor = (int) $sensor['id'];
                                            $statusSensor = $sensor['status'];
                                            $badgeClass = 'bg-success bg-opacity-10 text-success border-success';
                                            if ($statusSensor === 'Em Alerta') {
                                                $badgeClass = 'bg-warning bg-opacity-10 text-warning border-warning';
                                            } elseif ($statusSensor !== 'Funcionando') {
                                                $badgeClass = 'bg-danger bg-opacity-10 text-danger border-danger';
                                            }

                                            $linhas = [];
                                            if (isset($sensor['velocidade'])) {
                                                $linhas['Velocidade média'] = $sensor['velocidade'] . ' km/h';
                                            }
                                            $linhas['Temperatura média'] = $sensor['temperatura'] . ' °C';
                                            $linhas['Status operacional'] = $sensor['status'];
                                        ?>
                                            <div>
                                                <button class="sensor-collapse-btn d-flex justify-content-between align-items-center"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#lista-sensor<?= $idSensor ?>"
                                                    aria-expanded="false" aria-controls="lista-sensor<?= $idSensor ?>">
                                                    <span class="mono-font fw-bold small">
                                                        <?= escapar_html($sensor['codigo']) ?>
                                                    </span>
                                                    <span class="d-flex align-items-center gap-2">
                                                        <span class="badge border border-opacity-25 <?= $badgeClass ?> mono-font" style="font-size: 0.68rem;">
                                                            <?= escapar_html($statusSensor) ?>
                                                        </span>
                                                        <i class="bi bi-chevron-down text-steel small" aria-hidden="true"></i>
                                                    </span>
                                                </button>

                                                <div class="collapse" id="lista-sensor<?= $idSensor ?>">
                                                    <div class="sensor-collapse-body small">
                                                        <?php foreach ($linhas as $rotulo => $valor): ?>
                                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light border-opacity-10">
                                                                <span class="text-steel"><?= escapar_html($rotulo) ?>:</span>
                                                                <strong class="text-white mono-font"><?= escapar_html($valor) ?></strong>
                                                            </div>
                                                        <?php endforeach; ?>
                                                        <div class="mt-2 text-end">
                                                            <a href="Sensor_Data.php" class="text-blue text-decoration-none fw-semibold small">
                                                                Ver telemetria detalhada &rarr;
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Botões de Ação para Admin -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-4 pt-3 border-top border-light border-opacity-10">
                        <a href="Sensor_Data.php" class="btn btn-outline-blue btn-sm rounded-pill px-3 py-2">
                            <i class="bi bi-hdd-network me-1" aria-hidden="true"></i>Painel Completo de Sensores
                        </a>
                        <?php if ($ehAdmin): ?>
                            <div class="d-flex gap-2">
                                <a href="Sensor.php" id="AddSensor" class="btn btn-blue btn-sm rounded-pill px-3 py-2">
                                    <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Adicionar Sensor
                                </a>
                                <a href="sensor_delete.php" id="excluirsensor" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2">
                                    <i class="bi bi-trash3 me-1" aria-hidden="true"></i>Excluir
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </section>

    </main>

    <!-- RODAPÉ TÉCNICO CCO -->
    <footer class="container-fluid px-3 px-lg-4 py-3 border-top border-light border-opacity-10 text-center text-steel small mt-5">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2" style="max-width: 1600px; margin: 0 auto;">
            <span class="mono-font">TRACKFLOW CCO &mdash; CENTRO DE CONTROLE OPERACIONAL FERROVIÁRIO</span>
            <span class="mono-font">Monitoramento Meteorológico em Tempo Real via Open-Meteo</span>
        </div>
    </footer>

    <!-- Google Charts Loader & Renderização dos Gráficos em Dark Mode -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', { packages: ['corechart'], language: 'pt-BR' });

        const dadosGraficos = [
            {
                id: 'chart-velocidade',
                type: 'LineChart',
                data: [
                    ['Sensor', 'Velocidade média (km/h)'],
                    ['S.TRM.001', 62],
                    ['S.TRM.002', 74],
                    ['S.TRM.003', 58],
                    ['S.TRM.004', 81],
                    ['S.TRM.005', 0]
                ],
                options: {
                    title: 'Velocidade média semanal por composição (km/h)',
                    backgroundColor: 'transparent',
                    legend: { position: 'bottom', textStyle: { color: '#b8c7d9' } },
                    hAxis: { textStyle: { color: '#b8c7d9' }, gridlines: { color: 'rgba(255,255,255,0.06)' } },
                    vAxis: { textStyle: { color: '#b8c7d9' }, minValue: 0, gridlines: { color: 'rgba(255,255,255,0.08)' } },
                    titleTextStyle: { color: '#f4f7fa', fontSize: 15, bold: true },
                    colors: ['#5271ff'],
                    chartArea: { width: '88%', height: '70%' },
                    pointSize: 6,
                    lineWidth: 3
                }
            },
            {
                id: 'chart-temperatura',
                type: 'LineChart',
                data: [
                    ['Dia', 'Temperatura Média (°C)'],
                    ['Seg', 52],
                    ['Ter', 56],
                    ['Qua', 54],
                    ['Qui', 61],
                    ['Sex', 64],
                    ['Sáb', 55],
                    ['Dom', 48]
                ],
                options: {
                    title: 'Temperatura média semanal dos sensores da via (°C)',
                    backgroundColor: 'transparent',
                    legend: { position: 'bottom', textStyle: { color: '#b8c7d9' } },
                    hAxis: { textStyle: { color: '#b8c7d9' }, gridlines: { color: 'rgba(255,255,255,0.06)' } },
                    vAxis: { textStyle: { color: '#b8c7d9' }, minValue: 0, gridlines: { color: 'rgba(255,255,255,0.08)' } },
                    titleTextStyle: { color: '#f4f7fa', fontSize: 15, bold: true },
                    colors: ['#c1ff72'],
                    chartArea: { width: '88%', height: '70%' },
                    pointSize: 6,
                    lineWidth: 3
                }
            },
            {
                id: 'chart-status',
                type: 'PieChart',
                data: [
                    ['Status', 'Quantidade'],
                    ['Operação Normal', 8],
                    ['Em Alerta', 1],
                    ['Falha / Inativo', 1]
                ],
                options: {
                    title: 'Disponibilidade operacional dos sensores instalados',
                    backgroundColor: 'transparent',
                    legend: { position: 'right', textStyle: { color: '#b8c7d9' } },
                    titleTextStyle: { color: '#f4f7fa', fontSize: 15, bold: true },
                    colors: ['#5271ff', '#f59e0b', '#ef4444'],
                    chartArea: { width: '88%', height: '80%' },
                    pieHole: 0.45
                }
            }
        ];

        function desenharGrafico(config) {
            const el = document.getElementById(config.id);
            if (!el || typeof google === 'undefined' || !google.visualization) return;

            const tabela = google.visualization.arrayToDataTable(config.data);
            const chart = new google.visualization[config.type](el);
            chart.draw(tabela, config.options);
        }

        google.charts.setOnLoadCallback(() => {
            dadosGraficos.forEach(desenharGrafico);

            window.addEventListener('resize', () => {
                dadosGraficos.forEach(desenharGrafico);
            });

            const carousel = document.getElementById('hubCarousel');
            if (carousel) {
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
            }
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
