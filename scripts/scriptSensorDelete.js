function excluirSensor() {
    const confirmar = document.getElementById('ConfirmarExclusaoSensor');
    const formulario = document.getElementById('deleteSensorForm');
    const campoId = document.getElementById('deleteSensorId');
    const botoes = document.querySelectorAll('[data-bs-target="#ModalDeletarSensor"]');

    if (!confirmar || !formulario || !campoId) {
        return;
    }

    for (const botao of botoes) {
        botao.onclick = function () {
            campoId.value = botao.getAttribute('data-sensor-id') || '';
        };
    }

    confirmar.onclick = function () {
        if (campoId.value !== '') {
            formulario.submit();
        }
    };
}

excluirSensor();