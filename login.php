<?php
require_once 'includes/config.php';
require_once 'includes/funcoes.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Acadêmico - Arcadius Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth_new.css">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
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
            <div class="auth-form-side">
                <h1>Faça seu login<span>.</span></h1>
                
                <form action="auth/login.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo gerarCSRF(); ?>">
                    
                    <div class="auth-input-group">
                        <label>Email Acadêmico</label>
                        <input type="email" name="email" placeholder="estudante@arcadius.com" required>
                    </div>
                    
                    <div class="auth-input-group">
                        <label>Chave de Acesso</label>
                        <input type="password" name="senha" placeholder="••••••••" required>
                    </div>
                    
                    <a href="esqueci_senha.php" class="auth-forgot-link">Esqueceu sua chave de acesso?</a>
                    
                    <div class="cf-turnstile" data-sitekey="<?php echo TURNSTILE_SITEKEY; ?>"></div>
                    
                    <button type="submit" class="auth-submit-btn">Entrar no Sistema</button>
                </form>
                
                <div class="auth-switch-text">
                    Ainda não possui uma conta? <a href="cadastro.php">Realizar Matrícula</a>
                </div>
            </div>

            <!-- Image Side -->
            <div class="auth-image-side">
                <img src="assets/img/Imgloginpage.png" alt="Login Background">
            </div>
        </div>
    </div>

    <footer style="padding: 20px 0; text-align: center; opacity: 0.3; position: relative; z-index: 10; color: white;">
        <p>&copy; 2026 Arcadius Language | Academic Management System</p>
    </footer>
</body>
</html>