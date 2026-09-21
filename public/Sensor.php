<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Sensor</title>
</head>

<body class="cores-background " data-background="azure-escuro-fundo" class=" cores-background "
    data-background="azure-escuro-fundo">
    <header>

         <?php 
         
         include("components/navbar.php");
         
         ?>


    </header>

    <main>
        <form id="CadastrarSensores">
            <div class="blockcentro titulo-Sensor">
                <div class="cores-background p-2 rounded-4" data-background="azure-claro-fundo">
                    <h2 class="titulo-Sensor">Cadastrar Sensor</h2>
                </div>
                <br>
                <div id="valortipo" class="cores-background p-4 w-25 rounded-4" data-background="azure-claro-fundo">
                    <div class="center-sensor">
                        <input type="radio" name="tipo" id="trem" value=true required> Trem
                        <input type="radio" name="tipo" id="trilho" value=false> Trilho
                    </div>
                    <br>
                    <div class="column-sensor" id="tipos">
                        <label for="tipo_dado">Tipo de Dado</label>
                        <select name="tipo_dado" id="tipo_dado" required>
                            <option value="" disabled selected>Selecione</option>
                            <option value="Velocidade">Velocidade</option>
                            <option value="Temperatura">Temperatura</option>
                            <option value="Falha">Falha</option>
                        </select>
                        <br>
                        <div class="hiden" id="tout">
                            <label for="trem-vinculado">Trem vinculado</label>
                            <br>
                            <input class="w-100" type="text" id="tremvinculado" placeholder="Ex: S.TRM.1" required>
                        </div>

                        <div class="hiden" id="localisation">
                            <label for="loc-trilho">Localização</label>
                            <br>
                            <input class="w-100" type="text" id="loc-trilho" placeholder="Joinville" required>
                        </div>

                    </div>
                </div>
                <br>
                <button class="border-none-buttom cores-background titulo-Sensor p-2 rounded-3"
                    data-background="azure-claro-fundo">Cadastrar</button>
            </div>
        </form>


        <script src="../scripts/"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>

        <button id="voltarhub" class="btn btn-danger back-buttom"> Voltar </button>
    </main>
    <footer>

    </footer>
    <script src="../scripts/scriptVoltar.js"></script> 
    <script src="../scripts/scriptSensor.js"></script>
    <script src="../scripts/scriptAdmin.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>

