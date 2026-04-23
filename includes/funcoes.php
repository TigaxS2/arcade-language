<?php
/**
 * Verifica se o usuário está logado. Caso contrário, redireciona para a index.
 */
function verificarLogado() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['id'])) {
        header("Location: index.php");
        exit();
    }
}

/**
 * Verifica se o usuário é Administrador.
 */
function verificarAdmin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['id']) || !isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] !== 'admin') {
        header("Location: dashboard.php");
        exit();
    }
}

/**
 * Retorna o Nível de Escolaridade baseado no XP atual.
 * @param int $xp
 * @return string
 */
function calcularNivelEscolaridade($xp) {
    if ($xp >= 300) return "Mestre";
    if ($xp >= 150) return "Intermediário";
    return "Iniciante";
}

/**
 * Redireciona com mensagem para o sistema de Toasts.
 * @param string $msg
 * @param string $url
 * @param string $type (success, error, info)
 */
function alertarERedirecionar($msg, $url, $type = 'info') {
    $msg_encoded = urlencode($msg);
    header("Location: $url?msg=$msg_encoded&type=$type");
    exit();
}
?>