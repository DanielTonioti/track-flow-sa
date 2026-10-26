const localizacaoBox = document.getElementById("localizacao-box");
const tremVinculadoBox = document.getElementById("trem-vinculado-box");
const trem = document.getElementById("trem");
const trilho = document.getElementById("trilho");
const valorTipo = document.getElementById("valor-tipo");

function mostrarTipo() {
    if (trem.checked) {
        tremVinculadoBox.classList.remove("hiden");
    } else {
        tremVinculadoBox.classList.add("hiden");
    }

    if (trilho.checked) {
        localizacaoBox.classList.remove("hiden");
    } else {
        localizacaoBox.classList.add("hiden");
    }
}

valorTipo.addEventListener("change", mostrarTipo);
trem.addEventListener("change", mostrarTipo);
trilho.addEventListener("change", mostrarTipo);
