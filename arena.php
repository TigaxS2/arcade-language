<?php
require_once 'includes/funcoes.php';
verificarLogado();
require_once 'includes/conexao.php';

$usuario_id = (int)$_SESSION['id'];

// Busca uma pergunta que o usuário ainda não respondeu corretamente usando Prepared Statements
$query = "
    SELECT * FROM perguntas 
    WHERE id NOT IN (
        SELECT pergunta_id FROM log_respostas 
        WHERE usuario_id = ? AND is_correto = 1
    ) 
    ORDER BY RAND() LIMIT 1
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$pergunta = $resultado->fetch_assoc();
$stmt->close();

// Conta quantas faltam
$total_restante_query = "
    SELECT COUNT(*) as total FROM perguntas 
    WHERE id NOT IN (
        SELECT pergunta_id FROM log_respostas 
        WHERE usuario_id = ? AND is_correto = 1
    )
";
$stmt_count = $conn->prepare($total_restante_query);
$stmt_count->bind_param("i", $usuario_id);
$stmt_count->execute();
$total_restante = $stmt_count->get_result()->fetch_assoc()['total'];
$stmt_count->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Laboratório - Arcadius Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .lab-page { display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: calc(100vh - 100px); padding: 20px; }
        .quiz-card { width: 100%; max-width: 700px; }
        .option-btn { 
            display: block; width: 100%; padding: 18px; margin-bottom: 15px; 
            background: rgba(255,255,255,0.03); border: 1px solid var(--glass-border);
            color: #fff; text-align: left; cursor: pointer; transition: 0.2s;
            font-family: 'Rajdhani', sans-serif; font-size: 1.1rem;
        }
        .option-btn:hover { 
            border-color: var(--neon-cyan); background: rgba(0, 240, 255, 0.05);
            transform: translateX(5px);
        }
        .option-letter { color: var(--neon-cyan); font-weight: bold; margin-right: 15px; font-family: 'Orbitron'; }
        .reward-badge { 
            display: inline-block; padding: 5px 15px; background: var(--neon-cyan); color: #000;
            font-weight: bold; font-size: 0.7rem; margin-bottom: 20px; font-family: 'Orbitron';
        }
        .progress-info {
            color: var(--text-dim);
            font-size: 0.8rem;
            margin-top: 15px;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container lab-page">
        <div class="quiz-card card">
            <?php if ($pergunta): ?>
                <div style="text-align: center;">
                    <div class="reward-badge">RECOMPENSA: +<?php echo (int)$pergunta['xp_recompensa']; ?> XP ACADÊMICO</div>
                    <h2 style="margin-bottom: 40px; font-size: 1.8rem; line-height: 1.3;"><?php echo htmlspecialchars($pergunta['pergunta']); ?></h2>
                </div>
                
                <form action="auth/processa_xp.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo gerarCSRF(); ?>">
                    <input type="hidden" name="pergunta_id" value="<?php echo (int)$pergunta['id']; ?>">
                    
                    <button type="submit" name="resposta" value="A" class="option-btn"><span class="option-letter">A</span> <?php echo htmlspecialchars($pergunta['opcao_a']); ?></button>
                    <button type="submit" name="resposta" value="B" class="option-btn"><span class="option-letter">B</span> <?php echo htmlspecialchars($pergunta['opcao_b']); ?></button>
                    <button type="submit" name="resposta" value="C" class="option-btn"><span class="option-letter">C</span> <?php echo htmlspecialchars($pergunta['opcao_c']); ?></button>
                    <button type="submit" name="resposta" value="D" class="option-btn"><span class="option-letter">D</span> <?php echo htmlspecialchars($pergunta['opcao_d']); ?></button>
                </form>

                <div style="text-align: center;" class="progress-info">
                    Módulos restantes neste nível: <?php echo (int)$total_restante; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 40px;">
                    <h2 style="color: var(--neon-cyan); margin-bottom: 20px;">EXCELÊNCIA ACADÊMICA!</h2>
                    <p>Você completou todos os módulos disponíveis no Campus no momento.</p>
                    <a href="dashboard.php" class="btn" style="margin-top: 30px;">Retornar ao Terminal</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <footer style="padding: 60px 0; text-align: center; opacity: 0.3;">
        <p>&copy; 2026 Arcadius Language | Academic Management System</p>
    </footer>
</body>
</html>