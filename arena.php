<?php
session_start();
if (!isset($_SESSION['id'])) { header("Location: index.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Arena - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .quiz-container { background: #1a1a2e; border: 2px solid #00f0ff; padding: 30px; border-radius: 15px; text-align: center; color: white; max-width: 500px; margin: 50px auto; }
        .option-btn { display: block; width: 100%; padding: 15px; margin: 10px 0; border: 1px solid #ff00ff; background: transparent; color: white; cursor: pointer; transition: 0.3s; border-radius: 5px; text-decoration: none; }
        .option-btn:hover { background: #ff00ff; color: black; font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="quiz-container">
        <h2 style="color: #00f0ff;">Batalha de Vocabulário</h2>
        <p style="font-size: 1.2rem;">O que significa o termo <strong>"Framework"</strong> em TI?</p>
        
        <form action="auth/processa_xp.php" method="POST">
            <button type="submit" name="resposta" value="errado" class="option-btn">A. Um tipo de monitor de alta resolução</button>
            
            <button type="submit" name="resposta" value="correto" class="option-btn">B. Um conjunto de ferramentas e bibliotecas prontas</button>
            
            <button type="submit" name="resposta" value="errado" class="option-btn">C. O nome dado ao gabinete do computador</button>
        </form>
    </div>
</body>
</html>