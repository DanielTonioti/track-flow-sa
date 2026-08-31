let currentSensor;
let currentList;
let currentArrow;
// Buckle your seats, the following code is ulfiltered ASS
const botaoSensor001 = document.getElementById("sensor001");
const botaoSensor002 = document.getElementById("sensor002");
const botaoSensor003 = document.getElementById("sensor003");
const botaoSensor004 = document.getElementById("sensor004");
const botaoSensor005 = document.getElementById("sensor005");
const botaoSensor006 = document.getElementById("sensor006");
const botaoSensor007 = document.getElementById("sensor007");
const botaoSensor008 = document.getElementById("sensor008");
const botaoSensor009 = document.getElementById("sensor009");
const botaoSensor010 = document.getElementById("sensor010");
const lista001 = document.getElementById("lista-sensor001");
const lista002 = document.getElementById("lista-sensor002");
const lista003 = document.getElementById("lista-sensor003");
const lista004 = document.getElementById("lista-sensor004");
const lista005 = document.getElementById("lista-sensor005");
const lista006 = document.getElementById("lista-sensor006");
const lista007 = document.getElementById("lista-sensor007");
const lista008 = document.getElementById("lista-sensor008");
const lista009 = document.getElementById("lista-sensor009");
const lista010 = document.getElementById("lista-sensor010");
const seta001 = document.getElementById("seta-admin001");
const seta002 = document.getElementById("seta-admin002");
const seta003 = document.getElementById("seta-admin003");
const seta004 = document.getElementById("seta-admin004");
const seta005 = document.getElementById("seta-admin005");
const seta006 = document.getElementById("seta-admin006");
const seta007 = document.getElementById("seta-admin007");
const seta008 = document.getElementById("seta-admin008");
const seta009 = document.getElementById("seta-admin009");
const seta010 = document.getElementById("seta-admin010");
const setaFuncionario = document.getElementById("seta-func");
const botaoFuncionario = document.getElementById("botao-func");
const listaFuncionario = document.getElementById("lista-func");

botaoSensor001.addEventListener('click', function () {

    currentSensor = botaoSensor001;
    currentList = lista001
    currentArrow = seta001
    shower();

});
botaoSensor002.addEventListener('click', function () {

    currentSensor = botaoSensor002;
    currentList = lista002;
    currentArrow = seta002;
    shower();

});
botaoSensor003.addEventListener('click', function () {

    currentSensor = botaoSensor003;
    currentList = lista003;
    currentArrow = seta003;
    shower();

});
botaoSensor004.addEventListener('click', function () {

    currentSensor = botaoSensor004;
    currentList = lista004;
    currentArrow = seta004;
    shower();

});
botaoSensor005.addEventListener('click', function () {

    currentSensor = botaoSensor005;
    currentList = lista005;
    currentArrow = seta005;
    shower();

});
botaoSensor006.addEventListener('click', function () {

    currentSensor = botaoSensor006;
    currentList = lista006;
    currentArrow = seta006;
    shower();

});
botaoSensor007.addEventListener('click', function () {

    currentSensor = botaoSensor007;
    currentList = lista007;
    currentArrow = seta007;
    shower();

});
botaoSensor008.addEventListener('click', function () {

    currentSensor = botaoSensor008;
    currentList = lista008;
    currentArrow = seta008;
    shower();

});
botaoSensor009.addEventListener('click', function () {

    currentSensor = botaoSensor009;
    currentList = lista009;
    currentArrow = seta009;
    shower();

});
botaoSensor010.addEventListener('click', function () {

    currentSensor = botaoSensor010;
    currentList = lista010;
    currentArrow = seta010;
    shower();

});
function shower() {
    const estaAberto = currentList.classList.contains('show');

    if (estaAberto) {

        currentList.classList.remove('show');
        currentArrow.style.transform = "rotate(0deg)";
    } else {

        currentList.classList.add('show');
        currentArrow.style.transform = "rotate(180deg)";
    }
}
document.getElementById("AddSensor").onclick = () => {
    window.location.href = "../public/Sensor.html";
}