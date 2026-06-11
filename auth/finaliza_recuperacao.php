<?php
require_once '../includes/config.php';
require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. VERIFICAÇÃO CSRF
    if (!validarCSRF($_POST['csrf_token'] ?? '')) {
        alertarERedirecionar('Erro de validação (CSRF). Tente novamente.', '../index.php', 'error');
    }

    $token = sanitize($_POST['token']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // 2. Validação básica de senha
    if (strlen($nova_senha) < 6) {
        alertarERedirecionar("A senha deve ter pelo menos 6 caracteres.", "../nova_senha.php?token=$token", "error");
    }

    if ($nova_senha !== $confirma_senha) {
        alertarERedirecionar("As senhas não coincidem.", "../nova_senha.php?token=$token", "error");
    }

    // 3. Verifica se o token ainda é válido
    $stmt = $conn->prepare("SELECT id FROM recuperacao_senha WHERE email = ? AND token = ? AND data_expiracao > NOW()");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        alertarERedirecionar("O pedido de recuperação expirou ou é inválido.", "../index.php", "error");
    }

    // 4. Atualiza a senha no banco de dados (Criptografada com BCRYPT)
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $stmt_update = $conn->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
    $stmt_update->bind_param("ss", $senha_hash, $email);
    
    if ($stmt_update->execute()) {
        // 5. Deleta o token usado para evitar reutilização
        $stmt_del = $conn->prepare("DELETE FROM recuperacao_senha WHERE email = ?");
        $stmt_del->bind_param("s", $email);
        $stmt_del->execute();
        
        alertarERedirecionar("Sua senha foi redefinida com sucesso!", "../index.php", "success");
    } else {
        alertarERedirecionar("Erro ao atualizar a senha. Tente novamente.", "../index.php", "error");
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>