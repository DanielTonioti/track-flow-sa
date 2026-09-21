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

<body class="cores-background" data-background="azure-escuro-fundo">

    <head>
        <header>

            <?php

            include("components/navbar.php");

            ?>
        </header>

    </head>

    <main>
        <div class="admin-titulo cores-background " data-background="azure-claro-fundo">
            <p class="admin-titulo-texto cores-color" data-color="white">
                Sensores
            </p>
        </div>
        <div class="flex centralizar-tabela">
            <div class="flex">
                <div>
                    <div class="border-tabela grid-element cores-background" data-background="verde"></div>
                    <div class="border-tabela grid-element cores-background" data-background="verde"></div>
                    <div class="border-tabela grid-element cores-background" data-background="verde"></div>
                    <div class="border-tabela grid-element cores-background" data-background="verde"></div>
                    <div class="border-tabela grid-element cores-background" data-background="verde"></div>
                </div>
                <div>
                    <div class="border-tabela grid-element cores-background" data-background="verde"></div>
                    <div class="border-tabela grid-element cores-background" data-background="verde"></div>
                    <div class="border-tabela grid-element cores-background" data-background="verde"></div>
                    <div class="border-tabela grid-element cores-background" data-background="verde"></div>
                    <div class="border-tabela grid-element cores-background" data-background="verde"></div>
                </div>
            </div>
        </div>
        <button id="voltarsensor" class="btn btn-danger back-buttom"> Voltar </button>
    </main>
    <footer>

    </footer>
    <script src="../scripts/scripts-sensor-data.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>