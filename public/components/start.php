<?php
if (!isset($_SESSION['usuario'])) {
    header('Location: Login.php');
    exit();
}

function redirecionarSeNaoAdmin($fallback = 'hub.php') {
    if (($_SESSION['cargo'] ?? '') === 'admin') {
        return;
    }

    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    if ($referer !== '') {
        $url = parse_url($referer);
        $host = $url['host'] ?? '';
        $path = $url['path'] ?? '';

        if ($host === $_SERVER['HTTP_HOST'] ?? '' && $path !== '') {
            $pagina = basename($path);
            if ($pagina !== 'Login.php' && $pagina !== 'logout.php' && $pagina !== '') {
                header('Location: ' . $path);
                exit();
            }
        }
    }

    header('Location: ' . $fallback);
    exit();
}
?>