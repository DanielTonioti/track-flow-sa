const botao = document.getElementById("botao-admin");
const lista = document.getElementById("lista-adm");
const seta = document.getElementById("seta-admin");

const botaoFuncionario = document.getElementById("botao-func");
const listaFuncionario = document.getElementById("lista-func");
const setaFuncionario = document.getElementById("seta-func");

botao?.addEventListener("click", () => {
    lista.classList.toggle("show");
    seta.style.transform = lista.classList.contains("show")
        ? "rotate(180deg)"
        : "rotate(0deg)";
});

botaoFuncionario?.addEventListener("click", () => {
    listaFuncionario.classList.toggle("show");
    setaFuncionario.style.transform = listaFuncionario.classList.contains("show")
        ? "rotate(180deg)"
        : "rotate(0deg)";
});