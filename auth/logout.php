<?php
// 1. Inicia a sessão para poder destruí-la
session_start();

// 2. Limpa todas as variáveis de sessão
$_SESSION = array();

// 3. Destrói a sessão completamente
session_destroy();

// 4. Redireciona para o index.php que está uma pasta acima (../)
header("Location: ../index.php");
exit();
?>