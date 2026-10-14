<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastro_trens.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id <= 0) {
    header('Location: cadastro_trens.php');
    exit;
}

$checkRota = $db->prepare('SELECT id FROM rota WHERE trem_id = ? LIMIT 1');
$checkRota->bind_param('i', $id);
$checkRota->execute();
$possuiRota = $checkRota->get_result()->fetch_assoc();
$checkRota->close();

if ($possuiRota) {
    header('Location: cadastro_trens.php?erro=' . urlencode('Não é possível excluir este trem porque ele está vinculado a uma rota.')); 
    exit;
}

$stmt = $db->prepare('DELETE FROM trem WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

header('Location: cadastro_trens.php?sucesso=1');
exit;
