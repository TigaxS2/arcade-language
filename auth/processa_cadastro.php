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
        alertarERedirecionar('Erro de validação (CSRF). Tente novamente.', '../cadastro.php', 'error');
    }

    $nome = sanitize($_POST['nome']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $senha_pura = $_POST['senha'];
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
        alertarERedirecionar("Falha na validação de segurança (CAPTCHA).", "../cadastro.php", "error");
    }

    // 3. VALIDAÇÕES
    if (empty($nome) || empty($email) || empty($senha_pura)) {
        alertarERedirecionar("Todos os campos são obrigatórios.", "../cadastro.php", "error");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        alertarERedirecionar("E-mail inválido.", "../cadastro.php", "error");
    }

    if (strlen($senha_pura) < 6) {
        alertarERedirecionar("A senha deve ter no mínimo 6 caracteres.", "../cadastro.php", "error");
    }

    // 4. VERIFICA SE O E-MAIL JÁ EXISTE
    $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $stmt_check->close();
        alertarERedirecionar("Este e-mail já está matriculado na Faculdade! Tente outro.", "../cadastro.php", "error");
    }
    $stmt_check->close();
    
    // 5. CRIPTOGRAFIA DA SENHA
    $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);

    // 6. INSERÇÃO NO BANCO
    $sql = "INSERT INTO usuarios (nome, email, senha, xp, patente, nivel_acesso) VALUES (?, ?, ?, 0, 'Iniciante', 'user')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senha_hash);

    if ($stmt->execute()) {
        alertarERedirecionar("Estudante matriculado com sucesso! Bem-vindo ao Campus.", "../login.php", "success");
    } else {
        alertarERedirecionar("Erro crítico ao acessar o banco de dados. Tente novamente.", "../cadastro.php", "error");
    }
    $stmt->close();
}
$conn->close();
?>