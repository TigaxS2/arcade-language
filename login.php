<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <section class="auth-container">
        <div class="auth-header" style="text-align: center; margin-bottom: 30px;">
            <h1 class="glow-text" style="font-size: 3.5rem; letter-spacing: 5px; margin-bottom: 0;">ACESSO</h1>
            <p class="cyber-subtitle">AUTENTICAÇÃO DE ESTUDANTE</p>
        </div>
        
        <div class="login-card">
            <form action="auth/login.php" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="E-mail Acadêmico" required>
                </div>
                <div class="input-group">
                    <input type="password" name="senha" placeholder="Chave de Acesso" required>
                </div>
                
                <button type="submit" class="btn">INICIAR SESSÃO NO SISTEMA</button>
            </form>
            
            <div class="form-footer" style="margin-top: 25px; text-align: center;">
                <p style="margin-bottom: 12px;">
                    <a href="esqueci_senha.php" class="cyber-link">Esqueceu a senha?</a>
                </p>
                <p style="font-size: 0.85rem; opacity: 0.8;">
                    Novo acadêmico? <a href="cadastro.php" class="cyber-link-bold">Cadraste-se</a>
                </p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 Arcade Language | ADS Project</p>
    </footer>
</body>
</html>