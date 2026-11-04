
        document.addEventListener('DOMContentLoaded', function () {
            const distEl = document.getElementById('distancia_km');
            const tempoEl = document.getElementById('tempo_previsto_min');
            const velOut = document.getElementById('preview-velocidade-valor');

            function atualizarVelocidade() {
                if (!distEl || !tempoEl || !velOut) return;
                const d = parseFloat(distEl.value);
                const t = parseFloat(tempoEl.value);
                if (d > 0 && t > 0) {
                    const vel = d / (t / 60);
                    velOut.textContent = vel.toFixed(1).replace('.', ',') + ' km/h';
                } else {
                    velOut.textContent = '— km/h';
                }
            }

            if (distEl && tempoEl) {
                distEl.addEventListener('input', atualizarVelocidade);
                tempoEl.addEventListener('input', atualizarVelocidade);
                atualizarVelocidade();
            }

            document.querySelectorAll('.btn-preparar-exclusao-rota').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('inputExcluirRotaId').value = btn.getAttribute('data-rota-id') || '';
                    document.getElementById('nomeRotaModal').textContent = btn.getAttribute('data-rota-nome') || '';
                });
            });
        });