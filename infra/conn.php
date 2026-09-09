<?php

$host = "localhost";
$user = "root";
$password = "root";
$database = "track_flow_m1";

$db = new mysqli($host, $user, $password, $database);

if ($db->connect_error) {
    die("Erro na conexão". $db->connect_error);
}
$db ->set_charset("utf8mb4");