<?php
$anoAtual = date('Y');
$cssVersion = @filemtime(__DIR__ . '/Styles/style.css') ?: '2026';
$jsVersion = @filemtime(__DIR__ . '/scripts/scriptIndex.js') ?: '2026';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TrackFlow — Plataforma visual para monitoramento de sensores ferroviários, gestão de trens, linhas e relatórios operacionais.">
    <meta name="theme-color" content="#081220">
    <title>TrackFlow — Monitoramento Ferroviário</title>
    <link rel="shortcut icon" href="Assets/logo/icone.ico" type="image/x-icon">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=League+Spartan:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Styles/style.css?v=<?= $cssVersion ?>">
</head>

<body class="ferrorama-landing">
    <!-- Cena 3D WebGL de Viadutos e Trens Passando no Fundo -->
    <canvas id="webgl-trains-canvas" class="webgl-trains-canvas" aria-hidden="true"></canvas>

    <!-- ========================================================================
         1. BARRA SUPERIOR
         ======================================================================== -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-canva" aria-label="Navegação Principal">
            <div class="container-fluid px-3 px-lg-5">
                <a class="navbar-brand d-flex align-items-center gap-2 brand-font" href="#inicio">
                    <img src="Assets/logo/logo-track-flow.png" alt="Logo TrackFlow" width="36" height="36" fetchpriority="high" class="navbar-brand-logo">
                    <span class="fw-bold fs-4 text-white tracking-tight">TrackFlow</span>
                </a>

                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarTrackflow" aria-controls="navbarTrackflow" aria-expanded="false"
                    aria-label="Abrir menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarTrackflow">
                    <ul class="navbar-nav me-auto ms-lg-4 mb-3 mb-lg-0 gap-lg-1 pt-2 pt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link nav-link-canva active" href="#inicio">Início</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-canva" href="#recursos">Recursos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-canva" href="#sensores">Sensores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-canva" href="#beneficios">Vantagens</a>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center gap-2">
                        <a href="public/Login.php" class="btn btn-nav-login rounded-pill px-4 py-2">
                            Entrar
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <!-- ====================================================================
             2. HERO SECTION CENTRALIZADA + VITRINE INTERATIVA
             ==================================================================== -->
        <section id="inicio" class="hero-canva" aria-labelledby="hero-heading">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-12 col-lg-9 col-xl-8">
                        <h1 id="hero-heading" class="hero-canva-title text-white mb-3">
                            No ritmo que move estados.
                        </h1>
                        <p class="hero-canva-subtitle mb-4 mx-auto">
                            Acompanhe sensores de trens e trilhos, cadastre composições, visualize gráficos por linha e organize sua equipe em um único lugar.
                        </p>
                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3 mb-5">
                            <a href="public/Login.php" class="btn btn-canva-primary rounded-pill px-4 py-3">
                                Entrar no TrackFlow
                            </a>
                            <a href="#recursos" class="btn btn-canva-secondary rounded-pill px-4 py-3">
                                Ver como funciona
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pílulas de Categoria Estilo Canva -->
                <div class="d-flex flex-wrap justify-content-center gap-2 mb-4" role="tablist" aria-label="Explorar áreas do sistema">
                    <button type="button" class="canva-pill-tab active" data-showcase-tab="sensores" role="tab" aria-selected="true">
                        Sensores em Tempo Real
                    </button>
                    <button type="button" class="canva-pill-tab" data-showcase-tab="frota" role="tab" aria-selected="false">
                        Trens e Linhas
                    </button>
                    <button type="button" class="canva-pill-tab" data-showcase-tab="relatorios" role="tab" aria-selected="false">
                        Relatórios e Gráficos
                    </button>
                    <button type="button" class="canva-pill-tab" data-showcase-tab="equipe" role="tab" aria-selected="false">
                        Equipe e Acessos
                    </button>
                </div>

                <!-- Vitrine Visual 3D da Categoria Selecionada -->
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-10">
                        <div id="canva-showcase-card" class="canva-showcase-stage" aria-live="polite">
                            <div class="row align-items-center g-4">
                                <div class="col-12 col-lg-5">
                                    <span id="showcase-kicker" class="canva-kicker mb-2 d-inline-block">Painel de Sensores</span>
                                    <h2 id="showcase-title" class="h3 fw-bold text-white mb-3">
                                        Trens e trilhos monitorados lado a lado
                                    </h2>
                                    <p id="showcase-desc" class="text-steel mb-4">
                                        Visualize rapidamente o código do sensor, a velocidade da composição, a temperatura em graus Celsius e o status de funcionamento sem telas complicadas.
                                    </p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span id="showcase-tag-1" class="canva-mini-tag">Leitura térmica contínua</span>
                                        <span id="showcase-tag-2" class="canva-mini-tag">Status imediato</span>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-7">
                                    <!-- Simulador / Prévia Visual Limpa dos Sensores do Sistema -->
                                    <div class="canva-ui-preview">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom border-light border-opacity-10">
                                            <span class="fw-bold text-white small">Simular estado da linha:</span>
                                            <div class="d-flex flex-wrap gap-2" role="group" aria-label="Cenários de leitura">
                                                <button type="button" class="scenario-btn active" data-scenario="normal" aria-pressed="true">Normal</button>
                                                <button type="button" class="scenario-btn" data-scenario="hotbox" aria-pressed="false">Aquecimento</button>
                                                <button type="button" class="scenario-btn" data-scenario="vibration" aria-pressed="false">Alerta na Via</button>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12 col-sm-6">
                                                <div class="canva-sensor-tile">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="small text-steel">Sensor de Trem (S.TRM.001)</span>
                                                        <span id="badge-temp-status" class="canva-status-dot status-ok">Funcionando</span>
                                                    </div>
                                                    <div id="metric-temp" class="fs-3 fw-bold text-white brand-font">54.2 °C</div>
                                                    <div class="progress telemetry-progress mt-2" role="progressbar" aria-label="Temperatura" aria-valuenow="54" aria-valuemin="0" aria-valuemax="100">
                                                        <div id="bar-temp" class="progress-bar bg-info telemetry-bar-54"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="canva-sensor-tile">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="small text-steel">Sensor de Trilho (S.TRLH.001)</span>
                                                        <span id="badge-vib-status" class="canva-status-dot status-ok">Estável</span>
                                                    </div>
                                                    <div id="metric-vib" class="fs-3 fw-bold text-white brand-font">1.8 mm/s</div>
                                                    <div class="progress telemetry-progress mt-2" role="progressbar" aria-label="Vibração" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100">
                                                        <div id="bar-vib" class="progress-bar bg-success telemetry-bar-32"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="canva-sensor-tile d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <div>
                                                        <span id="metric-train-name" class="d-block small text-steel">Velocidade Média — Linha 1</span>
                                                        <span id="metric-speed" class="fs-4 fw-bold text-white brand-font">68.5 km/h</span>
                                                    </div>
                                                    <span id="badge-cco-status" class="canva-status-pill status-ok">Operação Normal</span>
                                                </div>
                                            </div>
                                        </div>

                                        <p id="telemetry-log-text" class="small text-steel mb-0 mt-3">
                                            Todos os sensores de trens e trilhos operando normalmente.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ====================================================================
             3. BENTO GRID
             ==================================================================== -->
        <section id="recursos" class="section-padding" aria-labelledby="recursos-heading">
            <div class="container">
                <div class="row justify-content-center text-center mb-5 reveal-on-scroll">
                    <div class="col-12 col-lg-8">
                        <div class="canva-section-header-box">
                            <h2 id="recursos-heading" class="section-header-title text-white mb-2">
                                Tudo o que a operação precisa, sem complicação
                            </h2>
                            <p class="text-steel fs-5 mb-0">
                                Informações claras e organizadas visualmente para agir rápido no dia a dia da ferrovia.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Bento 1 (Largo - 7 colunas) -->
                    <div class="col-12 col-lg-7 reveal-on-scroll">
                        <div class="bento-card bento-slate">
                            <div class="mb-4">
                                <h3 class="h3 fw-bold text-white mb-2">Prevenção de falhas em vagões e trilhos</h3>
                                <p class="text-steel mb-0">
                                    Superaquecimento de rolamentos e desgaste na via são identificados cedo, evitando paradas emergenciais e descarrilamentos.
                                </p>
                            </div>
                            <div class="bento-visual-box">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 py-2 border-bottom border-light border-opacity-10">
                                    <span class="fw-semibold text-white">S.TRM.001 — Locomotiva Principal</span>
                                    <span class="canva-status-pill status-ok">56 °C &bull; Funcionando</span>
                                </div>
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 py-2 border-bottom border-light border-opacity-10">
                                    <span class="fw-semibold text-white">S.TRLH.002 — Trecho Serra</span>
                                    <span class="canva-status-pill status-ok">19 °C &bull; Funcionando</span>
                                </div>
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-2">
                                    <span class="fw-semibold text-white">S.TRM.005 — Vagão de Carga</span>
                                    <span class="canva-status-pill status-warn">Manutenção Preventiva</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bento 2 (Médio - 5 colunas) -->
                    <div class="col-12 col-lg-5 reveal-on-scroll reveal-delay-1">
                        <div class="bento-card bento-azure">
                            <div class="mb-4">
                                <h3 class="h3 fw-bold text-white mb-2">Cadastro simples de trens e linhas</h3>
                                <p class="text-white text-opacity-75 mb-0">
                                    Registre composições com nome, código e linha associada em poucos cliques.
                                </p>
                            </div>
                            <div class="bento-visual-box">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <strong class="text-white d-block">Expresso Sul</strong>
                                        <small class="text-steel">Código: TRM-104 &bull; Linha 1</small>
                                    </div>
                                    <span class="canva-mini-tag">Ativo</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light border-opacity-10">
                                    <div>
                                        <strong class="text-white d-block">Carga Pesada Norte</strong>
                                        <small class="text-steel">Código: TRM-208 &bull; Linha 2</small>
                                    </div>
                                    <span class="canva-mini-tag">Ativo</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bento 3 (Médio - 5 colunas) -->
                    <div class="col-12 col-lg-5 reveal-on-scroll">
                        <div class="bento-card bento-green">
                            <div class="mb-4">
                                <h3 class="h3 fw-bold text-white mb-2">Equipe organizada por perfil</h3>
                                <p class="text-white text-opacity-75 mb-0">
                                    Administradores gerenciam cadastros e funcionários consultam tabelas e sensores com segurança.
                                </p>
                            </div>
                            <div class="bento-visual-box">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-white fw-semibold">Administradores</span>
                                    <span class="canva-mini-tag">Gestão completa</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light border-opacity-10">
                                    <span class="text-white fw-semibold">Funcionários</span>
                                    <span class="canva-mini-tag">Consulta e operação</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bento 4 (Largo - 7 colunas) -->
                    <div class="col-12 col-lg-7 reveal-on-scroll reveal-delay-1">
                        <div class="bento-card bento-navy">
                            <div class="mb-4">
                                <h3 class="h3 fw-bold text-white mb-2">Gráficos de velocidade e relatórios</h3>
                                <p class="text-steel mb-0">
                                    Compare a velocidade média de cada linha em gráficos claros e registre relatórios com data e horário.
                                </p>
                            </div>
                            <div class="bento-visual-box">
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-white">Linha 1 — Corredor Principal</span>
                                        <span class="text-volt fw-bold">68 km/h</span>
                                    </div>
                                    <div class="progress telemetry-progress"><div class="progress-bar bg-info" style="width: 78%"></div></div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-white">Linha 2 — Corredor de Carga</span>
                                        <span class="text-volt fw-bold">52 km/h</span>
                                    </div>
                                    <div class="progress telemetry-progress"><div class="progress-bar bg-success" style="width: 60%"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ====================================================================
             4. SEÇÃO DE SENSORES
             ==================================================================== -->
        <section id="sensores" class="section-padding section-surface-solid" aria-labelledby="sensores-heading">
            <div class="container">
                <div class="row justify-content-center text-center mb-5 reveal-on-scroll">
                    <div class="col-12 col-lg-7">
                        <h2 id="sensores-heading" class="section-header-title text-white mb-2">
                            O que cada sensor acompanha na ferrovia
                        </h2>
                        <p class="text-steel fs-5 mb-0">
                            Clique em um tipo de sensor para ver como ele ajuda na rotina da operação.
                        </p>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-4 reveal-on-scroll">
                        <article class="canva-feature-card sensor-card-selected" data-sensor-card="temperatura">
                            <span class="canva-card-pill mb-3">Trens e Vagões</span>
                            <h3 class="h4 fw-bold text-white mb-2">Temperatura</h3>
                            <p class="text-steel mb-0">
                                Mede o calor em eixos, rodas e freios durante a viagem para evitar superaquecimento e travamentos.
                            </p>
                        </article>
                    </div>

                    <div class="col-12 col-lg-4 reveal-on-scroll reveal-delay-1">
                        <article class="canva-feature-card" data-sensor-card="vibracao">
                            <span class="canva-card-pill mb-3">Via Permanente</span>
                            <h3 class="h4 fw-bold text-white mb-2">Vibração e Trilhos</h3>
                            <p class="text-steel mb-0">
                                Identifica trechos com desgaste, dilatação térmica ou desnível nos dormentes na passagem do trem.
                            </p>
                        </article>
                    </div>

                    <div class="col-12 col-lg-4 reveal-on-scroll reveal-delay-2">
                        <article class="canva-feature-card" data-sensor-card="velocidade">
                            <span class="canva-card-pill mb-3">Desempenho das Linhas</span>
                            <h3 class="h4 fw-bold text-white mb-2">Velocidade Média</h3>
                            <p class="text-steel mb-0">
                                Registra o ritmo de cada composição para alimentar os gráficos do painel e os relatórios de viagem.
                            </p>
                        </article>
                    </div>
                </div>

                <!-- Resumo Prático do Sensor Selecionado -->
                <div id="sensor-inspector-panel" class="canva-detail-strip reveal-on-scroll" aria-live="polite">
                    <div class="row align-items-center g-4">
                        <div class="col-12 col-lg-5">
                            <h4 id="sensor-inspector-title" class="h4 fw-bold text-white mb-2">
                                Monitoramento térmico de eixos e rolamentos
                            </h4>
                            <p id="sensor-inspector-desc" class="text-steel mb-0">
                                Acompanha variações de temperatura nos vagões e trilhos para avisar a equipe antes que uma peça sofra desgaste severo.
                            </p>
                        </div>
                        <div class="col-12 col-lg-7">
                            <div class="row g-3">
                                <div class="col-12 col-sm-4">
                                    <div class="canva-detail-item">
                                        <small class="text-steel d-block mb-1">Onde fica</small>
                                        <strong id="sensor-inspector-range" class="text-white small">Eixos dos trens e trilhos</strong>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="canva-detail-item">
                                        <small class="text-steel d-block mb-1">Leitura no sistema</small>
                                        <strong id="sensor-inspector-freq" class="text-white small">Graus Celsius (°C) em tempo real</strong>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="canva-detail-item">
                                        <small class="text-steel d-block mb-1">Benefício direto</small>
                                        <strong id="sensor-inspector-action" class="text-volt small">Evita quebras durante a viagem</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ====================================================================
             5. VANTAGENS + CHAMADA FINAL
             ==================================================================== -->
        <section id="beneficios" class="section-padding" aria-labelledby="beneficios-heading">
            <div class="container">
                <div class="row justify-content-center text-center mb-5 reveal-on-scroll">
                    <div class="col-12 col-lg-8">
                        <div class="canva-section-header-box">
                            <h2 id="beneficios-heading" class="section-header-title text-white mb-2">
                                Feito para facilitar a rotina da operação
                            </h2>
                            <p class="text-steel fs-5 mb-0">
                                Menos planilhas soltas e mais clareza para quem cuida da malha ferroviária.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-12 col-sm-6 col-lg-3 reveal-on-scroll">
                        <div class="canva-benefit-card">
                            <div class="canva-benefit-number">01</div>
                            <h3 class="h5 fw-bold text-white mb-2">Mais segurança</h3>
                            <p class="text-steel mb-0 small">
                                Alertas claros ajudam a agir antes que pequenas falhas se tornem acidentes na linha.
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 reveal-on-scroll reveal-delay-1">
                        <div class="canva-benefit-card">
                            <div class="canva-benefit-number">02</div>
                            <h3 class="h5 fw-bold text-white mb-2">Menos gastos com reparos</h3>
                            <p class="text-steel mb-0 small">
                                Manutenções planejadas com base nos sensores custam muito menos do que consertos de urgência.
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 reveal-on-scroll reveal-delay-2">
                        <div class="canva-benefit-card">
                            <div class="canva-benefit-number">03</div>
                            <h3 class="h5 fw-bold text-white mb-2">Visão rápida no Hub</h3>
                            <p class="text-steel mb-0 small">
                                Gráficos e tabelas diretas mostram o que está funcionando e o que precisa de atenção.
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 reveal-on-scroll reveal-delay-3">
                        <div class="canva-benefit-card">
                            <div class="canva-benefit-number">04</div>
                            <h3 class="h5 fw-bold text-white mb-2">Histórico organizado</h3>
                            <p class="text-steel mb-0 small">
                                Todos os relatórios operacionais ficam salvos com horário e descrição para consulta imediata.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Banner Final Estilo Canva -->
                <div class="canva-cta-banner p-4 p-md-5 text-center reveal-on-scroll">
                    <h3 class="display-6 fw-bold text-white mb-3 brand-font">
                        No ritmo que move estados.
                    </h3>
                    <p class="text-white text-opacity-75 fs-5 mb-4 mx-auto" style="max-width: 38rem;">
                        Entre na plataforma TrackFlow para acompanhar os sensores, trens, linhas e relatórios da sua operação.
                    </p>
                    <a href="public/Login.php" class="btn btn-canva-primary rounded-pill px-5 py-3">
                        Acessar o TrackFlow
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- ========================================================================
         6. RODAPÉ
         ======================================================================== -->
    <footer class="footer-ferrorama py-4">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <img src="Assets/logo/logo-track-flow.png" alt="Logo TrackFlow" width="30" height="30" loading="lazy" decoding="async" class="navbar-brand-logo">
                    <span class="fw-bold text-white brand-font fs-5">TrackFlow</span>
                    <span class="text-steel small ms-2">&copy; <?= $anoAtual ?> Todos os direitos reservados.</span>
                </div>

                <div class="d-flex flex-wrap justify-content-center gap-4 small">
                    <a href="#inicio" class="footer-link">Início</a>
                    <a href="#recursos" class="footer-link">Recursos</a>
                    <a href="#sensores" class="footer-link">Sensores</a>
                    <a href="public/Login.php" class="footer-link text-volt fw-semibold">Entrar no Sistema</a>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="scripts/three.min.js"></script>
    <script src="scripts/scriptIndex.js?v=<?= $jsVersion ?>" defer></script>
</body>

</html>
