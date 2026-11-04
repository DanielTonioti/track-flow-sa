<?php
function gerarTokenCSRF(): string
{
    if (
        empty($_SESSION['csrf_token']) ||
        !is_string($_SESSION['csrf_token'])
    ) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}
function validarTokenCSRF(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $tokenRecebido = $_POST['csrf_token'] ?? '';
    $tokenSessao = $_SESSION['csrf_token'] ?? '';

    if (
        !is_string($tokenRecebido) ||
        !is_string($tokenSessao) ||
        $tokenRecebido === '' ||
        $tokenSessao === '' ||
        !hash_equals($tokenSessao, $tokenRecebido)
    ) {
        require __DIR__ . '/erro_csrf.php';
    }
}
