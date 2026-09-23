document.getElementById("form-login").addEventListener("submit", function (e) {

    let usuario = document.getElementById("usuario-email").value.trim();
    let senha = document.getElementById("usuario-senha").value;

    if (usuario === "" || !usuario.includes("@")) {
        e.preventDefault();
        alert("Informe um email válido.");
        return;
    }

    if (senha.length < 4) {
        e.preventDefault();
        alert("A senha deve ter pelo menos 4 caracteres.");
        return;
    }

});
