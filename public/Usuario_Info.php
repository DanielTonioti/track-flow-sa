<?php
session_start();
require_once "../infra/conn.php";
require_once "components/start.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: ($_SESSION['id'] ?? null);
$user = null;

if (!empty($id) && is_numeric($id) && (int) $id > 0) {
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

$nomeUsuario = htmlspecialchars($user['nome'] ?? $_SESSION["usuario"] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
$emailUsuario = htmlspecialchars($user['email'] ?? $_SESSION["email"] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
$telUsuario = htmlspecialchars($user['telefone'] ?? $_SESSION["telefone"] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
$cargoUsuario = htmlspecialchars(ucfirst($user['cargo'] ?? $_SESSION["cargo"] ?? 'Não informado'), ENT_QUOTES, 'UTF-8');
$cidadeUsuario = htmlspecialchars($user['cidade'] ?? $_SESSION["cidade"] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
$bairroUsuario = htmlspecialchars($user['bairro'] ?? $_SESSION["bairro"] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
$ruaUsuario = htmlspecialchars($user['rua'] ?? $_SESSION["rua"] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
$ufUsuario = htmlspecialchars($user['uf'] ?? $_SESSION["uf"] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo/icone.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Perfil do Usuário - TrackFlow</title>
</head>

<body class="cores-background" data-background="azure-escuro-fundo">
    <header>
        <?php include("components/navbar.php"); ?>
    </header>

    <main class="container py-4">
        <div class="text-center mb-4">
            <div class="cores-background p-3 rounded-4 d-inline-block shadow-sm" data-background="azure-claro-fundo">
                <h2 class="titulo-Sensor mb-0 fs-3 fw-bold">Seu Perfil</h2>
            </div>
        </div>

        <div class="center-profile">
            <div class="text-center mb-4 mb-md-0">
                <img class="image-profile shadow" src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar do usuário"
                    onerror="this.onerror=null; this.src='<?= htmlspecialchars($avatarPadrao, ENT_QUOTES, 'UTF-8') ?>';">
                <div class="mt-3">
                    <span class="badge rounded-pill bg-primary px-3 py-2 fs-6"><?= $cargoUsuario ?></span>
                </div>
            </div>     

            <div class="left-space flex-grow-1">
                <div class="mb-3 border-bottom pb-2 border-secondary border-opacity-25">
                    <small class="text-secondary d-block text-uppercase fw-semibold">Nome</small>
                    <span class="fs-5 fw-bold text-white"><?= $nomeUsuario ?></span>
                </div>
                <div class="mb-3 border-bottom pb-2 border-secondary border-opacity-25">
                    <small class="text-secondary d-block text-uppercase fw-semibold">E-mail</small>
                    <span class="fs-5 text-white"><?= $emailUsuario ?></span>
                </div>
                <div class="mb-3 border-bottom pb-2 border-secondary border-opacity-25">
                    <small class="text-secondary d-block text-uppercase fw-semibold">Telefone</small>
                    <span class="fs-5 text-white"><?= $telUsuario ?></span>
                </div>
                <div class="row g-2">
                    <div class="col-12 col-sm-6 mb-2">
                        <small class="text-secondary d-block text-uppercase fw-semibold">Cidade / UF</small>
                        <span class="fs-6 text-white"><?= $cidadeUsuario ?> / <?= $ufUsuario ?></span>
                    </div>
                    <div class="col-12 col-sm-6 mb-2">
                        <small class="text-secondary d-block text-uppercase fw-semibold">Bairro</small>
                        <span class="fs-6 text-white"><?= $bairroUsuario ?></span>
                    </div>
                    <div class="col-12 mb-2">
                        <small class="text-secondary d-block text-uppercase fw-semibold">Rua</small>
                        <span class="fs-6 text-white"><?= $ruaUsuario ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="hub.php" class="btn btn-outline-danger px-4 py-2 rounded-pill fw-bold">
                <i class="bi bi-arrow-left me-2"></i>Voltar ao Hub
            </a>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/script_navbar.js"></script>
</body>
</html>
