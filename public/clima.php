
<?php
date_default_timezone_set('America/Sao_Paulo');

// Local consultado
$cidade = 'Joinville';
$estado = 'Santa Catarina';
$pais = 'Brasil';

$latitude = -26.30;
$longitude = -48.85;

$params = [
    'latitude' => $latitude,
    'longitude' => $longitude,
    'current' => 'temperature_2m,weather_code,is_day',
    'timezone' => 'America/Sao_Paulo'
];

$url = 'https://api.open-meteo.com/v1/forecast?'
     . http_build_query($params);

$contexto = stream_context_create([
    'http' => ['timeout' => 10]
]);

$resposta = @file_get_contents($url, false, $contexto);

if ($resposta === false) {
    exit('Não foi possível consultar o serviço de clima.');
}

$dados = json_decode($resposta);

if (!isset($dados->current)) {
    exit('Resposta inválida da API.');
}

$clima = $dados->current;

// Traduz o código meteorológico WMO
$condicoes = [
    0  => 'Céu limpo',
    1  => 'Predominantemente limpo',
    2  => 'Parcialmente nublado',
    3  => 'Nublado',
    45 => 'Neblina',
    48 => 'Neblina com geada',
    51 => 'Garoa leve',
    53 => 'Garoa moderada',
    55 => 'Garoa intensa',
    61 => 'Chuva leve',
    63 => 'Chuva moderada',
    65 => 'Chuva forte',
    71 => 'Neve leve',
    73 => 'Neve moderada',
    75 => 'Neve forte',
    80 => 'Pancadas de chuva leves',
    81 => 'Pancadas de chuva moderadas',
    82 => 'Pancadas de chuva fortes',
    95 => 'Trovoadas'
];

$codigo = $clima->weather_code;
$condicao = $condicoes[$codigo] ?? 'Condição variável';

if ($clima->is_day == 0 && $codigo == 0) {
    $condicao = 'Céu limpo à noite';
}

// Horário informado pela API
$horario = new DateTime(
    $clima->time,
    new DateTimeZone('America/Sao_Paulo')
);

$horarioConsulta = date('d/m/Y H:i:s');

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clima atual</title>
</head>
<body>
    <h1>Clima atual</h1>

    <p>
        <strong>Temperatura:</strong>
        <?= htmlspecialchars((string)$clima->temperature_2m) ?> °C
    </p>

    <p>
        <strong>Condição climática:</strong>
        <?= htmlspecialchars($condicao) ?>
    </p>

    <p>
        <strong>Localização:</strong>
        <?= htmlspecialchars("$cidade, $estado, $pais") ?>
    </p>

    <p>
        <strong>Data/hora dos dados meteorológicos:</strong>
        <?= $horario->format('d/m/Y H:i') ?>
    </p>

    <p>
        <strong>Data/hora da consulta:</strong>
        <?= $horarioConsulta ?>
    </p>
</body>
</html>
