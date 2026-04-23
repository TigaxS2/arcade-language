<?php
require_once 'includes/conexao.php';

// Pega o token da URL
$token = $_GET['token'] ?? '';

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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Senha - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="area-fundo">
    <section class="auth-section">
        <div class="login-card">
            <h2 class="glow-text">REDEFINIR SENHA</h2>
            <p style="color: rgba(255,255,255,0.6); margin-bottom: 20px;">
                Digite sua nova sequência de acesso para o e-mail:<br>
                <strong style="color: #00f0ff;"><?php echo $email_recuperacao; ?></strong>
            </p>

            <form action="auth/finaliza_recuperacao.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email_recuperacao); ?>">

                <div class="input-group">
                    <input type="password" name="nova_senha" placeholder="Nova Senha" required>
                </div>
                <div class="input-group">
                    <input type="password" name="confirma_senha" placeholder="Confirme a Nova Senha" required>
                </div>

                <button type="submit" class="btn" style="width: 100%; margin-top: 15px;">Atualizar Senha</button>
            </form>
        </div>
    </section>
    <footer style="padding: 60px 0; text-align: center; opacity: 0.3;">
        <p>&copy; 2026 Arcade Language | Academic Management System</p>
    </footer>
</body>
</html>