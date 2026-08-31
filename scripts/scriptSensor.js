const localisation = document.getElementById("localisation"); 
const tout = document.getElementById("tout");
const trem = document.getElementById("trem");
const trilho = document.getElementById("trilho");
const valortipo = document.getElementById("valortipo");

function mostrartipo(){
    const valortiposss = valortipo.value;

    if (trem.checked){
        tout.classList.remove("hiden");
    }else{
        tout.classList.add("hiden");
    }if (trilho.checked){
        localisation.classList.remove("hiden");
    }else{
        localisation.classList.add("hiden");
    }
}

valortipo.addEventListener("change", mostrartipo);
trem.addEventListener("change", mostrartipo);
trilho.addEventListener("change", mostrartipo);




let time = 30;
document.getElementById("dados-buttom").onclick = () => {
    time = 30;
    let id001 = setInterval(function () {
        if (time >= 1) {
            time -= 1;
        }
        else {
            window.location.href = "../public/Sensor_Data.html";
            time = 30;
            clearInterval(id001);
        }
    }, 10);
}
document.getElementById("gerenciar-buttom").onclick = () => {
    time = 30;
    let id001 = setInterval(function () {
        if (time >= 1) {
            time -= 1;
        }
        else {
            window.location.href = "../public/Sensor_Manage.html";
            time = 30;
            clearInterval(id001);
        }
    }, 10);
}