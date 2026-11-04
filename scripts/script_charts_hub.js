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

            new google.visualization.ComboChart(el).draw(dataVel, optVel);
            return;
        }

        if (tipo === 'line') {
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

            new google.visualization.LineChart(el).draw(dataTemp, optTemp);
            return;
        }

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

        new google.visualization.PieChart(el).draw(dataSt, optSt);
    });
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

        setTimeout(() => {
            desenharGraficos();
        }, 50);
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

function iniciar() {
    desenharGraficos();
    inicializarCarrosselGraficos();
}

google.charts.setOnLoadCallback(() => {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', iniciar);
    } else {
        iniciar();
    }

    let timerRedim;
    window.addEventListener('resize', () => {
        clearTimeout(timerRedim);
        timerRedim = setTimeout(() => {
            desenharGraficos();
        }, 120);
    });
});
