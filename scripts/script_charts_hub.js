google.charts.load('current', { packages: ['corechart'], language: 'pt-BR' });

function desenharGraficos() {
    if (typeof google === 'undefined' || !google.visualization) return;

    const charts = [
        { id: 'chart-velocidade', tipo: 'combo' },
        { id: 'chart-temperatura', tipo: 'line' },
        { id: 'chart-status', tipo: 'donut' }
    ];

    charts.forEach(({ id, tipo }) => {
        const el = document.getElementById(id);
        if (!el) return;

        if (tipo === 'combo') {
            const dataVel = google.visualization.arrayToDataTable([
                ['Composição', 'Velocidade (km/h)', { role: 'style' }, { role: 'annotation' }, 'Teto (80 km/h)'],
                ['C-01', 62, '#64748b', null, 80],
                ['C-02', 74, '#64748b', null, 80],
                ['V-12', 58, '#64748b', null, 80],
                ['V-14', 81, '#f59e0b', '81 km/h', 80],
                ['T-08', 4,  '#334155', 'Parada', 80]
            ]);

            const optVel = {
                backgroundColor: 'transparent',
                seriesType: 'bars',
                series: { 1: { type: 'line', color: '#ff5757', lineDashStyle: [4, 4], pointSize: 0, visibleInLegend: true } },
                legend: { position: 'top', textStyle: { color: '#94a3b8', fontSize: 11 } },
                chartArea: { width: '82%', height: '65%', top: 32, bottom: 36, left: 42 },
                hAxis: { textStyle: { color: '#94a3b8', fontSize: 11, fontName: 'Inter' } },
                vAxis: {
                    textStyle: { color: '#64748b', fontSize: 10, fontName: 'Inter' },
                    gridlines: { color: 'rgba(255,255,255,0.06)' },
                    baselineColor: 'rgba(255,255,255,0.12)',
                    minValue: 0,
                    maxValue: 100
                },
                annotations: { textStyle: { color: '#ffffff', fontSize: 10, bold: true } }
            };

            const chart = new google.visualization.ComboChart(el);
            chart.draw(dataVel, optVel);

        } else if (tipo === 'line') {
            const dataTemp = google.visualization.arrayToDataTable([
                ['Horário', 'Mancal C-01', 'Mancal C-02', 'Alerta (75°C)'],
                ['06:00', 42, 45, 75],
                ['07:00', 51, 53, 75],
                ['08:00', 63, 61, 75],
                ['09:00', 71, 68, 75],
                ['10:00', 78, 72, 75],
                ['11:00', 69, 74, 75]
            ]);

            const optTemp = {
                backgroundColor: 'transparent',
                curveType: 'function',
                series: {
                    0: { color: '#00d2ff', lineWidth: 2, pointSize: 4 },
                    1: { color: '#c1ff72', lineWidth: 2, pointSize: 4 },
                    2: { color: '#ff5757', lineDashStyle: [4, 4], pointSize: 0, lineWidth: 1.5 }
                },
                legend: { position: 'top', textStyle: { color: '#94a3b8', fontSize: 11 } },
                chartArea: { width: '82%', height: '65%', top: 32, bottom: 36, left: 42 },
                hAxis: { textStyle: { color: '#94a3b8', fontSize: 11, fontName: 'Inter' } },
                vAxis: {
                    textStyle: { color: '#64748b', fontSize: 10, fontName: 'Inter' },
                    gridlines: { color: 'rgba(255,255,255,0.06)' },
                    baselineColor: 'rgba(255,255,255,0.12)'
                }
            };

            const chart = new google.visualization.LineChart(el);
            chart.draw(dataTemp, optTemp);

        } else if (tipo === 'donut') {
            const dataStatus = google.visualization.arrayToDataTable([
                ['Condição', 'Sensores'],
                ['Normal', 7],
                ['Atenção', 2],
                ['Falha/Crítico', 0]
            ]);

            const optStatus = {
                backgroundColor: 'transparent',
                pieHole: 0.62,
                colors: ['#00bf63', '#f59e0b', '#ff5757'],
                legend: { position: 'bottom', textStyle: { color: '#94a3b8', fontSize: 11, fontName: 'Inter' } },
                chartArea: { width: '90%', height: '75%', top: 10, bottom: 40 },
                pieSliceBorderColor: 'transparent',
                pieSliceTextStyle: { color: '#ffffff', fontSize: 11 }
            };

            const chart = new google.visualization.PieChart(el);
            chart.draw(dataStatus, optStatus);
        }
    });
}

function inicializarCarrosselGraficos() {
    const slides = Array.from(document.querySelectorAll('.ops-chart-slide'));
    const dots = Array.from(document.querySelectorAll('[data-slide]'));
    const buttons = document.querySelectorAll('[data-carousel]');

    if (!slides.length) return;

    let indexAtual = 0;

    const atualizarCarrossel = (novoIndex) => {
        indexAtual = (novoIndex + slides.length) % slides.length;

        slides.forEach((slide, index) => {
            const ativo = index === indexAtual;
            slide.classList.toggle('active', ativo);
            slide.setAttribute('aria-hidden', (!ativo).toString());
        });

        dots.forEach((dot, index) => {
            const ativo = index === indexAtual;
            dot.classList.toggle('active', ativo);
            dot.setAttribute('aria-selected', ativo.toString());
        });

        desenharGraficos();
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

google.charts.setOnLoadCallback(() => {
    desenharGraficos();
    inicializarCarrosselGraficos();

    let timerRedim;
    window.addEventListener('resize', () => {
        clearTimeout(timerRedim);
        timerRedim = setTimeout(() => {
            desenharGraficos();
            inicializarCarrosselGraficos();
        }, 120);
    });
});

