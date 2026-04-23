<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // --- VERIFICAÇÃO DE HASH ---
        if (password_verify($senha, $usuario['senha'])) {
            // Sucesso! Grava na Sessão
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['patente'] = $usuario['patente']; // Grau de Escolaridade
            $_SESSION['xp'] = $usuario['xp'];
            $_SESSION['nivel_acesso'] = $usuario['nivel_acesso']; // Admin/User
            
            // Define a foto ou a padrão
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