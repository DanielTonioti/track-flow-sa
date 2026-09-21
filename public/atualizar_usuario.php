<?php
include "../infra/conn.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $nome = $_POST['NomeUpdate'];
    $email = $_POST['EmailUpdate'];
    $senha = $_POST['PasswordUpdateRegular'];
    $acesso = (isset($_POST['acesso']) && $_POST['acesso'] === '1') ? 1 : 0;

    if ($id > 0) {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ?, acesso = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('sssis', $nome, $email, $senha, $acesso, $id);
            $stmt->execute();
            $stmt->close();
        }
    }
    header('Location: Usuario_Info.php?id=' . $id);
    exit();
}
header('Location: Admin.php');
exit();
?>