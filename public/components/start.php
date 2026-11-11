<?php
if (!isset($_SESSION['usuario'])) {
    header('Location: Login.php');
    exit();
}


require_once __DIR__ . '/../../infra/conn.php';


$idSessao = isset($_SESSION['id']) ? (int) $_SESSION['id'] : 0;


if ($idSessao <= 0) {
    session_unset();
    session_destroy();
    header('Location: Login.php');
    exit();
}


$stmtSessao = $db->prepare("SELECT id, nome, email, rua, cidade, bairro, uf, telefone, cargo, avatar FROM funcionario WHERE id = ?");
if (!$stmtSessao) {
    session_unset();
    session_destroy();
    header('Location: Login.php');
    exit();
}


$stmtSessao->bind_param('i', $idSessao);
$stmtSessao->execute();
$usuarioSessao = $stmtSessao->get_result()->fetch_assoc();
$stmtSessao->close();


if (!$usuarioSessao) {
    session_unset();
    session_destroy();
    header('Location: Login.php');
    exit();
}


$_SESSION['id'] = (int) $usuarioSessao['id'];
$_SESSION['usuario'] = $usuarioSessao['nome'];
$_SESSION['email'] = $usuarioSessao['email'];
$_SESSION['rua'] = $usuarioSessao['rua'];
$_SESSION['cidade'] = $usuarioSessao['cidade'];
$_SESSION['bairro'] = $usuarioSessao['bairro'];
$_SESSION['uf'] = $usuarioSessao['uf'];
$_SESSION['telefone'] = $usuarioSessao['telefone'];
$_SESSION['cargo'] = $usuarioSessao['cargo'];
$_SESSION['avatar'] = $usuarioSessao['avatar'];


function redirecionarSeNaoAdmin($fallback = 'hub.php')
{
    if (($_SESSION['cargo'] ?? '') === 'admin') {
        return;
    }


    $paginasPublicasAutenticadas = [
        'hub.php',
        'Admin.php',
        'trens_trilhos.php',
        'Sensor_Data.php',
        'Relatories.php',
        'Relatories_New.php',
        'Relatorie_Info.php',
        'Usuario_Info.php'
    ];


    $referer = $_SERVER['HTTP_REFERER'] ?? '';


    if ($referer !== '') {
        $url = parse_url($referer);
        $host = $url['host'] ?? '';
        $path = $url['path'] ?? '';


        if ($host === ($_SERVER['HTTP_HOST'] ?? '') && $path !== '') {
            $pagina = basename($path);
            if (in_array($pagina, $paginasPublicasAutenticadas, true)) {
                header('Location: ' . $pagina);
                exit();
            }
        }
    }


    header('Location: ' . $fallback);
    exit();
}
?>