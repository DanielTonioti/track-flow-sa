/**
 * scripts/script_navbar.js
 * Gerenciamento do menu dropdown de perfil do usuário.
 * Suporta toggle, clique fora para fechar e tecla Escape.
 */
document.addEventListener('DOMContentLoaded', () => {
    const perfil = document.querySelector('.menu-nav .perfil');
    const menu = document.getElementById('menu_user') || document.querySelector('.menu-nav .menu');

    if (perfil && menu) {
        perfil.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('ativo');
            const expandido = menu.classList.contains('ativo');
            perfil.setAttribute('aria-expanded', expandido ? 'true' : 'false');
        });

        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && !perfil.contains(e.target)) {
                menu.classList.remove('ativo');
                perfil.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && menu.classList.contains('ativo')) {
                menu.classList.remove('ativo');
                perfil.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
