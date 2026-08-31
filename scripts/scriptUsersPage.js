

const botao = document.getElementById("botao-admin");
const lista = document.getElementById("lista-adm");
const seta = document.getElementById("seta-admin");
const setaFuncionario = document.getElementById("seta-func");
const botaoFuncionario = document.getElementById("botao-func");
const listaFuncionario = document.getElementById("lista-func");

botao.addEventListener('click', function () {


    const estaAberto = lista.classList.contains('show');

    if (estaAberto) {

        lista.classList.remove('show');
        seta.style.transform = "rotate(0deg)";
    } else {

        lista.classList.add('show');
        seta.style.transform = "rotate(180deg)";
    }
});


botaoFuncionario.addEventListener('click', function () {


    const estaAberto = listaFuncionario.classList.contains('show');

    if (estaAberto) {

        listaFuncionario.classList.remove('show');
        setaFuncionario.style.transform = "rotate(0deg)";
    } else {

        listaFuncionario.classList.add('show');
        setaFuncionario.style.transform = "rotate(180deg)";
    }
});