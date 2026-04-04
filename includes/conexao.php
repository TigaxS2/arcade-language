<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "arcade_language";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Define o charset para evitar problemas com acentos (ç, ã, é, etc.)
$conn->set_charset("utf8mb4");
?>