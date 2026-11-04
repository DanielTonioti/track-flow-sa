document.addEventListener('DOMContentLoaded', function () {
    // 1. Pesquisa / Filtro na tabela de Trens e Rotas
    const buscaInput = document.getElementById('busca-frota-rotas');
    if (buscaInput) {
        buscaInput.addEventListener('input', function () {
            const termo = buscaInput.value.trim().toLowerCase();
            document.querySelectorAll('tr[data-filter-row]').forEach(function (tr) {
                const alvo = tr.getAttribute('data-filter-row') || '';
                const bate = (termo === '' || alvo.indexOf(termo) !== -1);
                if (bate) {
                    tr.classList.remove('d-none-filter');
                    tr.removeAttribute('data-filter-hidden');
                } else {
                    tr.classList.add('d-none-filter');
                    tr.setAttribute('data-filter-hidden', 'true');
                }
            });

            const tabTrens = document.getElementById('tabela-trens-overview');
            if (tabTrens && typeof tabTrens.refreshPagination === 'function') {
                tabTrens.refreshPagination(true);
            }
            const tabRotas = document.getElementById('tabela-rotas-overview');
            if (tabRotas && typeof tabRotas.refreshPagination === 'function') {
                tabRotas.refreshPagination(true);
            }
        });
    }

    // 2. Modal de Consulta de Ficha
    document.querySelectorAll('.btn-consultar-ficha').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const elTipo = document.getElementById('fichaModalTipo');
            const elCodigo = document.getElementById('fichaModalCodigo');
            const elNome = document.getElementById('fichaModalNome');
            const elC1Label = document.getElementById('fichaC1Label');
            const elC1Val = document.getElementById('fichaC1Val');
            const elC2Label = document.getElementById('fichaC2Label');
            const elC2Val = document.getElementById('fichaC2Val');
            const elC3Label = document.getElementById('fichaC3Label');
            const elC3Val = document.getElementById('fichaC3Val');
            const elC4Label = document.getElementById('fichaC4Label');
            const elC4Val = document.getElementById('fichaC4Val');
            const elDesc = document.getElementById('fichaDesc');
            const link = document.getElementById('fichaEditLink');

            if (elTipo) elTipo.textContent = btn.getAttribute('data-ficha-tipo') || '';
            if (elCodigo) elCodigo.textContent = btn.getAttribute('data-ficha-codigo') || '';
            if (elNome) elNome.textContent = btn.getAttribute('data-ficha-titulo') || '';
            if (elC1Label) elC1Label.textContent = btn.getAttribute('data-ficha-c1-label') || '';
            if (elC1Val) elC1Val.textContent = btn.getAttribute('data-ficha-c1-val') || '';
            if (elC2Label) elC2Label.textContent = btn.getAttribute('data-ficha-c2-label') || '';
            if (elC2Val) elC2Val.textContent = btn.getAttribute('data-ficha-c2-val') || '';
            if (elC3Label) elC3Label.textContent = btn.getAttribute('data-ficha-c3-label') || '';
            if (elC3Val) elC3Val.textContent = btn.getAttribute('data-ficha-c3-val') || '';
            if (elC4Label) elC4Label.textContent = btn.getAttribute('data-ficha-c4-label') || '';
            if (elC4Val) elC4Val.textContent = btn.getAttribute('data-ficha-c4-val') || '';
            if (elDesc) elDesc.textContent = btn.getAttribute('data-ficha-desc') || '';
            if (link) {
                link.setAttribute('href', btn.getAttribute('data-ficha-edit-url') || '#');
            }
        });
    });
});

