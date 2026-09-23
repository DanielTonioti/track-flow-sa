<?php
include "../infra/conn.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    if ($id > 0) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
        }
    }
    header('Location: Admin.php');
    exit();
}
header('Location: Admin.php');
exit();