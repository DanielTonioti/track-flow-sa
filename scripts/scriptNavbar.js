const perfil = document.querySelector('.perfil');
const menu = document.querySelector('.menu');

perfil.onclick = () => {
    menu.classList.toggle('ativo');
}