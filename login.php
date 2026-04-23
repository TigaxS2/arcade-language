<?php
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
    <title>Acesso Acadêmico - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .auth-page { display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 80px); padding: 20px; }
        .login-card { width: 100%; max-width: 420px; }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="auth-page">
        <div class="login-card">
            <header style="text-align: center; margin-bottom: 35px;">
                <h1 class="glow-text" style="font-size: 2.8rem; margin-bottom: 5px;">ACESSO</h1>
                <p style="font-size: 0.85rem; color: var(--neon-cyan); letter-spacing: 3px; opacity: 0.8;">TERMINAL DE AUTENTICAÇÃO</p>
            </header>
            
            <form action="auth/login.php" method="POST">
                <div class="input-group">
                    <label>E-mail Acadêmico</label>
                    <input type="email" name="email" placeholder="estudante@arcade.com" required>
                </div>
                
                <div class="input-group">
                    <label>Chave de Acesso</label>
                    <input type="password" name="senha" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn" style="width: 100%; margin-top: 10px;">INICIAR SESSÃO NO SISTEMA</button>
            </form>
            
            <div style="margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 25px; text-align: center;">
                <p style="margin-bottom: 15px;">
                    <a href="esqueci_senha.php" class="cyber-link">Esqueceu sua chave de acesso?</a>
                </p>
                <p style="font-size: 0.9rem; opacity: 0.8;">
                    Novo acadêmico? <a href="cadastro.php" class="cyber-link-bold">Realizar Matrícula</a>
                </p>
            </div>
        </div>
    </div>

    <footer style="padding: 40px 0; text-align: center; opacity: 0.3;">
        <p>&copy; 2026 Arcade Language | Academic Management System</p>
    </footer>
</body>
</html>