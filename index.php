<?php
require_once 'includes/funcoes.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Se já estiver logado, vai direto para o dashboard
if (isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arcade Language - O Futuro da Fluência Acadêmica</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .hero {
            padding: 100px 20px;
            text-align: center;
            background: radial-gradient(circle at center, rgba(0, 240, 255, 0.05) 0%, transparent 70%);
        }
        .hero h1 {
            font-size: 4.5rem;
            margin-bottom: 15px;
            letter-spacing: 5px;
        }
        .hero p {
            font-size: 1.4rem;
            color: var(--neon-cyan);
            text-transform: uppercase;
            letter-spacing: 3px;
            opacity: 0.8;
            margin-bottom: 40px;
        }
        .levels-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 50px;
        }
        .level-step-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--glass-border);
            padding: 30px 20px;
            text-align: center;
            transition: var(--transition-fast);
        }
        .level-step-card:hover {
            border-color: var(--neon-cyan);
            background: rgba(0, 240, 255, 0.05);
            transform: translateY(-5px);
        }
        .level-number {
            display: block;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.7rem;
            color: var(--neon-cyan);
            margin-bottom: 10px;
            opacity: 0.6;
        }
        .mini-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin: 80px 0;
        }
        .cta-section {
            text-align: center;
            padding: 80px 20px;
            border-top: 1px solid var(--glass-border);
        }
    </style>
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <header class="hero">
        <h1 class="glow-text">ARCADE LANGUAGE</h1>
        <p>A Evolução Acadêmica de Idiomas</p>
        <div style="margin-top: 20px;">
            <a href="login.php" class="btn" style="padding: 18px 50px; font-size: 1rem;">ACESSAR TERMINAL</a>
        </div>
    </header>

    <div class="container">
        
        <section style="text-align: center; padding: 40px 0;">
            <div class="section-divider" style="border-bottom: 1px solid var(--glass-border); margin: 30px 0 40px; padding-bottom: 10px; color: var(--neon-cyan); font-size: 0.8rem; letter-spacing: 4px; text-transform: uppercase;">Protocolo de Aprendizado</div>
            
            <div style="max-width: 900px; margin: 0 auto;">
                <h2 style="font-size: 2.2rem; margin-bottom: 25px;">DOMINE NOVOS HORIZONTES</h2>
                <p style="font-size: 1.1rem; line-height: 1.8; opacity: 0.8;">
                    No Arcade Language, transformamos o aprendizado de línguas em uma experiência imersiva e competitiva. 
                    Nossa plataforma utiliza algoritmos acadêmicos para garantir que sua jornada do "Iniciante" ao "Mestre" seja constante, 
                    recompensadora e, acima de tudo, épica.
                </p>
            </div>

            <div class="levels-grid">
                <div class="level-step-card">
                    <span class="level-number">MÓDULO 01</span>
                    <h4 style="font-family: 'Orbitron';">INICIANTE</h4>
                </div>
                <div class="level-step-card">
                    <span class="level-number">MÓDULO 02</span>
                    <h4 style="font-family: 'Orbitron';">EXPLORADOR</h4>
                </div>
                <div class="level-step-card">
                    <span class="level-number">MÓDULO 03</span>
                    <h4 style="font-family: 'Orbitron';">COMUNICADOR</h4>
                </div>
                <div class="level-step-card">
                    <span class="level-number">MÓDULO 04</span>
                    <h4 style="font-family: 'Orbitron';">FLUENTE</h4>
                </div>
                <div class="level-step-card">
                    <span class="level-number">MÓDULO 05</span>
                    <h4 style="font-family: 'Orbitron';">MESTRE</h4>
                </div>
            </div>
        </section>

        <div class="mini-cards-grid"> 
            <div class="card">
                <h3 style="color: var(--neon-cyan);">🎯 LABORATORIAIS</h3>
                <p style="margin: 15px 0 25px; opacity: 0.7;">Participe de simulações de idiomas em tempo real. Cada acerto gera XP e eleva seu grau acadêmico.</p>
                <a href="login.php" class="btn" style="width: 100%;">INICIAR DESAFIO</a>
            </div>
            <div class="card">
                <h3 style="color: var(--neon-purple);">🧠 CONHECIMENTO</h3>
                <p style="margin: 15px 0 25px; opacity: 0.7;">Módulos estruturados para cobrir desde gramática básica até conversação avançada de alta tecnologia.</p>
                <a href="about.php" class="btn btn-secondary" style="width: 100%;">VER MÉTODO</a>
            </div>
            <div class="card">
                <h3 style="color: var(--neon-magenta);">🏆 HONRARIAS</h3>
                <p style="margin: 15px 0 25px; opacity: 0.7;">Sua evolução é pública. Dispute o topo do ranking com os melhores estudantes do mundo.</p>
                <a href="ranking_geral.php" class="btn" style="width: 100%; border-color: var(--neon-magenta); color: var(--neon-magenta);">QUADRO DE HONRA</a>
            </div>
        </div>

        <section class="cta-section">
            <h2 style="font-size: 2.5rem; margin-bottom: 20px;" class="glow-text">PRONTO PARA A MATRÍCULA?</h2>
            <p style="margin-bottom: 40px; opacity: 0.8; letter-spacing: 1px;">Inicie seu protocolo acadêmico hoje mesmo e mude seu futuro.</p>
            <a href="cadastro.php" class="btn" style="padding: 15px 60px;">CRIAR REGISTRO ACADÊMICO</a>
        </section>

    </div>

    <footer style="padding: 60px 0; text-align: center; opacity: 0.3;">
        <p>&copy; 2026 Arcade Language | Academic Management System</p>
    </footer>
</body>
</html>