<?php
require_once 'includes/config.php';
require_once 'includes/funcoes.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Acesso - Arcadius Language</title>
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
                <h1 style="text-align: center;">Resgate sua conta<span>.</span></h1>
                
                <p style="color: #bdbdbd; margin-bottom: 30px; text-align: center; font-size: 0.95rem; line-height: 1.6;">
                    Insira seu e-mail institucional para que possamos enviar o link de restauração da sua chave de acesso.
                </p>

                <form action="auth/processa_recuperacao.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo gerarCSRF(); ?>">
                    
                    <div class="auth-input-group">
                        <label>E-mail de Estudante</label>
                        <input type="email" name="email" placeholder="estudante@arcadius.com" required>
                    </div>
                    
                    <button type="submit" class="auth-submit-btn">Solicitar Resgate</button>
                </form>
                
                <div class="auth-switch-text">
                    Lembrou sua chave? <a href="login.php">Voltar ao Login</a>
                </div>
            </div>
        </div>
    </div>

    <footer style="padding: 20px 0; text-align: center; opacity: 0.3; position: relative; z-index: 10; color: white;">
        <p>&copy; 2026 Arcadius Language | Academic Management System</p>
    </footer>
</body>
</html>