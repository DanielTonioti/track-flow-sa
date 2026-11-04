document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btn-toggle-senha-cad') || document.getElementById('btn-toggle-senha-edit') || document.getElementById('btn-toggle-senha');
    const input = document.getElementById('senha') || document.getElementById('usuario-senha');

    if (btn && input) {
        btn.addEventListener('click', function () {
            const oculto = input.type === 'password';
            input.type = oculto ? 'text' : 'password';
            btn.setAttribute('aria-label', oculto ? 'Ocultar senha' : 'Mostrar senha');
            const icon = btn.querySelector('i');
            if (icon) {
                icon.className = oculto ? 'bi bi-eye-slash' : 'bi bi-eye';
            }
        });
    }
});

