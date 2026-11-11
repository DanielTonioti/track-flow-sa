document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-preparar-exclusao-trem').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const inputId = document.getElementById('inputExcluirTremId');
            const nomeModal = document.getElementById('nomeTremModal');

            if (inputId) inputId.value = btn.getAttribute('data-trem-id') || '';
            if (nomeModal) nomeModal.textContent = btn.getAttribute('data-trem-nome') || '';
        });
    });
});

