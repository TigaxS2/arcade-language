<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sobre o Campus - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .about-layout { display: flex; justify-content: center; padding: 60px 20px; }
        .about-card { width: 100%; max-width: 800px; }
        .tech-list { 
            list-style: none; 
            margin: 20px 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        .tech-item {
            background: rgba(0, 240, 255, 0.05);
            border: 1px solid var(--glass-border);
            padding: 15px;
            text-align: center;
            border-radius: 4px;
            transition: var(--transition-fast);
        }
        .tech-item:hover {
            border-color: var(--neon-cyan);
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.1);
        }
        .tech-item i {
            display: block;
            font-size: 1.5rem;
            color: var(--neon-cyan);
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <div class="container about-layout">
        <div class="about-card card">
            <header style="text-align: center; margin-bottom: 40px;">
                <h1 class="glow-text" style="font-size: 2.5rem;">ARQUIVOS DO CAMPUS</h1>
                <p style="color: var(--neon-cyan); letter-spacing: 2px; text-transform: uppercase; font-size: 0.8rem; opacity: 0.8;">Manuscritos sobre a fundação do Arcade Language</p>
            </header>

            <div class="section-divider" style="border-bottom: 1px solid var(--glass-border); margin: 30px 0 20px; padding-bottom: 10px; color: var(--neon-cyan); font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase;">A Visão Acadêmica</div>
            <p style="line-height: 1.8; opacity: 0.9; margin-bottom: 20px;">
                O <strong>Arcade Language</strong> nasceu da necessidade de transformar a barreira linguística em uma jornada de exploração e conquista. 
                Utilizando os pilares da gamificação acadêmica, nosso objetivo é que cada estudante sinta a progressão real de seu conhecimento através de XP, 
                conquistas de novos graus e desafios constantes em nosso Laboratório.
            </p>

            <div class="section-divider" style="border-bottom: 1px solid var(--glass-border); margin: 30px 0 20px; padding-bottom: 10px; color: var(--neon-cyan); font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase;">Núcleo Tecnológico</div>
            <ul class="tech-list">
                <li class="tech-item">
                    <span style="display: block; font-weight: bold; color: var(--neon-cyan);">PHP 8.2</span>
                    <span style="font-size: 0.7rem; opacity: 0.7;">Back-end Seguro</span>
                </li>
                <li class="tech-item">
                    <span style="display: block; font-weight: bold; color: var(--neon-cyan);">MySQL 8</span>
                    <span style="font-size: 0.7rem; opacity: 0.7;">Base de Dados</span>
                </li>
                <li class="tech-item">
                    <span style="display: block; font-weight: bold; color: var(--neon-cyan);">CSS3 / HTML5</span>
                    <span style="font-size: 0.7rem; opacity: 0.7;">Interface Neon</span>
                </li>
                <li class="tech-item">
                    <span style="display: block; font-weight: bold; color: var(--neon-cyan);">JavaScript</span>
                    <span style="font-size: 0.7rem; opacity: 0.7;">Interatividade</span>
                </li>
            </ul>

            <div class="section-divider" style="border-bottom: 1px solid var(--glass-border); margin: 30px 0 20px; padding-bottom: 10px; color: var(--neon-cyan); font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase;">Módulos de Sistema</div>
            <p style="line-height: 1.6; opacity: 0.8; font-size: 0.95rem;">
                ✓ <strong>Laboratório de Idiomas:</strong> Testes dinâmicos com recompensa imediata.<br>
                ✓ <strong>Quadro de Honra:</strong> Ranking global para os melhores acadêmicos.<br>
                ✓ <strong>Gestão da Reitoria:</strong> Painel administrativo para controle total do Campus.<br>
                ✓ <strong>Perfil Customizável:</strong> Gerenciamento de avatar e dados do estudante.
            </p>

            <div style="text-align: center; margin-top: 50px;">
                <a href="index.php" class="btn">Retornar ao Início</a>
            </div>
        </div>
    </div>

    <footer style="padding: 60px 0; text-align: center; opacity: 0.3;">
        <p>&copy; 2026 Arcade Language | Academic Management System</p>
    </footer>
</body>
</html>