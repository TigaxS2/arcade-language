<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Caminho para sua conexão - verifique se o nome da pasta e arquivo estão certos
require_once '../includes/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitização básica (Segurança XSS)
    $nome = htmlspecialchars($_POST['nome']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    // Criptografia da senha
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // SQL usando Prepared Statements (Segurança contra SQL Injection)
    // Adicionei valores padrão para xp (0) e patente (Iniciante)
    $sql = "INSERT INTO usuarios (nome, email, senha, xp, patente) VALUES (?, ?, ?, 0, 'Iniciante')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {
        echo "<script>alert('Recruta cadastrado com sucesso!'); window.location.href='../index.php';</script>";
    } else {
        // Se der erro de e-mail duplicado, por exemplo
        echo "<script>alert('Erro ao cadastrar. Verifique se o e-mail já existe.'); window.location.href='../index.php';</script>";
    }
}
?>