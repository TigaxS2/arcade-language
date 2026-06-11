<?php
require_once '../includes/config.php';
require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. VERIFICAÇÃO CSRF
    if (!validarCSRF($_POST['csrf_token'] ?? '')) {
        alertarERedirecionar('Erro de validação (CSRF). Tente novamente.', '../esqueci_senha.php', 'error');
    }

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    
    // 2. Verifica se o e-mail existe no sistema
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // 3. Gera um token único e seguro
        $token = bin2hex(random_bytes(32));
        $expiracao = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // 4. Salva o token no banco (Remove anteriores para o mesmo email)
        $stmt_del = $conn->prepare("DELETE FROM recuperacao_senha WHERE email = ?");
        $stmt_del->bind_param("s", $email);
        $stmt_del->execute();
        $stmt_del->close();

        $stmt_token = $conn->prepare("INSERT INTO recuperacao_senha (email, token, data_expiracao) VALUES (?, ?, ?)");
        $stmt_token->bind_param("sss", $email, $token, $expiracao);
        $stmt_token->execute();

        // 5. Monta o link de recuperação
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $link = $protocol . $_SERVER['HTTP_HOST'] . "/arcadius-language/nova_senha.php?token=" . $token;

        // 6. Envia o e-mail via Resend API
        $assunto = "Recuperação de Acesso - Arcadius Language";
        $mensagem_html = "
            <div style='background: #0a0a0c; color: #fff; padding: 40px; font-family: sans-serif; border: 1px solid #00f0ff;'>
                <h1 style='color: #00f0ff; text-transform: uppercase;'>Arcadius Language</h1>
                <p>Olá, estudante!</p>
                <p>Recebemos uma solicitação para redefinir sua senha de acesso ao portal acadêmico.</p>
                <p>Para prosseguir, clique no botão abaixo:</p>
                <div style='margin: 30px 0;'>
                    <a href='$link' style='background: #ff00ff; color: #fff; padding: 15px 25px; text-decoration: none; border-radius: 4px; font-weight: bold;'>REDEFINIR MINHA SENHA</a>
                </div>
                <p style='font-size: 0.8rem; color: #666;'>Se você não solicitou esta alteração, ignore este e-mail por segurança.</p>
                <hr style='border: 0; border-top: 1px solid #333; margin: 20px 0;'>
                <p style='font-size: 0.7rem; color: #444;'>[ PROTOCOLO DE RECUPERAÇÃO ATIVADO ]</p>
            </div>
        ";

        $resend_url = 'https://api.resend.com/emails';
        $resend_data = [
            'from' => 'Arcadius Language <onboarding@resend.dev>', 
            'to' => [$email],
            'subject' => $assunto,
            'html' => $mensagem_html
        ];

        $ch = curl_init($resend_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($resend_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . RESEND_API_KEY,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../assets/css/style.css">
            <title>Recuperação Iniciada</title>
            <style>
                body.area-fundo {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                    padding: 20px;
                    box-sizing: border-box;
                }
                .auth-container {
                    width: 100%;
                    max-width: 600px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }
                .login-card {
                    width: 100%;
                    text-align: center;
                    padding: 40px;
                    background: rgba(10, 10, 12, 0.9);
                    border: 1px solid #00f0ff;
                    box-shadow: 0 0 20px rgba(0, 240, 255, 0.2);
                }
                .debug-box {
                    margin-top: 30px;
                    padding: 15px;
                    background: rgba(255, 255, 255, 0.05);
                    border-radius: 4px;
                    border-left: 4px solid #ff00ff;
                }
                .error-box {
                    background: rgba(255, 0, 0, 0.1);
                    border: 1px solid #ff0000;
                    padding: 15px;
                    margin-bottom: 20px;
                    text-align: left;
                }
            </style>
        </head>
        <body class="area-fundo">
            <section class="auth-container">
                <h1 class="cyber-title">PROTOCOLO ATIVADO!</h1>
                <div class="login-card">
                    <p style="color: #00f0ff; margin-bottom: 25px; font-size: 1.1rem;">
                        Enviamos instruções de recuperação para: <br>
                        <strong style="color: #fff;"><?php echo htmlspecialchars($email); ?></strong>
                    </p>
                    
                    <?php if ($http_code !== 200 && $http_code !== 201): ?>
                        <div class="error-box">
                            <p style="color: #ff4d4d; margin: 0; font-weight: bold;">[ FALHA NO DISPARO ]</p>
                            <p style="color: #fff; font-size: 0.85rem; margin: 5px 0;">Ocorreu um erro ao conectar com o servidor de e-mail (Código: <?php echo $http_code; ?>).</p>
                        </div>
                    <?php endif; ?>

                    <div class="debug-box">
                        <p style="font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;">[ MODO ACADÊMICO ]: Link de Redirecionamento</p>
                        <a href="<?php echo htmlspecialchars($link); ?>" style="color: #ff00ff; font-size: 0.85rem; word-break: break-all; text-decoration: none; border-bottom: 1px dashed #ff00ff;">
                            <?php echo htmlspecialchars($link); ?>
                        </a>
                    </div>
                    
                    <div style="margin-top: 40px;">
                        <a href="../login.php" class="btn">Voltar ao Login</a>
                    </div>
                </div>
            </section>
        </body>
        </html>
        <?php
    } else {
        alertarERedirecionar("E-mail não cadastrado em nossa base acadêmica.", "../esqueci_senha.php", "error");
    }
} else {
    header("Location: ../esqueci_senha.php");
    exit();
}
?>