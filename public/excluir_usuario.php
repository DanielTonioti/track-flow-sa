<?php
session_start();
require_once 'components/csrf.php';
validarTokenCSRF();
require_once 'components/start.php';
require_once '../infra/conn.php';

redirecionarSeNaoAdmin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = isset($_POST['id_excluir']) ? (int) $_POST['id_excluir'] : 0;
    if ($id <= 0) {
        header("Location: Admin.php");
        exit();
    }

    $cancel = 0;
    $stmtTrem = $db->prepare("SELECT id FROM trem WHERE responsavel_usuario_id = ? LIMIT 1");
    $stmtTrem->bind_param('i', $id);
    $stmtTrem->execute();
    if ($stmtTrem->get_result()->num_rows > 0) {
        $cancel = 1;
    }
    $stmtTrem->close();

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