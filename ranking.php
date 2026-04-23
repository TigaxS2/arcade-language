<?php
require_once 'includes/conexao.php'; 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Busca os 10 melhores jogadores por XP
$sql = "SELECT nome, xp, patente FROM usuarios ORDER BY xp DESC LIMIT 10";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Ranking Geral - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .ranking-table {
            width: 100%;
            max-width: 800px;
            margin: 40px auto 20px auto; /* Reduzi um pouco a margem inferior */
            border-collapse: collapse;
            background: rgba(20, 20, 30, 0.6);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid rgba(0, 240, 255, 0.3);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        th, td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        
        th { 
            background: rgba(168, 85, 247, 0.8); 
            color: white; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        td { color: rgba(255,255,255,0.9); }

        tr:hover { background: rgba(0, 240, 255, 0.05); }

        .top-1 { 
            color: #ffd700 !important; 
            font-weight: bold; 
            background: rgba(255, 215, 0, 0.05);
        }
        
        .top-1 td { color: #ffd700 !important; }

        /* Estilo para a área do botão */
        .ranking-footer {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .btn-voltar {
            display: inline-block;
            padding: 12px 35px;
            background: transparent;
            color: #00f0ff;
            border: 2px solid #00f0ff;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
            transition: 0.3s;
            box-shadow: 0 0 10px rgba(0, 240, 255, 0.2);
        }

        .btn-voltar:hover {
            background: #00f0ff;
            color: #1a0a2a;
            box-shadow: 0 0 20px #00f0ff;
            transform: scale(1.05);
        }
    </style>
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <div class="container">
        <header style="text-align: center; margin-top: 50px;">
            <h1 class="glow-text">QUADRO DE HONRA</h1>
            <p style="color: rgba(255,255,255,0.7);">Os estudantes mais lendários da plataforma.</p>
        </header>

        <table class="ranking-table">
            <thead>
                <tr>
                    <th>Posição</th>
                    <th>Nome</th>
                    <th>Nível</th>
                    <th>XP</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $pos = 1;
                if ($result->num_rows > 0):
                    while($row = $result->fetch_assoc()): ?>
                    <tr class="<?php echo ($pos == 1) ? 'top-1' : ''; ?>">
                        <td>#<?php echo $pos++; ?></td>
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td><?php echo htmlspecialchars($row['patente']); ?></td>
                        <td><span style="color: #00f0ff;"><?php echo $row['xp']; ?></span></td>
                    </tr>
                    <?php endwhile; 
                else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Nenhum herói encontrado ainda.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="ranking-footer">
            <a href="dashboard.php" class="btn-voltar">Voltar</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Arcade Language | Academic Management System</p>
    </footer>
</body>
</html>