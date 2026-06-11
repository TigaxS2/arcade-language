<?php
require_once 'includes/config.php';
require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

// Pega o token da URL
$token = sanitize($_GET['token'] ?? '');

if (empty($token)) {
    die("Acesso negado. Token não fornecido.");
}

// Verifica se o token existe e ainda é válido (menos de 1 hora de vida)
$stmt = $conn->prepare("SELECT email FROM recuperacao_senha WHERE token = ? AND data_expiracao > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Este link de recuperação expirou ou é inválido. Solicite um novo.");
}

$dados = $result->fetch_assoc();
$email_recuperacao = $dados['email'];
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha - Arcadius Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth_new.css">
</head>
<body class="auth-body-page">
    <?php include 'includes/navbar.php'; ?>

    <!-- Background Elements -->
    <div class="auth-bg-wrapper">
        <div class="auth-circle"></div>
        <div class="auth-ring"></div>
        <div class="auth-line"></div>
    </div>

    <div class="auth-container">
        <div class="auth-card">
            <!-- Form Side -->
            <div class="auth-form-side" style="width: 100%; max-width: 500px; margin: 0 auto;">
                <h1 style="text-align: center;">Nova Chave<span>.</span></h1>
                
                <p style="color: #bdbdbd; margin-bottom: 30px; text-align: center;">
                    Digite sua nova sequência de acesso para o e-mail:<br>
                    <strong style="color: #ff2d6f;"><?php echo htmlspecialchars($email_recuperacao); ?></strong>
                </p>

                <form action="auth/finaliza_recuperacao.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo gerarCSRF(); ?>">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email_recuperacao); ?>">

                    <div class="auth-input-group">
                        <label>Nova Senha</label>
                        <input type="password" name="nova_senha" placeholder="••••••••" required>
                    </div>
                    
                    <div class="auth-input-group">
                        <label>Confirmar Nova Senha</label>
                        <input type="password" name="confirma_senha" placeholder="••••••••" required>
                    </div>
                    
                    <button type="submit" class="auth-submit-btn">Atualizar Chave</button>
                </form>
            </div>
        </div>
    </div>

    <footer style="padding: 20px 0; text-align: center; opacity: 0.3; position: relative; z-index: 10; color: white;">
        <p>&copy; 2026 Arcadius Language | Academic Management System</p>
    </footer>
</body>
</html>