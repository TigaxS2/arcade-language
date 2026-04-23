<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = htmlspecialchars(trim($_POST['nome']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $senha_pura = $_POST['senha'];

    // 1. VALIDAÇÃO BÁSICA
    if (strlen($senha_pura) < 6) {
        alertarERedirecionar("A senha deve ter no mínimo 6 caracteres.", "../cadastro.php", "error");
    }

    // 2. VERIFICA SE O E-MAIL JÁ EXISTE
    $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $stmt_check->close();
        alertarERedirecionar("Este e-mail já está matriculado na Faculdade! Tente outro.", "../cadastro.php", "error");
    }
    $stmt_check->close();
    
    // 3. CRIPTOGRAFIA DA SENHA
    $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);

    // 4. INSERÇÃO NO BANCO
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