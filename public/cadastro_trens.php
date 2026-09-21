<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Cadastrar Trem</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">
    <header>
        <?php

        include("components/navbar.php");

        ?>

    </header>

    <main class="CadastroTremPage">

        <h1 class="CadastroTremTitle cores-color" data-color="white">Cadastrar Trem</h1>

        <form class="CadastroTremForm" id="CadastroTremForm" novalidate>
            <div class="CadastroTremFields">
                <div class="CadastroTremField">
                    <label class="CadastroTremLabel" for="Numeracao">Numeração:</label>
                    <input class="CadastroTremInput" type="text" id="Numeracao" name="Numeracao" placeholder="Ex: N289T"
                        required>
                </div>

                <div class="CadastroTremField">
                    <label class="CadastroTremLabel" for="EstacaoOrigem">Estação de Origem:</label>
                    <input class="CadastroTremInput" type="text" id="EstacaoOrigem" name="EstacaoOrigem"
                        placeholder="Inicial" required>
                </div>

                <div class="CadastroTremField">
                    <label class="CadastroTremLabel" for="Tamanho">Tamanho:</label>
                    <input class="CadastroTremInput" type="number" id="Tamanho" name="Tamanho"
                        placeholder="Tamanho em metros" min="1" required>
                </div>
            </div>
        </form>

        <button class="CadastroTremButton" type="button">Cadastrar</button>

        <section class="CadastroTremList cores-color" data-color="white" aria-labelledby="TituloTrilhosCadastrados">
            <h2 id="TituloTrilhosCadastrados" class="CadastroTremListTitle">Trilhos cadastrados</h2>
            <div class="CadastroTremListItem" data-trilho="N289T">
                <div>
                    <strong>N289T</strong>
                    <span>Inicial - 1200 m</span>
                </div>
                <button class="CadastroTremDeleteButton" type="button" data-bs-toggle="modal"
                    data-bs-target="#ModalExcluirTrilho" data-trilho-nome="N289T">
                    Excluir
                </button>
            </div>
        </section>
    </main>

    <div class="modal fade CadastroTremModal" id="ModalExcluirTrilho" tabindex="-1"
        aria-labelledby="TituloModalExcluirTrilho" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content CadastroTremModalBox cores-background cores-color" data-background="cinza-claro"
                data-color="black">
                <h2 id="TituloModalExcluirTrilho">Certeza que deseja<br> excluir Trem:<br>
                    <span>N289T</span>.
                </h2>
                <div class="CadastroTremModalButtons">
                    <button type="button" id="ConfirmarExclusaoTrilho" data-bs-dismiss="modal">Confirmar</button>
                    <button type="button" data-bs-dismiss="modal">voltar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/scriptNavbar.js"></script>
</body>

</html>