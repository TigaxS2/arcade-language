<?php
session_start();
require_once '../includes/conexao.php';

if (!isset($_SESSION['id'])) { 
    header("Location: ../index.php"); 
    exit(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];
    $erro = false;

    // --- 1. LÓGICA DE UPLOAD DA FOTO (Mantida e melhorada) ---
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $pasta_destino = '../assets/img/';
        if (!is_dir($pasta_destino)) { mkdir($pasta_destino, 0777, true); }

        $extensao = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
        $novo_nome_arquivo = 'user_' . $user_id . '_' . time() . '.' . $extensao;
        $db_caminho = 'assets/img/' . $novo_nome_arquivo; 

        if ($_FILES['foto_perfil']['size'] > 2097152) {
            echo "<script>alert('A imagem é muito grande! Máximo 2MB.'); window.history.back();</script>";
            $erro = true;
        }

        if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<script>alert('Apenas JPG, PNG ou GIF.'); window.history.back();</script>";
            $erro = true;
        }

        if (!$erro && move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $pasta_destino . $novo_nome_arquivo)) {
            $stmt = $conn->prepare("UPDATE usuarios SET foto = ? WHERE id = ?");
            $stmt->bind_param("si", $db_caminho, $user_id);
            if ($stmt->execute()) { $_SESSION['foto'] = $db_caminho; }
            $stmt->close();
        }
    }

    // --- 2. ATUALIZAÇÃO DE NOME E EMAIL ---
    if (!$erro && !empty($nome) && !empty($email)) {
        $stmt_info = $conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
        $stmt_info->bind_param("ssi", $nome, $email, $user_id);
        if ($stmt_info->execute()) {
            $_SESSION['nome'] = $nome; // Atualiza a sessão
        }
        $stmt_info->close();
    }

    // --- 3. LÓGICA DE TROCA DE SENHA (Opcional) ---
    if (!$erro && !empty($nova_senha)) {
        if ($nova_senha === $confirma_senha) {
            // Criptografa a nova senha antes de salvar
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt_senha = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $stmt_senha->bind_param("si", $senha_hash, $user_id);
            $stmt_senha->execute();
            $stmt_senha->close();
        } else {
            echo "<script>alert('As senhas não coincidem!'); window.history.back();</script>";
            $erro = true;
        }
    }

    // --- FINALIZAÇÃO ---
    if (!$erro) {
        echo "<script>alert('Perfil e segurança atualizados, Soldado!'); window.location.href='../dashboard.php';</script>";
    }
}
$conn->close();