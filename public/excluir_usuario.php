<?php
session_start();
require_once 'components/start.php';
require_once '../infra/conn.php';

redirecionarSeNaoAdmin();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = $_POST['id_excluir'];
    $sql = "DELETE FROM funcionario WHERE id = $id;";
    $db->query($sql);
    header("Location: Admin.php");
    }

?>
