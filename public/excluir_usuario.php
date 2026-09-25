<?php
session_start();

if (($_SESSION['cargo'] ?? '') !== 'admin') {
    header('Location: hub.php');
    exit();
}

include "../infra/conn.php";

$id = $_POST['id'] ?? 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id > 0) {
    $sql = "DELETE FROM funcionario WHERE id = ?";
    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: Admin.php');
exit();
