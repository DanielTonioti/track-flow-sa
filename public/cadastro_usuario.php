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
        <form id="CadastrarSensor">
            <div class="blockcentro titulo-Sensor">
                <div class="cores-background p-2 rounded-4" data-background="azure-claro-fundo">
                    <h2 class="titulo-Sensor">Cadastrar Usuário</h2>
                </div>
                <br>
                <div id="valortipo" class="cores-background p-4 w-25 rounded-4" data-background="azure-claro-fundo">
                    <br>
                    <div class="column-sensor" id="tipos">
                        <label for="nome"> Nome: </label>
                        <input type="text" name="nome" required>
                        <label for="nome"> E-mail: </label>
                        <input type="email" name="email" required>
                        <label for="senha"> Senha: </label>
                        <input type="password" name="password">
                        <label for="senha"> Confirmar senha: </label>
                        <input type="password" name="passwordconfirm">
                        <label for="acesso"> Nível de acesso: </label>
                        <div>
                        <input type="radio" name="acesso" id="funcionario" value=true required> Funcionário
                        <input type="radio" name="acesso" id="administrador" value=false> Administrador
                        </div>
                    </div>
                </div>
                <br>
                <input type="submit" value="Cadastrar" class="border-none-buttom cores-background titulo-Sensor p-2 rounded-3"
                data-background="azure-claro-fundo"></button>
            </div>
        </form>


        <script src="../scripts/"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>

        <button id="voltaradmin" class="btn btn-danger back-buttom"> Voltar </button>
    </main>
    <footer>

    </footer>

    <script src="../scripts/scriptvoltarAdmin.js"></script>
    <script src="../scripts/scriptSensor.js"></script>
    <script src="../scripts/scriptAdmin.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>