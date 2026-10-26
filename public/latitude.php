<?php
require_once '../infra/conn.php';

// Buscar funcionários sem coordenadas
$sql = "SELECT id, cidade, uf FROM funcionario WHERE latitude IS NULL OR longitude IS NULL";

$resultado = $db->query($sql);

if (!$resultado) {
    exit("Erro ao consultar funcionários.");
}

while ($funcionario = $resultado->fetch_assoc()) {

    $id = (int) $funcionario['id'];
    $cidade = trim($funcionario['cidade']);
    $uf = strtoupper(trim($funcionario['uf']));

    // Consultar a API de geolocalização
    $parametros = http_build_query([
        'name' => $cidade,
        'count' => 10,
        'language' => 'pt',
        'format' => 'json',
        'countryCode' => 'BR'
    ]);

    $url = "https://geocoding-api.open-meteo.com/v1/search?$parametros";

    $contexto = stream_context_create([
        'http' => [
            'timeout' => 10,
            'ignore_errors' => false
        ]
    ]);

    $resposta = @file_get_contents($url, false, $contexto);

    if ($resposta === false) {
        error_log("Falha na API para funcionário ID $id");
        continue;
    }

    $dados = json_decode($resposta, true);

    if (empty($dados['results'])) {
        error_log("Cidade não encontrada: $cidade - $uf");
        continue;
    }

    // Procurar uma correspondência de cidade e estado
    $local = null;

    foreach ($dados['results'] as $item) {
        $nomeCidade = $item['name'] ?? '';
        $nomeEstado = $item['admin1'] ?? '';

        // A API pode retornar a sigla ou o nome completo do estado.
        $estados = [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins'
        ];

        $estadoEsperado = $estados[$uf] ?? '';

        if (
            strcasecmp($nomeCidade, $cidade) === 0 &&
            (
                strcasecmp($nomeEstado, $uf) === 0 ||
                strcasecmp($nomeEstado, $estadoEsperado) === 0
            )
        ) {
            $local = $item;
            break;
        }
    }

    if (!$local) {
        error_log("Correspondência não confirmada: $cidade - $uf");
        continue;
    }

    $latitude = (float) $local['latitude'];
    $longitude = (float) $local['longitude'];

    // Salvar as coordenadas no banco
    $stmt = $db->prepare("
        UPDATE funcionario
        SET latitude = ?, longitude = ?
        WHERE id = ?
    ");

    $stmt->bind_param("ddi", $latitude, $longitude, $id);

    if (!$stmt->execute()) {
        error_log("Erro ao salvar coordenadas do funcionário ID $id");
    }

    $stmt->close();
}

$resultado->free();
$db->close();
header('location: cadastro_usuario.php?sucesso=1');
exit;
?>