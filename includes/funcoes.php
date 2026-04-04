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
 * Retorna a patente baseada no XP atual.
 * @param int $xp
 * @return string
 */
function calcularPatente($xp) {
    if ($xp >= 300) return "Mestre";
    if ($xp >= 150) return "Intermediário";
    return "Iniciante";
}

/**
 * Exibe um alerta JavaScript e redireciona.
 * @param string $msg
 * @param string $url
 */
function alertarERedirecionar($msg, $url) {
    echo "<script>alert('$msg'); window.location.href='$url';</script>";
    exit();
}
?>