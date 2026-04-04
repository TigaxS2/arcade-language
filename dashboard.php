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
    <title>Painel - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* CSS EXCLUSIVO DA DASHBOARD */
        .dashboard-layout {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
            padding: 50px 20px;
            gap: 30px;
        }

        /* Card de Perfil à Esquerda */
        .user-status-card {
            background: rgba(20, 20, 30, 0.6);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(0, 240, 255, 0.4);
            border-radius: 20px;
            padding: 30px;
            width: 100%;
            max-width: 350px;
            text-align: center;
            box-shadow: 0 0 25px rgba(0, 240, 255, 0.1);
        }

        .user-status-card .status-avatar {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 4px solid #00f0ff;
            object-fit: cover;
            margin-bottom: 15px;
            box-shadow: 0 0 20px #00f0ff;
        }

        .user-status-card h1 { 
            font-size: 1.8rem; 
            color: #fff; 
            text-shadow: 0 0 10px rgba(0, 240, 255, 0.5); 
        }

        .user-stats-list {
            list-style: none;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.8);
            text-align: left;
        }

        .user-stats-list li { margin-bottom: 10px; font-size: 1.1rem; }
        .stat-value { color: #00f0ff; font-weight: bold; }

        /* Área de Ações à Direita */
        .actions-container {
            flex: 1;
            max-width: 700px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        /* Card de Ação (Glassmorphism) */
        .action-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            transition: 0.3s;
        }

        .action-card:hover {
            border-color: #00f0ff;
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.2);
            transform: translateY(-5px);
        }

        .action-card h3 { color: #00f0ff; margin-bottom: 10px; font-size: 1.4rem; }
        .action-card p { color: rgba(255, 255, 255, 0.7); margin-bottom: 20px; line-height: 1.4; }

        /* Ajuste do botão dentro da action-card */
        .action-card .btn {
            width: 100%;
            text-align: center;
        }

        .btn-secondary {
            background: rgba(0, 240, 255, 0.1);
            color: #00f0ff;
            border: 1px solid #00f0ff;
            box-shadow: none;
        }

        .btn-secondary:hover { background: #00f0ff; color: #1a0a2a; }

        @media (max-width: 900px) {
            .dashboard-layout { flex-direction: column; align-items: center; }
        }
    </style>
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <div class="container">
        <header style="margin-bottom: 0px;">
            <h1>Terminal de Comando</h1>
            <p>Seja bem-vindo de volta, <?php echo htmlspecialchars($primeiroNome); ?>! Próximo Boss te aguarda.</p>
        </header>

        <section class="dashboard-layout">
            
            <div class="user-status-card">
                <img src="<?php echo $_SESSION['foto']; ?>" class="status-avatar" alt="Sua Foto">
                <h1><?php echo htmlspecialchars($_SESSION['nome']); ?></h1>
                
                <ul class="user-stats-list">
                    <li>🎖️ Patente: <span class="stat-value"><?php echo htmlspecialchars($_SESSION['patente']); ?></span></li>
                    <li>💎 XP: <span class="stat-value"><?php echo htmlspecialchars($_SESSION['xp']); ?></span> / <span class="stat-value">500</span></li>
                    <li>✅ Dias Seguidos: <span class="stat-value">12</span></li>
                </ul>

                <a href="perfil.php" class="btn btn-secondary" style="margin-top: 20px;">Atualizar Perfil (Foto)</a>
            </div>

            <div class="actions-container">
                
                <div class="action-card">
                    <div>
                        <h3>🎮 ARENA DE DESAFIOS</h3>
                        <p>Participe de batalhas de vocabulário e gramática. Onde cada vitória é XP na conta!</p>
                    </div>
                    <a href="arena.php" class="btn">Entrar na Arena</a>
                </div>

                <div class="action-card">
                    <div>
                        <h3>🏆 QUADRO DE HONRA</h3>
                        <p>Veja quem são os grandes mestres dos idiomas. Onde você está no ranking?</p>
                    </div>
                    <a href="ranking_geral.php" class="btn btn-secondary">Ver Quadro de Honra</a>
                </div>

            </div>

        </section>
    </div>

    <footer>
        <p>&copy; 2026 Arcade Language | ADS Project</p>
    </footer>
</body>
</html>