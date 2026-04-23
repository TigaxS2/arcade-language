<?php
require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
    // 1. Verifica se o e-mail existe no sistema
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // 2. Gera um token único e seguro
        $token = bin2hex(random_bytes(32));
        $expiracao = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token vale por 1 hora

        // 3. Salva o token no banco
        $conn->query("DELETE FROM recuperacao_senha WHERE email = '$email'");
        $stmt_token = $conn->prepare("INSERT INTO recuperacao_senha (email, token, data_expiracao) VALUES (?, ?, ?)");
        $stmt_token->bind_param("sss", $email, $token, $expiracao);
        $stmt_token->execute();

        // 4. Monta o link de recuperação
        $link = "http://" . $_SERVER['HTTP_HOST'] . "/arcade-language/nova_senha.php?token=" . $token;

        // 5. Tenta enviar o e-mail
        $assunto = "Recuperação de Acesso - Arcade Language";
        $mensagem = "Olá, estudante! Você solicitou a troca de senha acadêmica.\n\nClique no link abaixo para redefinir:\n$link\n\nSe não foi você, ignore este e-mail.";
        $headers = "From: no-reply@arcadelanguage.com";
        
        @mail($email, $assunto, $mensagem, $headers);

        // EXIBIÇÃO PARA TESTE
        echo "<!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <link rel='stylesheet' href='../assets/css/style.css'>
            <title>E-mail Enviado</title>
        </head>
        <body class='area-fundo'>
            <section class='auth-container'>
                <h1 class='cyber-title'>E-MAIL ENVIADO!</h1>
                <div class='login-card' style='max-width: 550px; text-align: center;'>
                    <p style='color: #00f0ff; margin-bottom: 20px;'>Verifique sua caixa de entrada acadêmica para o e-mail: <strong>$email</strong></p>
                    <p style='font-size: 0.8rem; color: rgba(255,255,255,0.5);'>[ MODO ACADÊMICO ]: Link gerado para teste:</p>
                    <a href='$link' style='color: #ff00ff; font-size: 0.85rem; word-break: break-all;'>$link</a>
                    <br><br>
                    <a href='../login.php' class='btn'>Voltar ao Login</a>
                </div>
            </section>
        </body>
        </html>";
    } else {
        alertarERedirecionar("E-mail não cadastrado em nossa Faculdade.", "../esqueci_senha.php", "error");
    }
} else {
    header("Location: ../esqueci_senha.php");
    exit();
}
?>