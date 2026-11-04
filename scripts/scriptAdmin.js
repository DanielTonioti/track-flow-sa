
        document.addEventListener('DOMContentLoaded', function () {
            const inputBusca = document.getElementById('busca-funcionario');
            const botoesFiltro = document.querySelectorAll('[data-role-filter]');
            const linhas = document.querySelectorAll('#tabela-funcionarios tbody tr[data-role]');
            const contador = document.getElementById('contador-usuarios');
            const vazioBox = document.getElementById('estado-busca-vazia');

            let roleAtiva = 'todos';

            function aplicarFiltros() {
                const termo = (inputBusca ? inputBusca.value : '').trim().toLowerCase();
                let visiveis = 0;

                linhas.forEach(function (tr) {
                    const roleLinha = tr.getAttribute('data-role') || '';
                    const buscaLinha = tr.getAttribute('data-search') || '';
                    const bateRole = (roleAtiva === 'todos' || roleLinha === roleAtiva);
                    const bateTermo = (termo === '' || buscaLinha.indexOf(termo) !== -1);

                    if (bateRole && bateTermo) {
                        tr.classList.remove('d-none');
                        visiveis++;
                    } else {
                        tr.classList.add('d-none');
                    }
                });

                if (contador) {
                    contador.textContent = visiveis + (visiveis === 1 ? ' funcionário' : ' funcionários');
                }
                if (vazioBox && linhas.length > 0) {
                    vazioBox.classList.toggle('d-none', visiveis > 0);
                }
            }

            if (inputBusca) {
                inputBusca.addEventListener('input', aplicarFiltros);
            }

            botoesFiltro.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    roleAtiva = btn.getAttribute('data-role-filter') || 'todos';
                    botoesFiltro.forEach(function (b) {
                        const ativo = (b === btn);
                        b.classList.toggle('active', ativo);
                        b.setAttribute('aria-pressed', ativo ? 'true' : 'false');
                    });
                    aplicarFiltros();
                });
            });
        });