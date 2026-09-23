<?php
include "../infra/conn.php";
if (!isset($conn) && isset($db)) {
    $conn = $db;
}

$id = $_POST['id'];
$nome = $_POST['nome'];
$localizacao = $_POST['localizacao'];

if ($id <= 0) {
    header('Location: cadastro_linhas.php');
    exit();
}

$sql = "UPDATE trilho SET nome = ?, localizacao = ? WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('ssi', $nome, $localizacao, $id);
    $stmt->execute();
    $stmt->close();
}

header('Location: trens_trilhos.php');
exit();
