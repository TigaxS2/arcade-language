<?php
// Ativa a exibição de erros para descobrirmos o que está travando
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // 1. Aqui você conectaria ao seu banco de dados
    // 2. Verificaria se o e-mail existe
    // 3. Enviaria o e-mail real

    // Por enquanto, vamos simular que deu certo e avisar o usuário:
    echo "<!DOCTYPE html>
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' href='../assets/css/style.css'>
        <title>Recuperação Enviada</title>
    </head>
    <body class='area-fundo'>
        <section class='auth-container'>
            <h1 class='cyber-title'>E-MAIL ENVIADO!</h1>
            <div class='login-card'>
                <p style='text-align: center; color: #fff;'>Se o e-mail <strong>$email</strong> estiver cadastrado, você receberá um link de resgate em instantes.</p>
                <br>
                <a href='../login.php' class='btn' style='text-decoration: none; display: block; text-align: center;'>Voltar ao Login</a>
            </div>
        </section>
    </body>
    </html>";
} else {
    // Se tentarem acessar o arquivo direto sem postar o formulário, manda de volta
    header("Location: ../esqueci_senha.php");
    exit();
}