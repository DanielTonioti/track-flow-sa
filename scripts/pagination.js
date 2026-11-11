(function () {
    'use strict';

    function initTablePagination(table, options) {
        if (!table || table.dataset.paginationInitialized) return;
        table.dataset.paginationInitialized = 'true';

        const perPage = (options && options.perPage) || parseInt(table.dataset.perPage, 10) || 10;
        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        // Container onde colocaremos a barra de paginação (após o table-responsive ou após a table)
        const wrapper = table.closest('.table-responsive') || table;
        
        const navContainer = document.createElement('nav');
        navContainer.className = 'ops-pagination-wrapper d-flex flex-wrap align-items-center justify-content-between gap-2 px-3 py-2 border-top';
        navContainer.style.borderColor = 'rgba(255, 255, 255, 0.08)';
        navContainer.setAttribute('aria-label', 'Navegação de páginas da tabela');

        const infoSpan = document.createElement('span');
        infoSpan.className = 'small text-steel';
        infoSpan.style.color = '#b8c7d9';

        const ul = document.createElement('ul');
        ul.className = 'pagination pagination-sm mb-0 flex-wrap gap-1';

        navContainer.appendChild(infoSpan);
        navContainer.appendChild(ul);
        wrapper.insertAdjacentElement('afterend', navContainer);

        let currentPage = 1;

        function isRowFilteredOut(tr) {
            if (tr.querySelector('.ops-empty-state')) return true;
            if (tr.classList.contains('d-none-filter')) return true;
            if (tr.getAttribute('data-filter-hidden') === 'true') return true;
            return false;
        }

        function getFilteredRows() {
            const rows = Array.from(tbody.querySelectorAll('tr'));
            return rows.filter(function (tr) {
                return !isRowFilteredOut(tr);
            });
        }

        function render() {
            const filteredRows = getFilteredRows();
            const totalItems = filteredRows.length;
            const totalPages = Math.ceil(totalItems / perPage) || 1;

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }
            if (currentPage < 1) {
                currentPage = 1;
            }

            const startIdx = (currentPage - 1) * perPage;
            const endIdx = startIdx + perPage;

            // Percorre todas as linhas reais da tabela
            const allRows = Array.from(tbody.querySelectorAll('tr'));
            allRows.forEach(function (tr) {
                if (tr.querySelector('.ops-empty-state')) return;

                if (isRowFilteredOut(tr)) {
                    // Linha excluída pelo filtro de busca/cargo
                    tr.style.display = 'none';
                    return;
                }

                // Linha válida: verificar se está na página atual
                const itemIndex = filteredRows.indexOf(tr);
                if (itemIndex >= startIdx && itemIndex < endIdx) {
                    tr.style.display = '';
                } else {
                    tr.style.display = 'none';
                }
            });

            if (totalItems <= perPage && totalPages <= 1) {
                navContainer.style.display = totalItems === 0 ? 'none' : 'flex';
                ul.innerHTML = '';
                if (totalItems > 0) {
                    infoSpan.textContent = 'Exibindo ' + totalItems + ' item(ns)';
                } else {
                    infoSpan.textContent = '';
                }
                return;
            }

            navContainer.style.display = 'flex';
            const itemFrom = totalItems === 0 ? 0 : startIdx + 1;
            const itemTo = Math.min(endIdx, totalItems);
            infoSpan.textContent = 'Exibindo ' + itemFrom + '–' + itemTo + ' de ' + totalItems;

            ul.innerHTML = '';

            function createPageItem(label, targetPage, disabled, active, ariaLabel) {
                const li = document.createElement('li');
                li.className = 'page-item' + (disabled ? ' disabled' : '') + (active ? ' active' : '');
                
                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.innerHTML = label;
                if (ariaLabel) a.setAttribute('aria-label', ariaLabel);
                if (active) a.setAttribute('aria-current', 'page');

                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (!disabled && !active) {
                        currentPage = targetPage;
                        render();
                    }
                });

                li.appendChild(a);
                return li;
            }

            // Anterior
            ul.appendChild(createPageItem('&laquo;', currentPage - 1, currentPage === 1, false, 'Página anterior'));

            let startP = Math.max(1, currentPage - 2);
            let endP = Math.min(totalPages, currentPage + 2);

            if (startP > 1) {
                ul.appendChild(createPageItem('1', 1, false, currentPage === 1));
                if (startP > 2) {
                    const liDots = document.createElement('li');
                    liDots.className = 'page-item disabled';
                    liDots.innerHTML = '<span class="page-link">…</span>';
                    ul.appendChild(liDots);
                }
            }

            for (let p = startP; p <= endP; p++) {
                ul.appendChild(createPageItem(p.toString(), p, false, p === currentPage));
            }

            if (endP < totalPages) {
                if (endP < totalPages - 1) {
                    const liDots = document.createElement('li');
                    liDots.className = 'page-item disabled';
                    liDots.innerHTML = '<span class="page-link">…</span>';
                    ul.appendChild(liDots);
                }
                ul.appendChild(createPageItem(totalPages.toString(), totalPages, false, currentPage === totalPages));
            }

            // Próximo
            ul.appendChild(createPageItem('&raquo;', currentPage + 1, currentPage === totalPages, false, 'Próxima página'));
        }

        table.refreshPagination = function (resetToFirstPage) {
            if (resetToFirstPage) {
                currentPage = 1;
            }
            render();
        };

        render();
    }

    window.initTablePagination = initTablePagination;

    document.addEventListener('DOMContentLoaded', function () {
        const tables = document.querySelectorAll('table.ops-table');
        tables.forEach(function (t) {
            initTablePagination(t, { perPage: 10 });
        });
    });
})();
