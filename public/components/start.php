<?php
if (!isset($_SESSION['usuario'])) {
    header('Location: Login.php');
    exit();
}
?>