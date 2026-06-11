<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../includes/config.php';
require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. VERIFICAÇÃO CSRF
    if (!validarCSRF($_POST['csrf_token'] ?? '')) {
        alertarERedirecionar('Erro de validação (CSRF). Tente novamente.', '../login.php', 'error');
    }

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $senha = trim($_POST['senha']);
    $turnstileResponse = $_POST['cf-turnstile-response'] ?? '';

    // 2. VERIFICAÇÃO TURNSTILE
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://challenges.cloudflare.com/turnstile/v0/siteverify");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'secret'   => TURNSTILE_SECRET,
        'response' => $turnstileResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $responseData = json_decode($response, true);

    if (!$responseData['success']) {
        alertarERedirecionar('Falha na validação de segurança (CAPTCHA).', '../login.php', 'error');
    }

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        if (password_verify($senha, $usuario['senha'])) {
            // Sucesso! Regenera o ID da sessão para prevenir Session Fixation
            session_regenerate_id(true);
            
            // Grava na Sessão
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['patente'] = $usuario['patente'];
            $_SESSION['xp'] = $usuario['xp'];
            $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];
            $_SESSION['foto'] = (!empty($usuario['foto'])) ? $usuario['foto'] : 'assets/img/default.png';

            // REGISTRA ACESSO DIÁRIO
            $hoje = date('Y-m-d');
            $stmt_log = $conn->prepare("INSERT IGNORE INTO log_acessos (usuario_id, data_acesso) VALUES (?, ?)");
            $stmt_log->bind_param("is", $_SESSION['id'], $hoje);
            $stmt_log->execute();

            header("Location: ../dashboard.php");
            exit();
        } else {
            alertarERedirecionar('Senha incorreta!', '../login.php', 'error');
        }
    } else {
        alertarERedirecionar('E-mail não cadastrado na Faculdade!', '../login.php', 'error');
    }
}
?>