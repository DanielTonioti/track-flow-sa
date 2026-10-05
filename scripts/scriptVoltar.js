const rotasVoltar = {
    voltarhub: "hub.php",
    voltaradmin: "Admin.php",
    voltarsensor: "sensor.php"
};

const buttonsVoltar = document.querySelectorAll('[data-voltar], #voltarhub, #voltaradmin, #voltarsensor');

buttonsVoltar.forEach((botao) => {
    const destino = botao.dataset.voltar || rotasVoltar[botao.id];

    if (!destino) return;

    botao.addEventListener('click', () => {
        window.location.href = destino;
    });
});