/**
 * scripts/script_charts_hub.js
 * Renderiza gráficos de telemetria no Hub CCO.
 * - Em redes lentas (3G) ou computadores fracos (data-perf="low"), utiliza gráficos SVG nativos
 *   ultra-leves (< 1ms, zero requisições externas), evitando baixar ~200 KB do Google Charts.
 * - Em computadores fortes e rede rápida (data-perf="high"), faz upgrade assíncrono para Google Charts
 *   após o carregamento inicial sem bloquear o LCP.
 */
(function () {
    let googleChartsPronto = false;
    let googleChartsCarregando = false;

    function isLightMode() {
        return document.documentElement.getAttribute('data-theme') === 'light';
    }

    function isLowPerf() {
        return document.documentElement.getAttribute('data-perf') === 'low';
    }

    /* ======================================================================
       RENDERIZADOR SVG NATIVO ULTRA-LEVE (< 1ms, ZERO DEPENDÊNCIAS)
       ====================================================================== */
    function desenharGraficosSvgNativo() {
        const isLight = isLightMode();
        const textColor = isLight ? '#475569' : '#94a3b8';
        const gridColor = isLight ? 'rgba(15, 23, 42, 0.08)' : 'rgba(255, 255, 255, 0.06)';
        const labelColor = isLight ? '#0f172a' : '#f8fafc';

        // 1. Gráfico de Velocidade (Barras + Linha de Teto 80 km/h)
        const elVel = document.getElementById('chart-velocidade');
        if (elVel) {
            const barras = [
                { nome: 'C-01', val: 62, cor: '#5271ff', nota: '' },
                { nome: 'C-02', val: 74, cor: '#5271ff', nota: '' },
                { nome: 'V-12', val: 58, cor: '#5271ff', nota: '' },
                { nome: 'V-14', val: 81, cor: '#f59e0b', nota: '81 km/h' },
                { nome: 'T-08', val: 4,  cor: '#3b5870', nota: 'Parada' }
            ];
            let svgBars = '';
            barras.forEach((b, i) => {
                const x = 65 + i * 82;
                const h = Math.max(4, (b.val / 100) * 170);
                const y = 200 - h;
                svgBars += `<rect x="${x}" y="${y}" width="44" height="${h}" rx="4" fill="${b.cor}"/>`;
                svgBars += `<text x="${x + 22}" y="220" text-anchor="middle" fill="${textColor}" font-size="11" font-family="system-ui,sans-serif">${b.nome}</text>`;
                if (b.nota) {
                    svgBars += `<text x="${x + 22}" y="${y - 6}" text-anchor="middle" fill="${labelColor}" font-size="10" font-weight="700" font-family="system-ui,sans-serif">${b.nota}</text>`;
                }
            });
            elVel.innerHTML = `<svg viewBox="0 0 480 240" width="100%" height="100%" role="img" aria-label="Gráfico de velocidade por composição">
                <line x1="42" y1="30" x2="460" y2="30" stroke="${gridColor}"/>
                <line x1="42" y1="115" x2="460" y2="115" stroke="${gridColor}"/>
                <line x1="42" y1="200" x2="460" y2="200" stroke="${gridColor}"/>
                <line x1="42" y1="64" x2="460" y2="64" stroke="#ef4444" stroke-width="1.5" stroke-dasharray="4,4"/>
                <text x="34" y="34" text-anchor="end" fill="${textColor}" font-size="10">100</text>
                <text x="34" y="68" text-anchor="end" fill="#ef4444" font-size="10">80</text>
                <text x="34" y="119" text-anchor="end" fill="${textColor}" font-size="10">50</text>
                <text x="34" y="204" text-anchor="end" fill="${textColor}" font-size="10">0</text>
                ${svgBars}
            </svg>`;
        }

        // 2. Gráfico de Temperatura (Linha Semanal + Limite 85 °C)
        const elTemp = document.getElementById('chart-temperatura');
        if (elTemp) {
            const dias = [
                { d: 'Seg', t: 52 }, { d: 'Ter', t: 56 }, { d: 'Qua', t: 54 },
                { d: 'Qui', t: 61 }, { d: 'Sex', t: 64 }, { d: 'Sáb', t: 55 }, { d: 'Dom', t: 48 }
            ];
            const pts = dias.map((item, i) => {
                const x = 55 + i * 64;
                const y = 200 - ((item.t - 20) / 80) * 170;
                return { x, y, ...item };
            });
            const poly = pts.map(p => `${p.x},${p.y}`).join(' ');
            const circles = pts.map(p => `<circle cx="${p.x}" cy="${p.y}" r="3.5" fill="#f59e0b"/><text x="${p.x}" y="220" text-anchor="middle" fill="${textColor}" font-size="11">${p.d}</text>`).join('');
            const yLimite = 200 - ((85 - 20) / 80) * 170;
            elTemp.innerHTML = `<svg viewBox="0 0 480 240" width="100%" height="100%" role="img" aria-label="Gráfico de temperatura média da via">
                <line x1="42" y1="30" x2="460" y2="30" stroke="${gridColor}"/>
                <line x1="42" y1="115" x2="460" y2="115" stroke="${gridColor}"/>
                <line x1="42" y1="200" x2="460" y2="200" stroke="${gridColor}"/>
                <line x1="42" y1="${yLimite}" x2="460" y2="${yLimite}" stroke="#ef4444" stroke-width="1.5" stroke-dasharray="4,4"/>
                <polyline fill="none" stroke="#f59e0b" stroke-width="2.2" points="${poly}"/>
                <text x="34" y="34" text-anchor="end" fill="${textColor}" font-size="10">100</text>
                <text x="34" y="${yLimite + 3}" text-anchor="end" fill="#ef4444" font-size="10">85</text>
                <text x="34" y="119" text-anchor="end" fill="${textColor}" font-size="10">60</text>
                <text x="34" y="204" text-anchor="end" fill="${textColor}" font-size="10">20</text>
                ${circles}
            </svg>`;
        }

        // 3. Gráfico Donut de Status dos Sensores (8 Normal, 1 Atenção, 1 Falha)
        const elSt = document.getElementById('chart-status');
        if (elSt) {
            const r = 68;
            const c = 2 * Math.PI * r;
            const pNormal = 0.80 * c;
            const pAtencao = 0.10 * c;
            const pFalha = 0.10 * c;
            elSt.innerHTML = `<svg viewBox="0 0 480 240" width="100%" height="100%" role="img" aria-label="Gráfico de condição dos sensores">
                <g transform="translate(240, 108) rotate(-90)">
                    <circle r="${r}" cx="0" cy="0" fill="transparent" stroke="#00bf63" stroke-width="22" stroke-dasharray="${pNormal} ${c}" stroke-dashoffset="0"/>
                    <circle r="${r}" cx="0" cy="0" fill="transparent" stroke="#f59e0b" stroke-width="22" stroke-dasharray="${pAtencao} ${c}" stroke-dashoffset="-${pNormal}"/>
                    <circle r="${r}" cx="0" cy="0" fill="transparent" stroke="#ff5757" stroke-width="22" stroke-dasharray="${pFalha} ${c}" stroke-dashoffset="-${pNormal + pAtencao}"/>
                </g>
                <g transform="translate(135, 222)" font-size="11" fill="${textColor}" font-family="system-ui,sans-serif">
                    <circle cx="0" cy="-4" r="4" fill="#00bf63"/><text x="8" y="0">Normal</text>
                    <circle cx="78" cy="-4" r="4" fill="#f59e0b"/><text x="86" y="0">Atenção</text>
                    <circle cx="160" cy="-4" r="4" fill="#ff5757"/><text x="168" y="0">Falha</text>
                </g>
            </svg>`;
        }
    }

    /* ======================================================================
       RENDERIZADOR GOOGLE CHARTS (APENAS EM PC FORTE + REDE RÁPIDA)
       ====================================================================== */
    function desenharGoogleCharts() {
        if (typeof google === 'undefined' || !google.visualization) {
            desenharGraficosSvgNativo();
            return;
        }

        const isLight = isLightMode();
        const axisTextColor = isLight ? '#475569' : '#94a3b8';
        const gridlineColor = isLight ? 'rgba(15, 23, 42, 0.08)' : 'rgba(255, 255, 255, 0.05)';
        const baselineColor = isLight ? 'rgba(15, 23, 42, 0.16)' : 'rgba(255, 255, 255, 0.1)';
        const annotationColor = isLight ? '#0f172a' : '#f8fafc';
        const pieBorderColor = isLight ? '#ffffff' : '#0b1523';

        const elVel = document.getElementById('chart-velocidade');
        if (elVel) {
            const dataVel = google.visualization.arrayToDataTable([
                ['Composição', 'Velocidade (km/h)', { role: 'style' }, { role: 'annotation' }, 'Teto (80 km/h)'],
                ['C-01', 62, '#5271ff', null, 80],
                ['C-02', 74, '#5271ff', null, 80],
                ['V-12', 58, '#5271ff', null, 80],
                ['V-14', 81, '#f59e0b', '81 km/h', 80],
                ['T-08', 4,  '#3b5870', 'Parada', 80]
            ]);
            new google.visualization.ComboChart(elVel).draw(dataVel, {
                title: '',
                backgroundColor: 'transparent',
                legend: { position: 'none' },
                seriesType: 'bars',
                series: { 1: { type: 'line', color: '#ef4444', lineDashStyle: [4, 4], lineWidth: 1.5, pointSize: 0, visibleInLegend: false } },
                hAxis: { textStyle: { color: axisTextColor, fontName: 'Inter', fontSize: 11 }, gridlines: { color: 'transparent' } },
                vAxis: { minValue: 0, maxValue: 100, textStyle: { color: axisTextColor, fontName: 'Inter', fontSize: 11 }, gridlines: { count: 6, color: gridlineColor }, minorGridlines: { count: 0 }, baselineColor },
                chartArea: { left: 38, top: 18, right: 16, bottom: 32, width: '92%', height: '80%' },
                annotations: { textStyle: { fontName: 'Inter', fontSize: 10, color: annotationColor, bold: true } }
            });
        }

        const elTemp = document.getElementById('chart-temperatura');
        if (elTemp) {
            const dataTemp = google.visualization.arrayToDataTable([
                ['Dia', 'Média da Via (°C)', 'Limite Seguro (85 °C)'],
                ['Seg', 52, 85], ['Ter', 56, 85], ['Qua', 54, 85],
                ['Qui', 61, 85], ['Sex', 64, 85], ['Sáb', 55, 85], ['Dom', 48, 85]
            ]);
            new google.visualization.LineChart(elTemp).draw(dataTemp, {
                title: '',
                backgroundColor: 'transparent',
                legend: { position: 'top', alignment: 'end', textStyle: { color: axisTextColor, fontName: 'Inter', fontSize: 10 } },
                hAxis: { textStyle: { color: axisTextColor, fontName: 'Inter', fontSize: 11 }, gridlines: { color: 'transparent' } },
                vAxis: { minValue: 20, maxValue: 100, textStyle: { color: axisTextColor, fontName: 'Inter', fontSize: 11 }, gridlines: { count: 5, color: gridlineColor }, minorGridlines: { count: 0 }, baselineColor },
                colors: ['#f59e0b', '#ef4444'],
                chartArea: { left: 38, top: 24, right: 16, bottom: 32, width: '92%', height: '76%' },
                pointSize: 4,
                lineWidth: 2,
                series: { 1: { lineDashStyle: [4, 4], pointSize: 0, lineWidth: 1.5 } }
            });
        }

        const elSt = document.getElementById('chart-status');
        if (elSt) {
            const dataSt = google.visualization.arrayToDataTable([
                ['Condição', 'Quantidade'],
                ['Normal', 8], ['Atenção', 1], ['Falha', 1]
            ]);
            new google.visualization.PieChart(elSt).draw(dataSt, {
                title: '',
                backgroundColor: 'transparent',
                legend: { position: 'bottom', alignment: 'center', textStyle: { color: axisTextColor, fontName: 'Inter', fontSize: 11 } },
                colors: ['#00bf63', '#f59e0b', '#ff5757'],
                chartArea: { left: 10, top: 6, right: 10, bottom: 28, width: '94%', height: '84%' },
                pieHole: 0.72,
                pieSliceBorderColor: pieBorderColor,
                pieSliceText: 'none'
            });
        }
    }

    function desenharGraficos() {
        if (isLowPerf() || !googleChartsPronto) {
            desenharGraficosSvgNativo();
        } else {
            desenharGoogleCharts();
        }
    }

    function carregarGoogleChartsEmIdle() {
        if (isLowPerf() || googleChartsPronto || googleChartsCarregando) return;
        googleChartsCarregando = true;
        const s = document.createElement('script');
        s.src = 'https://www.gstatic.com/charts/loader.js';
        s.async = true;
        s.onload = () => {
            if (typeof google !== 'undefined' && google.charts) {
                google.charts.load('current', { packages: ['corechart'], language: 'pt-BR' });
                google.charts.setOnLoadCallback(() => {
                    googleChartsPronto = true;
                    if (!isLowPerf()) {
                        desenharGoogleCharts();
                    }
                });
            }
        };
        document.head.appendChild(s);
    }

    function inicializarCarrosselGraficos() {
        const track = document.getElementById('chart-carousel-track');
        const slides = Array.from(document.querySelectorAll('.ops-chart-slide'));
        const dots = Array.from(document.querySelectorAll('.ops-chart-dot'));
        const buttons = Array.from(document.querySelectorAll('.ops-carousel-btn'));
        let indexAtual = 0;

        if (!track || !slides.length) return;

        const atualizarCarrossel = (novoIndice) => {
            indexAtual = (novoIndice + slides.length) % slides.length;
            track.style.transform = `translateX(-${indexAtual * 100}%)`;

            slides.forEach((slide, idx) => {
                slide.classList.toggle('is-active', idx === indexAtual);
            });

            dots.forEach((dot, idx) => {
                dot.classList.toggle('is-active', idx === indexAtual);
                dot.setAttribute('aria-pressed', idx === indexAtual ? 'true' : 'false');
            });

            if (googleChartsPronto && !isLowPerf()) {
                setTimeout(desenharGoogleCharts, 50);
            }
        };

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                const direcao = button.dataset.carousel === 'next' ? 1 : -1;
                atualizarCarrossel(indexAtual + direcao);
            });
        });

        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                atualizarCarrossel(Number(dot.dataset.slide));
            });
        });

        atualizarCarrossel(0);
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Renderiza imediatamente em < 1ms via SVG nativo para não atrasar nada
        desenharGraficosSvgNativo();
        inicializarCarrosselGraficos();

        // Se o PC e a conexão forem fortes, carrega o Google Charts em segundo plano após o load
        if (!isLowPerf()) {
            window.addEventListener('load', () => {
                const runIdle = window.requestIdleCallback || ((cb) => setTimeout(cb, 300));
                runIdle(carregarGoogleChartsEmIdle);
            }, { once: true });
        }

        let timerRedim;
        window.addEventListener('resize', () => {
            if (isLowPerf() || !googleChartsPronto) return;
            clearTimeout(timerRedim);
            timerRedim = setTimeout(desenharGoogleCharts, 180);
        });

        window.addEventListener('trackflow:themechange', desenharGraficos);
        window.addEventListener('trackflow:perfchange', desenharGraficos);
    });
})();
