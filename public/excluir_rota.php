<?php
session_start();
require_once '../infra/conn.php';
require_once 'components/start.php';
redirecionarSeNaoAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastro_rotas.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id <= 0) {
    header('Location: cadastro_rotas.php');
    exit;
}

$stmt = $db->prepare('DELETE FROM rota WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

header('Location: cadastro_rotas.php?sucesso=1');
exit;
