const adminButton = document.getElementById("admin-button");
if (adminButton) {
    adminButton.onclick = () => {
        let time = 30;
        let id001 = setInterval(function () {
            if (time >= 1) {
                time -= 1;
            }
            else {
                window.location.href = "cadastro_usuario.php";
                time = 30;
                clearInterval(id001);
            }
        }, 10);
    };
}

const botao = document.getElementById("botao-admin");
const lista = document.getElementById("lista-adm");
const seta = document.getElementById("seta-admin");
const setaFuncionario = document.getElementById("seta-func");
const botaoFuncionario = document.getElementById("botao-func");
const listaFuncionario = document.getElementById("lista-func");

if (botao && lista && seta) {
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
}

if (botaoFuncionario && listaFuncionario && setaFuncionario) {
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
}