<?php
session_start();

if (($_SESSION['cargo'] ?? '') !== 'admin') {
    header("Location: Login.php");
    exit();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include "../infra/conn.php";

$id = $_GET['id'];

if ($id <= 0) {
    header("Location: Sensor_Manage.php");
    exit();
}

try {
    $sql = "SELECT id FROM dados_sensor WHERE sensor_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    $possuiDados = $stmt->num_rows > 0;
    $stmt->close();

    if ($possuiDados) {
        $_SESSION['mensagem'] =
            "Não é possível excluir um sensor com dados registrados.";
    } else {
        $sql = "DELETE FROM sensor WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['mensagem'] = "Sensor excluído com sucesso.";
        } else {
            $_SESSION['mensagem'] = "Sensor não encontrado.";
        }

        $stmt->close();
    }
} catch (mysqli_sql_exception $erro) {
    if ($erro->getCode() == 1451) {
        $_SESSION['mensagem'] =
            "Não é possível excluir um sensor com registros vinculados.";
    } else {
        $_SESSION['mensagem'] =
            "Erro ao acessar o banco. O sensor não foi excluído.";
    }
}

header("Location: Sensor_Manage.php");
exit();
