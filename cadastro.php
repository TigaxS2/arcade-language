<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Arcade Language - Cadastro</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <section class="auth-container">
        
        <h1 class="cyber-title">CRIE SUA CONTA</h1>
        
        <div class="cadastro-card">
            <form action="auth/cadastro.php" method="POST">
                <input type="text" name="nome" placeholder="Nome Completo" required>
                <input type="email" name="email" placeholder="Seu melhor e-mail" required>
                <input type="password" name="senha" placeholder="Crie uma Senha forte" required>
                <button type="submit" class="btn">Confirmar Cadastro</button>
            </form>
            
<div class="form-footer" style="margin-top: 25px; text-align: center;">
    <p style="font-size: 0.9rem; color: rgba(255,255,255,0.7);">
        Já possui uma conta acadêmica? 
        <br>
        <a href="login.php" class="cyber-link" style="margin-top: 10px;">← Voltar para o Login!</a>
    </p>
</div>
    </section>

    <footer>
        <p>&copy; 2026 Arcade Language | ADS Project</p>
    </footer>
</body>
</html>