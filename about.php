<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>About - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Ajustes específicos para diminuir os vãos */
        header { padding: 40px 20px 20px; } /* Diminui o topo */
        
        .about-main {
            display: flex;
            justify-content: center;
            margin-bottom: 30px; /* Reduzido de 50px */
        }

        .mission-card {
            background: rgba(20, 20, 30, 0.6);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(0, 240, 255, 0.4);
            border-radius: 20px;
            padding: 25px; /* Mais compacto */
            max-width: 850px;
            text-align: center;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        /* Reduz o espaço entre os cards de baixo e o topo */
        .cards { 
            margin-top: 10px; 
            gap: 20px; 
        }

        .developer-section {
            margin-top: 40px !important; /* Reduzido de 80px */
            text-align: center;
            padding-bottom: 40px;
        }

        .level-badge { margin: 5px; }
    </style>
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <header>
        <h1 class="glow-text">QUEM SOMOS</h1>
        <p>Elevando o aprendizado ao próximo nível 🚀</p>
    </header>

    <div class="container">
        <div class="about-main">
            <div class="mission-card">
                <h3 style="color: #00f0ff; margin-bottom: 15px;">[ MISSÃO DO SISTEMA ]</h3>
                <p style="line-height: 1.6; color: rgba(255,255,255,0.9); font-size: 1.05rem;">
                    O <strong>Arcade Language</strong> é uma plataforma experimental desenvolvida por acadêmicos de 
                    <em>Análise e Desenvolvimento de Sistemas</em>. Nossa tese é simples: o cérebro humano retém 
                    90% mais informação quando está em estado de "Flow". 
                    Misturamos algoritmos de progressão com elementos de RPG para transformar o cansaço do estudo em dopamina de vitória.
                </p>
            </div>
        </div>

        <div class="cards">
            <div class="card">
                <h4 style="color: #00f0ff; margin-bottom: 10px;">🎮 Gamificação</h4>
                <p style="font-size: 0.9rem;">Lógica de XP, Patentes e Ranking em tempo real para estimular a retenção.</p>
            </div>
            <div class="card">
                <h4 style="color: #00f0ff; margin-bottom: 10px;">💻 Stack</h4>
                <p style="font-size: 0.9rem;">PHP Estruturado, MySQL e CSS Moderno com Glassmorphism.</p>
            </div>
            <div class="card">
                <h4 style="color: #00f0ff; margin-bottom: 10px;">🛡️ Segurança</h4>
                <p style="font-size: 0.9rem;">Proteção contra SQL Injection e tratamento rigoroso de sessões.</p>
            </div>
        </div>

        <div class="developer-section">
            <h2 style="color: #fff; margin-bottom: 20px; font-size: 1.5rem;">DEVELOPER LOG</h2>
            <div class="level-badge">Tiago - Lead Developer</div>
            <div class="level-badge">Equipe ADS - Fullstack Squad</div>
            <p style="margin-top: 15px; opacity: 0.6; font-size: 0.85rem;">Status: Alpha Version 1.0.2 - XAMPP Environment</p>
        </div>
    </div>

    <footer style="margin-top: 20px;">
        <p>&copy; 2026 Arcade Language | ADS Project</p>
    </footer>
</body>
</html>