<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../includes/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Usamos trim() para remover qualquer espaço acidental no começo ou fim
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // --- SISTEMA DE VERIFICAÇÃO DUPLA ---
        $senha_correta = false;

        // 1. Tenta verificar por Hash (Segurança máxima)
        if (password_verify($senha, $usuario['senha'])) {
            $senha_correta = true;
        } 
        // 2. Se falhar, tenta comparação direta (Texto Puro para testes)
        elseif ($senha === $usuario['senha']) {
            $senha_correta = true;
        }

        if ($senha_correta) {
            // Sucesso! Grava tudo na Sessão
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['patente'] = $usuario['patente'];
            $_SESSION['xp'] = $usuario['xp'];
            
            // Define a foto ou a padrão
            $_SESSION['foto'] = (!empty($usuario['foto'])) ? $usuario['foto'] : 'assets/img/default.png';

            header("Location: ../dashboard.php");
            exit();
        } else {
            // Debug: Se quiser ver o que está vindo, descomente a linha abaixo (apenas para teste)
            // die("Senha digitada: $senha | Senha no banco: " . $usuario['senha']);
            echo "<script>alert('Senha incorreta!'); window.location.href='../index.php';</script>";
        }
    } else {
        echo "<script>alert('E-mail não cadastrado na Arena!'); window.location.href='../index.php';</script>";
    }
}
?>