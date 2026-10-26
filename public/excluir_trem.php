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
$cancel = 0;
    $sql2 = "SELECT * FROM rota WHERE trem_id = $id";
    $result = mysqli_query($db, $sql2);
    while ($joshua = $result->fetch_assoc())
        {
        if (isset($joshua['id']));
        {
        $cancel = 1;
        }
        }

if ($id <= 0) {
    header('Location: cadastro_trens.php');
    exit;
}

if ($cancel == 0)
{
$stmt = $db->prepare('DELETE FROM trem WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();
header('Location: cadastro_trens.php');
exit;
}
else
{
header('Location: cadastro_trens.php?excluir_erro=1');
exit;  
}
