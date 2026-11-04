document.addEventListener('DOMContentLoaded', function () {
    const dEl = document.getElementById('distancia_km');
    const tEl = document.getElementById('tempo_previsto_min');
    const out = document.getElementById('preview-velocidade-edit');

    function calc() {
        if (!dEl || !tEl || !out) return;
        const d = parseFloat(dEl.value);
        const t = parseFloat(tEl.value);
        out.textContent = (d > 0 && t > 0) ? (d / (t / 60)).toFixed(1).replace('.', ',') + ' km/h' : '— km/h';
    }

    if (dEl && tEl) {
        dEl.addEventListener('input', calc);
        tEl.addEventListener('input', calc);
        calc();
    }
});

