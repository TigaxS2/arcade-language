<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Acesso - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <section class="auth-container">
        
        <h1 class="cyber-title">RECUPERAR ACESSO</h1>
        
        <div class="login-card">
            <p style="color: rgba(255,255,255,0.7); margin-bottom: 25px; text-align: center; font-size: 0.9rem;">
                Insira seu e-mail de recruta para receber o código de restauração.
            </p>
            
            <form action="auth/processa_recuperacao.php" method="POST">
                <input type="email" name="email" placeholder="Seu e-mail cadastrado" required>
                <button type="submit" class="btn">Enviar Link de Resgate</button>
            </form>
            
            <div class="form-footer">
                <a href="login.php" style="color: #00f0ff; text-decoration: none;">← Voltar ao Login</a>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 Arcade Language | ADS Project</p>
    </footer>
</body>
</html>