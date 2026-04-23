<?php
require_once 'includes/funcoes.php';
verificarLogado();

// Pega o primeiro nome para usar na mensagem
$primeiroNome = explode(" ", $_SESSION['nome'])[0];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Acadêmico - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <div class="container">
        <header style="padding: 40px 0; text-align: center;">
            <h1 class="glow-text" style="font-size: 2.5rem;">Terminal Acadêmico</h1>
            <p style="opacity: 0.8;">Seja bem-vindo, <?php echo htmlspecialchars($primeiroNome); ?>! Seu próximo desafio acadêmico está pronto.</p>
        </header>

        <section style="display: flex; gap: 30px; flex-wrap: wrap; justify-content: center; align-items: flex-start;">
            
            <!-- STATUS DO ESTUDANTE -->
            <div class="user-status-card" style="flex: 1; max-width: 350px; display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="avatar-container" style="margin-bottom: 20px;">
                    <?php 
                        $foto = (isset($_SESSION['foto']) && !empty($_SESSION['foto'])) ? $_SESSION['foto'] : 'assets/img/default.png';
                    ?>
                    <img src="<?php echo $foto; ?>" class="status-avatar" alt="" onerror="this.src='assets/img/default.png';">
                </div>
                
                <h2 style="text-align: center; margin-bottom: 20px; width: 100%;"><?php echo htmlspecialchars($_SESSION['nome']); ?></h2>
                
                <ul style="list-style: none; width: 100%;">
                    <li style="margin-bottom: 10px;">🎖️ Nível: <span style="color: var(--neon-cyan); font-weight: bold;"><?php echo htmlspecialchars($_SESSION['patente']); ?></span></li>
                    <li style="margin-bottom: 5px;">💎 XP: <span style="color: var(--neon-cyan); font-weight: bold;"><?php echo htmlspecialchars($_SESSION['xp']); ?></span> / 500</li>
                    
                    <div class="xp-progress-container">
                        <?php 
                            $percentual = ($_SESSION['xp'] / 500) * 100; 
                            if ($percentual > 100) $percentual = 100;
                        ?>
                        <div class="xp-progress-bar" style="width: <?php echo $percentual; ?>%;"></div>
                    </div>
                </ul>

                <a href="perfil.php" class="btn btn-secondary" style="width: 100%; margin-top: 20px;">Configurações de Aluno</a>
            </div>

            <!-- AÇÕES PRINCIPAIS -->
            <div style="flex: 2; min-width: 300px; display: grid; gap: 20px;">
                
                <div class="action-card">
                    <h3>🎮 LABORATÓRIO DE IDIOMAS</h3>
                    <p style="margin: 10px 0 20px; opacity: 0.8;">Participe de desafios interativos. Cada acerto acelera sua graduação acadêmica.</p>
                    <a href="arena.php" class="btn" style="width: 100%;">Acessar Laboratório</a>
                </div>

                <div class="action-card">
                    <h3>🏆 QUADRO DE HONRA</h3>
                    <p style="margin: 10px 0 20px; opacity: 0.8;">Confira sua posição no ranking global da faculdade.</p>
                    <a href="ranking_geral.php" class="btn btn-secondary" style="width: 100%;">Ver Ranking</a>
                </div>

                <?php if(isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] === 'admin'): ?>
                <div class="action-card" style="border-color: var(--neon-magenta);">
                    <h3 style="color: var(--neon-magenta);">🛠️ PAINEL DA FACULDADE</h3>
                    <p style="margin: 10px 0 20px; opacity: 0.8;">Gestão de questões e acompanhamento de estudantes.</p>
                    <div style="display: flex; gap: 10px;">
                        <a href="admin_perguntas.php" class="btn" style="flex: 1; background: var(--neon-magenta);">Questões</a>
                        <a href="admin_usuarios.php" class="btn btn-secondary" style="flex: 1; color: var(--neon-magenta); border-color: var(--neon-magenta);">Estudantes</a>
                    </div>
                </div>
                <?php endif; ?>

            </div>

        </section>
    </div>

    <footer style="padding: 60px 0; text-align: center; opacity: 0.4;">
        <p>&copy; 2026 Arcade Language | Academic Management System</p>
    </footer>
</body>
</html>