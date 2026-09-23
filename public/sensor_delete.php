<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Excluição de Sensores</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">
    <header>
        <?php

        include("components/navbar.php");

        ?>
    </header>
    <main>
        <div class="flex justify-content-center">
            <div class="admin-titulo d-flex justify-content-center align-items-center cores-background rounded-pill"
                data-background="azure-claro-fundo">
                <p class="admin-titulo-texto cores-color fw-bold mx-3" data-color="white">
                    Sensores
                </p>
            </div>
        </div>
        <div>
            <div class="text-center text-white p-3">
                <button id="botao-admin" class="btn text-white fw-bold" type="button">
                    <div class="d-flex align-items-center">
                        Sensores trilhos
                        <img src="../assets/icons/seta-para-baixo.png" alt="seta-para-baixo do admin"
                            class="users-page-arrow" id="seta-admin">
                    </div>
                </button>
                <div class="collapse" id="lista-adm">
                    <div class="d-flex flex-column mt-2">
                        <div>
                            <div class="flex centralizar-tabela">
                                <p class="border-tabela admin-tabela tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    
                                </p>
                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    
                                </p>
                                <p class="border-tabela admin-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    
                                </p>
                                <button
                                    class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-bs-toggle="modal" data-bs-target="#ModalDeletarSensor">
                                    Deletar
                                </button>
                            </div>
                        </div>
                        <div>
                            <div class="flex centralizar-tabela">
                                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    
                                </p>
                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                    
                                </p>
                                <p class="border-tabela admin-tabela px-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                 
                                </p>
                                <button
                                    class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-bs-toggle="modal" data-bs-target="#ModalDeletarSensor">
                                    Deletar
                                </button>
                            </div>
                        </div>
                        <div>
                            <div class="flex centralizar-tabela">
                                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    
                                </p>
                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                  
                                </p>
                                <p class="border-tabela admin-tabela px-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                   
                                </p>
                                <button
                                    class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-bs-toggle="modal" data-bs-target="#ModalDeletarSensor">
                                    Deletar
                                </button>
                            </div>
                        </div>
                        <div>
                            <div class="flex centralizar-tabela">
                                <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    
                                </p>
                                <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                    data-background="azure-claro-fundo">
                                   
                                </p>
                                <p class="border-tabela admin-tabela px-1 w-auto cores-color cores-background"
                                    data-color="white" data-background="azure-claro-fundo">
                                    
                                </p>
                                <button
                                    class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                    data-color="white" data-bs-toggle="modal" data-bs-target="#ModalDeletarSensor">
                                    Deletar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center cores-color p-3" data-color="white">
            <button id="botao-func" class="btn text-white fw-bold" type="button">
                Sensores trens
                <img src="../assets/icons/seta-para-baixo.png" alt="seta-para-baixo do admin" class="users-page-arrow"
                    id="seta-func">
            </button>
            <div class="collapse" id="lista-func">
                <div class="d-flex flex-column mt-2">
                    <div>
                        <div class="flex centralizar-tabela">
                            <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                                
                            </p>
                            <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                data-background="azure-claro-fundo">
                              
                            </p>
                            <p class="border-tabela admin-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                               
                            </p>
                            <button class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-bs-toggle="modal" data-bs-target="#ModalDeletarSensor">
                                Deletar
                            </button>
                        </div>
                    </div>
                    <div>
                        <div class="flex centralizar-tabela">
                            <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                               
                            </p>
                            <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                data-background="azure-claro-fundo">
                               
                            </p>
                            <p class="border-tabela admin-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                               
                            </p>
                            <button class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-bs-toggle="modal" data-bs-target="#ModalDeletarSensor">
                                Deletar
                            </button>
                        </div>
                    </div>
                    <div>
                        <div class="flex centralizar-tabela">
                            <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                                
                            </p>
                            <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                data-background="azure-claro-fundo">
                               
                            </p>
                            <p class="border-tabela admin-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                              
                            </p>
                            <button class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-bs-toggle="modal" data-bs-target="#ModalDeletarSensor">
                                Deletar
                            </button>
                        </div>
                    </div>
                    <div>
                        <div class="flex centralizar-tabela">
                            <p class="border-tabela admin-tabela  tabela-texto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                                
                            </p>
                            <p class="border-tabela admin-tabela cores-color cores-background" data-color="white"
                                data-background="azure-claro-fundo">
                               
                            </p>
                            <p class="border-tabela admin-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-background="azure-claro-fundo">
                               
                            </p>
                            <button class=" admin-tabela-button border-tabela p-1 w-auto cores-color cores-background"
                                data-color="white" data-bs-toggle="modal" data-bs-target="#ModalDeletarSensor">
                                Deletar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        </div>
        <button id="voltarhub" class="btn btn-danger back-buttom"> Voltar </button>
    </main>
    <div class="modal fade CadastroTremModal" id="ModalDeletarSensor" tabindex="-1"
        aria-labelledby="TituloModalDeletarSensor" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content CadastroTremModalBox cores-background cores-color" data-background="cinza-claro"
                data-color="black">
                <h2 id="TituloModalDeletarSensor">Certeza que deseja<br> excluir Sensor:<br>
                    <span>???</span>.
                </h2>
                <div class="CadastroTremModalButtons">
                    <button type="button" id="ConfirmarExclusaoSensor" data-bs-dismiss="modal">Confirmar</button>
                    <button type="button" data-bs-dismiss="modal">voltar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Hidden form to submit sensor deletion to server -->
    <form id="deleteSensorForm" action="excluir_sensor.php" method="POST" style="display:none;">
        <input type="hidden" name="id" id="deleteSensorId" value="">
    </form>
    <footer>

    </footer>





    <script src="../scripts/scriptsensorpage.js"></script>
    <script src="../scripts/scriptVoltar.js"></script>
    <script>
        // wire modal confirm to submit hidden delete form
        (function(){
            var confirmBtn = document.getElementById('ConfirmarExclusaoSensor');
            var deleteForm = document.getElementById('deleteSensorForm');
            var deleteIdInput = document.getElementById('deleteSensorId');

            // when a delete button opens the modal, it should set data-sensor-id on the button
            document.querySelectorAll('[data-bs-target="#ModalDeletarSensor"]').forEach(function(btn){
                btn.addEventListener('click', function(e){
                    var sid = btn.getAttribute('data-sensor-id') || '';
                    deleteIdInput.value = sid;
                });
            });

            if (confirmBtn) {
                confirmBtn.addEventListener('click', function(){
                    if (deleteForm) deleteForm.submit();
                });
            }
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>