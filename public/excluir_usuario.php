<?php
session_start();

if (($_SESSION['cargo'] ?? '') !== 'admin') {
    header('Location: hub.php');
    exit();
}

include "../infra/conn.php";

$id = $_GET['id'] ?? 0;

if ($id <= 0) {
    header('Location: Admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sql = "DELETE FROM funcionario WHERE id = ?";

    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }

    header('Location: Admin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>exclusão</title>
</head>

<body>
    <main>
        <div class="container min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <div class="cores-background p-2 rounded-4 w-100 text-center" data-background="azure-claro-fundo">
                <h2 class="titulo-Sensor m-0">
                    Confirmar exclusão
                </h2>
            </div>
            <br>
            <div class="cores-background p-4 rounded-4 text-center" data-background="azure-claro-fundo"
                style="width: 25rem;">
                <p class="titulo-Sensor fs-5 mb-4">
                    Tem certeza que deseja excluir este usuário?
                </p>
                <form method="POST">
                    <button type="submit" class="btn btn-danger px-4 me-2">
                        Sim, excluir
                    </button>
                    <a href="Admin.php" class="btn btn-secondary px-4">
                        Cancelar
                    </a>
                </form>
            </div>
        </div>
    </main>
</body>

</html>