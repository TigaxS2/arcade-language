<?php
/**
 * Verifica se o usuário está logado. Caso contrário, redireciona para a index.
 */
function verificarLogado() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
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
 */
function calcularNivelEscolaridade($xp) {
    if ($xp >= 300) return "Mestre";
    if ($xp >= 150) return "Intermediário";
    return "Iniciante";
}

/**
 * Redireciona com mensagem para o sistema de Toasts.
 */
function alertarERedirecionar($msg, $url, $type = 'info') {
    $msg_encoded = urlencode($msg);
    $separator = (strpos($url, '?') === false) ? '?' : '&';
    header("Location: $url" . $separator . "msg=$msg_encoded&type=$type");
    exit();
}

/**
 * Gera um token CSRF para formulários.
 */
function gerarCSRF() {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida o token CSRF recebido.
 */
function validarCSRF($token) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

/**
 * Limpa dados de entrada para evitar XSS e outras injeções.
 */
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>