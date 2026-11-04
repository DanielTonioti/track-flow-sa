document.addEventListener('DOMContentLoaded', function () {
    const radioTrem = document.getElementById('trem');
    const radioTrilho = document.getElementById('trilho');
    const boxTrem = document.getElementById('trem-vinculado-box');
    const boxLocal = document.getElementById('localizacao-box');

    function atualizarCampos() {
        const ehTrilho = radioTrilho && radioTrilho.checked;
        if (boxTrem) boxTrem.classList.toggle('d-none', ehTrilho);
        if (boxLocal) boxLocal.classList.toggle('d-none', !ehTrilho);
    }

    if (radioTrem) radioTrem.addEventListener('change', atualizarCampos);
    if (radioTrilho) radioTrilho.addEventListener('change', atualizarCampos);
    atualizarCampos();
});
