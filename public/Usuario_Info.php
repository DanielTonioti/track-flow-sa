<?php
session_start();
require_once "../infra/conn.php";
require_once "components/start.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$user = null;

if ($id !== false && $id > 0) {
    $sql = "SELECT * FROM funcionario WHERE id = ?";
    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
    }
}

$avatarPadrao = "../assets/icons/User.png";
$avatar = $avatarPadrao;

// Esta navbar está em public/components/, então a raiz fica 2 níveis acima.
$raizProjeto = realpath(__DIR__ . "/../..");
$pastaAssets = realpath(__DIR__ . "/../../assets");

if (!empty($_SESSION["avatar"]) && $raizProjeto !== false && $pastaAssets !== false) {
    $valorAvatar = str_replace("\\", "/", trim((string) $_SESSION["avatar"]));
    $caminhoRelativo = preg_replace('#^(?:\.\./)+#', '', $valorAvatar);
    $caminhoRelativo = preg_replace('#^Assets/#i', 'assets/', $caminhoRelativo);
    $candidatosAvatar = [];

    if (preg_match('#^(?:[A-Za-z]:/|/)#', $valorAvatar)) {
        $candidatosAvatar[] = $valorAvatar;
    } elseif (strpos($caminhoRelativo, 'assets/') === 0) {
        $candidatosAvatar[] = $raizProjeto . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $caminhoRelativo);
    } elseif (strpos($caminhoRelativo, '/') === false && $caminhoRelativo !== '') {
        $candidatosAvatar[] = $pastaAssets . DIRECTORY_SEPARATOR . 'images'
            . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $caminhoRelativo;
    } elseif ($caminhoRelativo !== '') {
        $candidatosAvatar[] = $pastaAssets . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $caminhoRelativo);
    }

    $prefixoAssets = rtrim($pastaAssets, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    foreach ($candidatosAvatar as $candidatoAvatar) {
        $arquivoFisico = realpath($candidatoAvatar);
        if ($arquivoFisico === false || !is_file($arquivoFisico)) {
            continue;
        }

        $comparacaoPrefixo = DIRECTORY_SEPARATOR === '\\'
            ? strncasecmp($arquivoFisico, $prefixoAssets, strlen($prefixoAssets))
            : strncmp($arquivoFisico, $prefixoAssets, strlen($prefixoAssets));

        if ($comparacaoPrefixo === 0) {
            $caminhoDentroAssets = substr($arquivoFisico, strlen($prefixoAssets));
            $avatar = "../assets/" . str_replace(DIRECTORY_SEPARATOR, "/", $caminhoDentroAssets);
            break;
        }
    }
}

?>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/style.css">
    <title> Informações do usuario </title>
</head>

<body class="cores-background " data-background="azure-escuro-fundo">

    <head>
        <header>

            <?php

            include("components/navbar.php");

            ?>


        </header>

    </head>

    <main class="container py-4">
        <div class="text-center mb-4">
            <div class="cores-background p-3 rounded-4 d-inline-block" data-background="azure-claro-fundo">
                <h2 class="titulo-Sensor mb-0"> Seu perfil </h2>
            </div>
        </div>
        <div class="center-profile flex">
        <div>
            <img class="image-profile" src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar do usuário"
                        onerror="this.onerror=null; this.src='<?= htmlspecialchars($avatarPadrao, ENT_QUOTES, 'UTF-8') ?>';">
        </div>     
            <div class="left-space">
                <h2 class="cores-color" data-color="white"> Nome: <?php echo $_SESSION["usuario"]; ?> </h2>
                <h2 class="cores-color" data-color="white"> Email: <?php echo $_SESSION["email"]; ?> </h2>
                <h2 class="cores-color" data-color="white"> Telefone: <?php echo $_SESSION["telefone"]; ?> </h2>
                <h2 class="cores-color" data-color="white"> Cargo: <?php echo $_SESSION["cargo"]; ?> </h2>
                <h2 class="cores-color" data-color="white"> Cidade: <?php echo $_SESSION["cidade"]; ?> </h2>
                <h2 class="cores-color" data-color="white"> Bairro: <?php echo $_SESSION["bairro"]; ?> </h2>
                <h2 class="cores-color" data-color="white"> Rua: <?php echo $_SESSION["rua"]; ?> </h2>
                <h2 class="cores-color" data-color="white"> UF: <?php echo $_SESSION["uf"]; ?> </h2>
            </div>
        </div>
        


        <a href="hub.php" class="btn btn-danger back-buttom">Voltar</a>
    </main>
    <footer>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
</body>

</html>