let contas = (("marlon@gmail.com", "1234"),
    ("susane@gmail.com", "1234"),
    ("frederico@gmail.com", "1234")
); // Small test at pre-inserted accounts
let cancel = 0; // Cancels login

document.getElementById("form-login").addEventListener("submit", function (e) {
    e.preventDefault();

    cancel = 0;
    let usuario = document.getElementById("usuario-email").value;
    let senha = document.getElementById("usuario-senha").value;

    if (usuario == null) {
        cancel = 1;
    }
    if (!usuario.includes("@")) {
        cancel = 1;
    }
    if (senha.length < 4) {
        cancel = 1;
    }
    if (cancel == 1) {
        alert("Dados Incorretos");
    }
    if (cancel == 0) {
        localStorage.setItem(usuario, senha);
        window.location.href = "../public/hub.html";
    }

}) // This will allow anyone to log-in, only checking requirements inside the input. This will be connected to the database later.

// Login can be bypassed by typing window.location.href = "../public/hub.html"; in the console.