<?php
session_start();

if (($_SESSION['cargo'] ?? '') !== 'admin') {
    header('Location: hub.php');
    exit();
}

include "../infra/conn.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: Admin.php');
    exit();
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id <= 0) {
    header('Location: Admin.php');
    exit();
}

$stmt = $db->prepare("SELECT id, cargo FROM funcionario WHERE id = ?");
if (!$stmt) {
    header('Location: Admin.php');
    exit();
}

$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header('Location: Admin.php');
    exit();
}

if (($user['cargo'] ?? '') === 'admin') {
    header('Location: Admin.php');
    exit();
}

$delete = $db->prepare("DELETE FROM funcionario WHERE id = ? AND cargo = 'funcionario'");
if (!$delete) {
    header('Location: Admin.php');
    exit();
}

$delete->bind_param('i', $id);
$delete->execute();
$delete->close();

header('Location: Admin.php');
exit();
