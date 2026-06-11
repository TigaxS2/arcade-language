<?php
session_start();
require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

if (!isset($_SESSION['id'])) { 
    header("Location: ../login.php"); 
    exit(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. VERIFICAÇÃO CSRF
    if (!validarCSRF($_POST['csrf_token'] ?? '')) {
        alertarERedirecionar('Erro de validação (CSRF). Tente novamente.', '../perfil.php', 'error');
    }

    $user_id = (int)$_SESSION['id'];
    $nome = sanitize($_POST['nome']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];
    $erro = false;

    // --- 2. LÓGICA DE UPLOAD DA FOTO ---
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $pasta_destino = '../assets/img/';
        if (!is_dir($pasta_destino)) { mkdir($pasta_destino, 0777, true); }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($_FILES['foto_perfil']['tmp_name']);
        
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
        $extensao = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));

        if (!in_array($mime_type, $allowed_mimes) || !in_array($extensao, ['jpg', 'jpeg', 'png', 'gif'])) {
            alertarERedirecionar("Tipo de arquivo não permitido. Apenas JPG, PNG ou GIF.", "../perfil.php", "error");
        }

        if ($_FILES['foto_perfil']['size'] > 2097152) { // 2MB
            alertarERedirecionar("A imagem é muito grande! Máximo 2MB.", "../perfil.php", "error");
        }

        $novo_nome_arquivo = 'user_' . $user_id . '_' . time() . '.' . $extensao;
        $db_caminho = 'assets/img/' . $novo_nome_arquivo; 

        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $pasta_destino . $novo_nome_arquivo)) {
            $stmt = $conn->prepare("UPDATE usuarios SET foto = ? WHERE id = ?");
            $stmt->bind_param("si", $db_caminho, $user_id);
            if ($stmt->execute()) { $_SESSION['foto'] = $db_caminho; }
            $stmt->close();
        }
    }

    // --- 3. ATUALIZAÇÃO DE NOME E EMAIL ---
    if (!empty($nome) && !empty($email)) {
        // Verifica se o email já existe para outro usuário
        $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt_check->bind_param("si", $email, $user_id);
        $stmt_check->execute();
        if ($stmt_check->get_result()->num_rows > 0) {
            alertarERedirecionar("Este e-mail já está em uso por outro acadêmico.", "../perfil.php", "error");
        }
        $stmt_check->close();

        $stmt_info = $conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
        $stmt_info->bind_param("ssi", $nome, $email, $user_id);
        if ($stmt_info->execute()) {
            $_SESSION['nome'] = $nome; 
        }
        $stmt_info->close();
    }

    // --- 4. LÓGICA DE TROCA DE SENHA ---
    if (!empty($nova_senha)) {
        if (strlen($nova_senha) < 6) {
            alertarERedirecionar("A nova senha deve ter no mínimo 6 caracteres.", "../perfil.php", "error");
        }
        if ($nova_senha === $confirma_senha) {
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt_senha = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $stmt_senha->bind_param("si", $senha_hash, $user_id);
            $stmt_senha->execute();
            $stmt_senha->close();
        } else {
            alertarERedirecionar("As senhas não coincidem!", "../perfil.php", "error");
        }
    }

    alertarERedirecionar("Perfil e segurança atualizados, Acadêmico!", "../dashboard.php", "success");
}
$conn->close();
?>