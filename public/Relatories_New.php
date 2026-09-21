<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Data dos sensores</title>
</head>

<body>
    <header>

          <?php 
         
         include("components/navbar.php");
         
         ?>


    </header>

    <main class="container py-4">
        <div class="ReportFormPanel mx-auto cores-background rounded-4 p-4 p-md-5" data-background="azure-claro-fundo">
            <h1 class="text-white mb-4">Novo relatario</h1>
            <form>
                <div class="mb-3">
                    <label class="form-label text-white" for="TituloRelatório">Título</label>
                    <input class="form-control" type="text" id="TituloRelatório" placeholder="Título do relatario">
                </div>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label text-white" for="SensorRelatório">Sensor ou rota</label>
                        <select class="form-select" id="SensorRelatório">
                            <option selected>Selecione</option>
                            <option>Rota Norte</option>
                            <option>Sensor S001</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label text-white" for="PeriodoRelatório">Período</label>
                        <select class="form-select" id="PeriodoRelatório">
                            <option selected>últimos 7 dias</option>
                            <option>últimos 30 dias</option>
                            <option>Período personalizado</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a class="btn btn-outline-light" href="Relatories.php">Cancelar</a>
                    <button class="btn btn-light fw-bold" type="button">Gerar relatório</button>
                </div>
            </form>
        </div>
    </main>

    </head>

    <script src="../scripts/scriptVoltar.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>

