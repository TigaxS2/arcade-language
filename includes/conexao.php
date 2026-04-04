<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "arcade_language";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
// NÃO PODE TER NADA ESCRITO AQUI EMBAIXO
?>