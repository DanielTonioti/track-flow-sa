<?php
session_start();
require_once 'components/csrf.php';
validarTokenCSRF();
require_once 'components/start.php';

redirecionarSeNaoAdmin();

include "../infra/conn.php";
if (!isset($conn) && isset($db)) {
    $conn = $db;
}

$id = (int) ($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$localizacao = trim($_POST['localizacao'] ?? '');

if ($id <= 0 || $nome === '' || $localizacao === '') {
    header('Location: cadastro_linhas.php');
    exit();
}

$sql = "UPDATE trilho SET nome = ?, localizacao = ? WHERE id = ?";
if ($stmt = $db->prepare($sql)) {
    $stmt->bind_param('ssi', $nome, $localizacao, $id);
    $stmt->execute();
    $stmt->close();
}

header('Location: cadastro_linhas.php?atualizado=1');
exit();
