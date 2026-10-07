const perfil = document.querySelector('.perfil');
const menu = document.querySelector('.menu');

perfil.onclick = () => {
    menu.classList.toggle('ativo');
}

let timenav = 30;

const hubToggleAdmin = document.getElementById("hub-toggle-admin");
if (hubToggleAdmin) {
    hubToggleAdmin.onclick = () => {
        timenav = 30;
        let id001 = setInterval(function () {
            if (timenav >= 1) {
                timenav -= 1;
            }
            else {
                window.location.href = "Admin.php";
                timenav = 30;
                clearInterval(id001);
            }
        }, 10);
    };
} // Brings you to the admin page after a small delay

const hubToggleRelatories = document.getElementById("hub-toggle-relatories");
if (hubToggleRelatories) {
    hubToggleRelatories.onclick = () => {
        timenav = 30;
        let id001 = setInterval(function () {
            if (timenav >= 1) {
                timenav -= 1;
            }
            else {
                window.location.href = "Relatories.php";
                timenav = 30;
                clearInterval(id001);
            }
        }, 10);
    };
} // Brings you to the relatories page after a small delay

const hubToggleSensor = document.getElementById("hub-toggle-sensor");
if (hubToggleSensor) {
    hubToggleSensor.onclick = () => {
        timenav = 30;
        let id001 = setInterval(function () {
            if (timenav >= 1) {
                timenav -= 1;
            }
            else {
                window.location.href = "hub.php";
                timenav = 30;
                clearInterval(id001);
            }
        }, 10);
    };
}

