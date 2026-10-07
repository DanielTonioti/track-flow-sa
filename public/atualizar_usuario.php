<?php
session_start();
require_once 'components/start.php';
include "../infra/conn.php";
redirecionarSeNaoAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $action = $_POST['action'] ?? '';

    if ($id > 0) {
        if ($action === 'change_access') {
            $acesso = $_POST['acesso'] ?? '';
            if ($acesso === 'admin' || $acesso === 'funcionario') {
                $sql = "UPDATE funcionario SET cargo = ? WHERE id = ?";
                if ($stmt = $db->prepare($sql)) {
                    $stmt->bind_param('si', $acesso, $id);
                    $stmt->execute();
                    $stmt->close();
                }

                if ($_SESSION['id'] === $id) {
                    $_SESSION['cargo'] = $acesso;
                    if ($acesso !== 'admin') {
                        header('Location: hub.php');
                        exit();
                    }
                }
            }
            header('Location: Usuario_Info.php?id=' . $id);
            exit();
        }

        $nome = trim($_POST['NomeUpdate'] ?? '');
        $email = trim($_POST['EmailUpdate'] ?? '');
        $senha = $_POST['PasswordUpdateRegular'] ?? '';
        $telefone = trim($_POST['TelefoneUpdate'] ?? '');
        $acesso = $_POST['acesso'] ?? '';

        if ($nome !== '' && $email !== '' && ($acesso === 'admin' || $acesso === 'funcionario')) {
            if ($senha !== '') {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $sql = "UPDATE funcionario SET nome = ?, email = ?, telefone = ?, cargo = ?, senha = ? WHERE id = ?";
                if ($stmt = $db->prepare($sql)) {
                    $stmt->bind_param('sssssi', $nome, $email, $telefone, $acesso, $senhaHash, $id);
                    $stmt->execute();
                    $stmt->close();
                }
            } else {
                $sql = "UPDATE funcionario SET nome = ?, email = ?, telefone = ?, cargo = ? WHERE id = ?";
                if ($stmt = $db->prepare($sql)) {
                    $stmt->bind_param('ssssi', $nome, $email, $telefone, $acesso, $id);
                    $stmt->execute();
                    $stmt->close();
                }
            }

            if ($_SESSION['id'] === $id) {
                $_SESSION['usuario'] = $nome;
                $_SESSION['email'] = $email;
                $_SESSION['telefone'] = $telefone;
                $_SESSION['cargo'] = $acesso;

                if ($acesso !== 'admin') {
                    header('Location: hub.php');
                    exit();
                }
            }
        }
    }
    header('Location: Usuario_Info.php?id=' . $id);
    exit();
}
header('Location: Admin.php');
exit();
?>