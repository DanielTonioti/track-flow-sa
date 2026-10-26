<?php
session_start();
require_once 'components/start.php';
require_once '../infra/conn.php';

redirecionarSeNaoAdmin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = isset($_POST['id_excluir']) ? (int) $_POST['id_excluir'] : 0;
    $cancel = 0;
    $sql2 = "SELECT * FROM trem WHERE responsavel_usuario_id = $id";
    $result = mysqli_query($db, $sql2);
    while ($joshua = $result->fetch_assoc())
        {
        if (isset($joshua['id']));
        {
        $cancel = 1;
        }
        }

    if ($id <= 0) {
        header("Location: Admin.php");
        exit();
    }

    $idSessao = (int) ($_SESSION['id'] ?? 0);

    if ($cancel == 0){

    $sql = "DELETE FROM funcionario WHERE id = ?";
    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }

    if ($id === $idSessao) {
        session_unset();
        session_destroy();
        header("Location: Login.php");
        exit();
    }
    header("Location: Admin.php");
    exit();
    }
    else
    {
    header("Location: editar_usuario.php?id=" . $id . "&excluir_erro=1");
    exit();
    }
    
}

?>