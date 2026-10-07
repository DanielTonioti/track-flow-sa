<?php
session_start();
require_once 'components/start.php';
require_once '../infra/conn.php';

redirecionarSeNaoAdmin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id_excluir']);

    if ($id <= 0) {
        header("Location: Admin.php");
        exit();
    }

    $idSessao = $_SESSION['id'];

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

?>