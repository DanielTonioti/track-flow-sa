document.addEventListener('DOMContentLoaded', function () {
    const linkConfirmar = document.getElementById('link-confirmar-exclusao-sensor');
    const nomeModal = document.getElementById('nome-sensor-modal');

    document.querySelectorAll('.btn-preparar-exclusao').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = btn.getAttribute('data-sensor-id') || '0';
            const nome = btn.getAttribute('data-sensor-nome') || 'selecionado';
            if (nomeModal) nomeModal.textContent = nome;
            if (linkConfirmar) linkConfirmar.setAttribute('href', 'excluir_sensor.php?id=' + encodeURIComponent(id));
        });
    });
});

