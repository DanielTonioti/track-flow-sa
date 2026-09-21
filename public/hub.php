<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style.css">
    <title> Página Inicial </title>
</head>

<body class="cores-background " data-background="azure-escuro-fundo">

    <head>
        <header>

        <?php 
         
         include("components/navbar.php");
         
         ?>
            
               
        </header>

    </head>
    <main>

    

        <hr class="opacity-100 cores-background hub-main-hr d-flex m-0" data-background="cinza-claro">
        <section class="container-fluid px-3 py-4">
            <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                <h2 class="text-white mb-0">Relatórios em destaque</h2>
                <div class="hub-carousel-controls">
                    <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-target="#hubCarousel"
                        data-bs-slide="prev" aria-label="Gráfico anterior">&#8249;</button>
                    <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-target="#hubCarousel"
                        data-bs-slide="next" aria-label="Próximo gráfico">&#8250;</button>
                </div>
            </div>

            <div id="hubCarousel" class="carousel slide" data-bs-ride="false">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="hub-chart-panel">
                            <div class="hub-chart-header">
                                <span>Velocidade média por sensor</span>
                            </div>
                            <div id="chart-velocidade" class="hub-chart"></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="hub-chart-panel">
                            <div class="hub-chart-header">
                                <span>Temperatura média</span>
                            </div>
                            <div id="chart-temperatura" class="hub-chart"></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="hub-chart-panel">
                            <div class="hub-chart-header">
                                <span>Status dos sensores</span>
                            </div>
                            <div id="chart-status" class="hub-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Area sensores -->
        <div class="flex centralizar-tabela mt-4 cores-color" data-color="white">
            <div class="d-flex flex-column">
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor001">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRM.001
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin001">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor001">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Velocidade média:</p>
                                <p>12 km/h</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p> 56 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p> Funcionando </p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor002">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRM.002
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin002">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor002">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Velocidade média:</p>
                                <p>26 km/h</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>45 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor003">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRM.003
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin003">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor003">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Velocidade média:</p>
                                <p>14 km/h</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>24 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor004">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRM.004
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin004">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor004">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Velocidade média:</p>
                                <p>1670 km/h</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>5400 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor005">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRM.005
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin005">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor005">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Velocidade média:</p>
                                <p>0 km/h</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>12 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Não Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column">
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor006">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRLH.001
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin006">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor006">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>21 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor007">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRLH.001
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin007">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor007">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>19 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor008">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRLH.001
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin008">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor008">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>23 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor009">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRLH.001
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin009">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor009">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>17 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="border-tabela grid-element cores-background rounded-2" data-background="verde"
                    id="sensor010">
                    <div class="align-items-center d-block cores-color fw-bold" data-color="white">
                        S.TRLH.001
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta lista home page" class="Home-page-arrow"
                            id="seta-admin010">
                    </div>
                </button>
                <div class="collapse" id="lista-sensor010">
                    <div class="d-flex flex-column">
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Temperatura média:</p>
                                <p>29 C°</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="verde-escuro">
                            <div class="justify-content-center align-items-center mt-2 d-flex">
                                <p class="text-center me-1">
                                    Status médio:</p>
                                <p>Funcionando</p>
                            </div>
                        </div>
                        <div class="border-tabela grid-element cores-background justify-content-center align-items-center cores-background rounded-2 fw-bold"
                            data-background="azul-pastel">
                            <div class="justify-content-center align-items-center mt-2 d-flex cursor-pointer"
                                id="detalhes-sensor">
                                <p class="text-center me-1">
                                    Detalhes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Area botoes de modificação -->
        <div class="justify-content-center align-items-center d-flex mt-5">
            <button
                class="border-none cores-background me-4 rounded-pill cores-color px-3 fw-bold hub-main-sensor-management"
                data-background="azure-claro-fundo" data-color="white" id="AddSensor">
                <p class="mt-2">Adicionar sensor</p>
            </button>
            <button
                class="cores-background align-items-center rounded-pill justify-content-center cores-color px-3 fw-bold hub-main-sensor-management me-4"
                data-background="azure-claro-fundo" data-color="white">
                <p class="mt-2">Excluir sensor</p>
            </button>
        </div>
        
    </main>
    <footer>

    </footer>

    <script>
        const usuarioLogado = localStorage.getItem("trackflow_nome") || localStorage.getItem("trackflow_user") || "Visitante";
        const nomeFormatado = usuarioLogado.includes("@") ? usuarioLogado.split("@")[0] : usuarioLogado;
        const nomeExibicao = nomeFormatado.charAt(0).toUpperCase() + nomeFormatado.slice(1);
        const elementoUsuario = document.getElementById("usuario-logado");

        if (elementoUsuario) {
            elementoUsuario.textContent = `Olá, ${nomeExibicao}`;
        }
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', { packages: ['corechart'], language: 'pt-BR' });

        const dadosGraficos = [
            {
                id: 'chart-velocidade',
                type: 'LineChart',
                data: [
                    ['Sensor', 'Velocidade média'],
                    ['S.TRM.001', 12],
                    ['S.TRM.002', 26],
                    ['S.TRM.003', 14],
                    ['S.TRM.004', 18]
                ],
                options: {
                    title: 'Temperatura média semanal',
                    backgroundColor: 'transparent',
                    legend: { position: 'none' },
                    hAxis: { textStyle: { color: '#fff' } },
                    vAxis: { textStyle: { color: '#fff' }, minValue: 0 },
                    titleTextStyle: { color: '#fff', fontSize: 18 },
                    colors: ['#67a4ff'],
                    chartArea: { width: '80%', height: '70%' },
                    pointSize: 5
                }
            },
            {
                id: 'chart-temperatura',
                type: 'LineChart',
                data: [
                    ['Semana', 'Temperatura'],
                    ['Seg', 24],
                    ['Ter', 28],
                    ['Qua', 26],
                    ['Qui', 29],
                    ['Sex', 31],
                    ['Sáb', 27]
                ],
                options: {
                    title: 'Temperatura média semanal',
                    backgroundColor: 'transparent',
                    legend: { position: 'none' },
                    hAxis: { textStyle: { color: '#fff' } },
                    vAxis: { textStyle: { color: '#fff' }, minValue: 0 },
                    titleTextStyle: { color: '#fff', fontSize: 18 },
                    colors: ['#67a4ff'],
                    chartArea: { width: '80%', height: '70%' },
                    pointSize: 5
                }
            },
            {
                id: 'chart-status',
                type: 'PieChart',
                data: [
                    ['Status', 'Quantidade'],
                    ['Funcionando', 74],
                    ['Em alerta', 16],
                    ['Manutenção', 10]
                ],
                options: {
                    title: 'Status dos sensores',
                    backgroundColor: 'transparent',
                    legend: { textStyle: { color: '#fff' } },
                    titleTextStyle: { color: '#fff', fontSize: 18 },
                    colors: ['#00bf63', '#ff5757', '#67a4ff'],
                    chartArea: { width: '80%', height: '70%' },
                    pieSliceTextStyle: { color: '#fff' }
                }
            }
        ];

        function desenharGrafico({ id, type, data, options }) {
            const elemento = document.getElementById(id);
            if (!elemento) return;

            const tabela = google.visualization.arrayToDataTable(data);
            const chart = new google.visualization[type](elemento);
            chart.draw(tabela, options);
        }

        google.charts.setOnLoadCallback(() => {
            dadosGraficos.forEach(desenharGrafico);
            window.addEventListener('resize', () => {
                dadosGraficos.forEach(desenharGrafico);
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script src="../scripts/scriptSair.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="../scripts/scriptHub.js"></script>
    <script src="../scripts/ScriptAdicionar.js"></script>
</body>

</html>