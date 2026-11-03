<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastro_trens.php');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id <= 0) {
    header('Location: cadastro_trens.php');
    exit;
}

$stmtRota = $db->prepare('SELECT id FROM rota WHERE trem_id = ? LIMIT 1');
$stmtRota->bind_param('i', $id);
$stmtRota->execute();
$temRota = $stmtRota->get_result()->num_rows > 0;
$stmtRota->close();

if ($temRota) {
    header('Location: cadastro_trens.php?excluir_erro=1');
    exit;
}

$stmtSensor = $db->prepare('SELECT id FROM sensor WHERE trem_id = ? LIMIT 1');
$stmtSensor->bind_param('i', $id);
$stmtSensor->execute();
$temSensor = $stmtSensor->get_result()->num_rows > 0;
$stmtSensor->close();

if ($temSensor) {
    header('Location: cadastro_trens.php?excluir_erro=sensor');
    exit;
}

$stmt = $db->prepare('DELETE FROM trem WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

header('Location: cadastro_trens.php?excluido=1');
exit;
