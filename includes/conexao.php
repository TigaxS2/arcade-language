<?php
require_once dirname(__FILE__) . '/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    if (APP_ENV === 'development') {
        die("Falha na conexão: " . $conn->connect_error);
    } else {
        die("Erro crítico de sistema. Por favor, tente novamente mais tarde.");
    }
}

$conn->set_charset("utf8mb4");
?>