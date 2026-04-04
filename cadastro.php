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
            
            <div class="form-footer">
                <p>Já possui uma conta? <a href="login.php">Voltar para o Login</a></p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 Arcade Language | ADS Project</p>
    </footer>
</body>
</html>