<?php
require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $email = $_POST['email'];
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // 1. Validação básica de senha
    if (strlen($nova_senha) < 6) {
        alertarERedirecionar("A senha deve ter pelo menos 6 caracteres.", "../nova_senha.php?token=$token", "error");
    }

    if ($nova_senha !== $confirma_senha) {
        alertarERedirecionar("As senhas não coincidem.", "../nova_senha.php?token=$token", "error");
    }

    // 2. Verifica se o token ainda é válido
    $stmt = $conn->prepare("SELECT id FROM recuperacao_senha WHERE email = ? AND token = ? AND data_expiracao > NOW()");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        alertarERedirecionar("O pedido de recuperação expirou ou é inválido.", "../index.php", "error");
    }

    // 3. Atualiza a senha no banco de dados (Criptografada com BCRYPT)
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $stmt_update = $conn->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
    $stmt_update->bind_param("ss", $senha_hash, $email);
    
    if ($stmt_update->execute()) {
        // 4. Deleta o token usado para evitar reutilização
        $conn->query("DELETE FROM recuperacao_senha WHERE email = '$email'");
        
        alertarERedirecionar("Sua senha foi redefinida com sucesso!", "../index.php", "success");
    } else {
        alertarERedirecionar("Erro ao atualizar a senha. Tente novamente.", "../index.php", "error");
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>