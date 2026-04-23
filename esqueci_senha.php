<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Acesso - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .auth-page { display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 150px); padding: 20px; }
        .recovery-card { width: 100%; max-width: 450px; }
    </style>
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <div class="auth-page">
        <div class="recovery-card card">
            <header style="text-align: center; margin-bottom: 35px;">
                <h1 class="glow-text" style="font-size: 2.2rem; margin-bottom: 5px;">RESTAURAÇÃO</h1>
                <p style="font-size: 0.8rem; color: var(--neon-cyan); letter-spacing: 3px; opacity: 0.8;">PROTOCOLO DE RESGATE</p>
            </header>
            
            <p style="color: rgba(255,255,255,0.7); margin-bottom: 30px; text-align: center; font-size: 0.95rem; line-height: 1.6;">
                Insira seu e-mail institucional para que possamos enviar o link de restauração da sua chave de acesso.
            </p>
            
            <form action="auth/processa_recuperacao.php" method="POST">
                <div class="input-group">
                    <label>E-mail de Estudante</label>
                    <input type="email" name="email" placeholder="estudante@arcade.com" required>
                </div>
                
                <button type="submit" class="btn" style="width: 100%; margin-top: 10px;">SOLICITAR RESGATE ACADÊMICO</button>
            </form>
            
            <div style="margin-top: 30px; border-top: 1px solid var(--glass-border); padding-top: 25px; text-align: center;">
                <p style="font-size: 0.9rem; opacity: 0.8;">
                    Lembrou sua chave? <a href="login.php" class="cyber-link-bold">Voltar ao Login</a>
                </p>
            </div>
        </div>
    </div>

    <footer style="padding: 60px 0; text-align: center; opacity: 0.3;">
        <p>&copy; 2026 Arcade Language | Academic Management System</p>
    </footer>
</body>
</html>