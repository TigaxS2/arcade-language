<?php
require_once 'includes/funcoes.php';
verificarAdmin();
require_once 'includes/conexao.php';

// 1. ESTATÍSTICAS GERAIS
$total_estudantes = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE nivel_acesso = 'user'")->fetch_assoc()['total'];
$questoes_ativas = $conn->query("SELECT COUNT(*) as total FROM perguntas")->fetch_assoc()['total'];

// 2. ACESSOS POR DIA (Últimos 7 dias)
$acessos_query = $conn->query("
    SELECT data_acesso, COUNT(*) as qtd 
    FROM log_acessos 
    GROUP BY data_acesso 
    ORDER BY data_acesso DESC 
    LIMIT 7
");

// 3. DESEMPENHO POR QUESTÃO
$questoes_stat = $conn->query("
    SELECT p.id, p.pergunta, 
    SUM(CASE WHEN lr.is_correto = 1 THEN 1 ELSE 0 END) as acertos,
    SUM(CASE WHEN lr.is_correto = 0 THEN 1 ELSE 0 END) as erros
    FROM perguntas p
    LEFT JOIN log_respostas lr ON p.id = lr.pergunta_id
    GROUP BY p.id
    ORDER BY COUNT(lr.id) DESC
");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gestão Acadêmica - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-box { text-align: center; padding: 30px; border-left: 4px solid var(--neon-cyan); }
        .stat-box h2 { font-size: 2.5rem; color: var(--neon-cyan); }
        .stat-box p { font-size: 0.8rem; letter-spacing: 2px; opacity: 0.7; }

        .dashboard-section { margin-bottom: 50px; }
        .dashboard-section h3 { margin-bottom: 25px; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px; }

        .bar-chart { width: 100%; display: flex; align-items: flex-end; gap: 10px; height: 150px; padding: 20px; background: rgba(0,0,0,0.2); border-radius: 10px; }
        .bar { flex: 1; background: var(--neon-cyan); position: relative; border-radius: 3px 3px 0 0; min-height: 5px; }
        .bar:hover { background: var(--neon-magenta); }
        .bar-label { position: absolute; bottom: -25px; left: 50%; transform: translateX(-50%); font-size: 0.6rem; white-space: nowrap; }
        .bar-value { position: absolute; top: -20px; left: 50%; transform: translateX(-50%); font-size: 0.7rem; font-weight: bold; }
    </style>
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <div class="container">
        <header style="padding: 40px 0; text-align: center;">
            <h1 class="glow-text">INTELIGÊNCIA ACADÊMICA</h1>
            <p style="opacity: 0.7;">Análise de desempenho e engajamento da faculdade</p>
        </header>

        <!-- CARDS DE RESUMO -->
        <div class="stats-grid">
            <div class="stat-box card">
                <h2><?php echo $total_estudantes; ?></h2>
                <p>ESTUDANTES ATIVOS</p>
            </div>
            <div class="stat-box card" style="border-color: var(--neon-purple);">
                <h2><?php echo $questoes_ativas; ?></h2>
                <p>QUESTÕES NO CAMPUS</p>
            </div>
            <div class="stat-box card" style="border-color: var(--neon-magenta);">
                <h2><?php 
                    $total_resp = $conn->query("SELECT COUNT(*) as total FROM log_respostas")->fetch_assoc()['total'];
                    echo $total_resp;
                ?></h2>
                <p>DESAFIOS CONCLUÍDOS</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; flex-wrap: wrap;">
            
            <!-- GRÁFICO DE ACESSOS -->
            <section class="dashboard-section card">
                <h3>📅 Engajamento (Últimos 7 dias)</h3>
                <div class="bar-chart">
                    <?php while($row = $acessos_query->fetch_assoc()): ?>
                        <?php $h = ($row['qtd'] / ($total_estudantes > 0 ? $total_estudantes : 1)) * 100 + 10; ?>
                        <div class="bar" style="height: <?php echo min($h, 100); ?>%;">
                            <span class="bar-value"><?php echo $row['qtd']; ?></span>
                            <span class="bar-label"><?php echo date('d/m', strtotime($row['data_acesso'])); ?></span>
                        </div>
                    <?php endwhile; ?>
                </div>
                <p style="font-size: 0.7rem; margin-top: 40px; opacity: 0.5;">* Número de acessos únicos por dia.</p>
            </section>

            <!-- GESTÃO DE QUESTÕES -->
            <section class="dashboard-section card">
                <h3>📊 Performance das Questões</h3>
                <div style="max-height: 300px; overflow-y: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <thead>
                            <tr style="color: var(--neon-cyan); text-align: left;">
                                <th style="padding: 10px 0;">Questão</th>
                                <th>Acertos</th>
                                <th>Erros</th>
                                <th>Taxa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($q = $questoes_stat->fetch_assoc()): ?>
                                <?php 
                                    $total = $q['acertos'] + $q['erros'];
                                    $taxa = ($total > 0) ? round(($q['acertos'] / $total) * 100) : 0;
                                ?>
                                <tr style="border-bottom: 1px solid var(--glass-border);">
                                    <td style="padding: 10px 0; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($q['pergunta']); ?></td>
                                    <td style="color: #00ff88;"><?php echo $q['acertos']; ?></td>
                                    <td style="color: #ff4d4d;"><?php echo $q['erros']; ?></td>
                                    <td style="font-weight: bold;"><?php echo $taxa; ?>%</td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="admin_usuarios.php" class="btn" style="margin-right: 15px;">Gerenciar Estudantes</a>
            <a href="admin_perguntas.php" class="btn btn-secondary">Editar Questões</a>
        </div>
    </div>

    <footer style="padding: 60px 0; text-align: center; opacity: 0.3;">
        <p>&copy; 2026 Arcade Language | Academic Management System</p>
    </footer>
</body>
</html>