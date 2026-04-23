<?php
require_once 'includes/funcoes.php';
verificarLogado();
require_once 'includes/conexao.php'; 

$sql = "SELECT nome, xp, patente, foto FROM usuarios ORDER BY xp DESC LIMIT 10";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Quadro de Honra - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container">
        <header style="text-align: center; padding: 60px 0 40px;">
            <h1 class="glow-text" style="font-size: 2.5rem;">QUADRO DE HONRA</h1>
            <p style="color: var(--neon-cyan); letter-spacing: 2px; text-transform: uppercase; font-size: 0.8rem; opacity: 0.8;">Os 10 melhores acadêmicos do Campus</p>
        </header>

        <div style="max-width: 900px; margin: 0 auto;">
            <table class="ranking-table">
                <thead>
                    <tr style="color: var(--neon-cyan); font-family: 'Orbitron'; font-size: 0.7rem; letter-spacing: 2px;">
                        <th style="padding: 0 20px 10px;">POS</th>
                        <th style="padding: 0 20px 10px;">ESTUDANTE</th>
                        <th style="padding: 0 20px 10px;">GRAU</th>
                        <th style="padding: 0 20px 10px; text-align: right;">CONHECIMENTO (XP)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $pos = 1;
                    if ($result && $result->num_rows > 0):
                        while($row = $result->fetch_assoc()): 
                            $foto_rank = (!empty($row['foto'])) ? $row['foto'] : 'assets/img/default.png';
                            $isTop = ($pos == 1);
                        ?>
                        <tr class="<?php echo $isTop ? 'top-1' : ''; ?>" style="<?php echo $isTop ? 'border: 1px solid var(--neon-gold);' : ''; ?>">
                            <td style="font-family: 'Orbitron'; font-weight: bold; color: <?php echo $isTop ? 'var(--neon-gold)' : 'var(--neon-cyan)'; ?>;">
                                #<?php echo str_pad($pos++, 2, '0', STR_PAD_LEFT); ?>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="width: 45px; height: 45px; border-radius: 50%; border: 2px solid <?php echo $isTop ? 'var(--neon-gold)' : 'var(--neon-cyan)'; ?>; background: url('<?php echo $foto_rank; ?>') center/cover; flex-shrink: 0;"></div>
                                    <span style="font-weight: bold; font-size: 1.1rem; <?php echo $isTop ? 'color: var(--neon-gold);' : ''; ?>"><?php echo htmlspecialchars($row['nome']); ?></span>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-dim);"><?php echo htmlspecialchars($row['patente']); ?></span>
                            </td>
                            <td style="text-align: right; font-family: 'Orbitron'; font-weight: bold; color: var(--neon-cyan);">
                                <?php echo number_format($row['xp'], 0, '', '.'); ?>
                            </td>
                        </tr>
                        <?php endwhile; 
                    else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; opacity: 0.5;">Nenhum registro acadêmico encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="text-align: center; margin: 50px 0;">
            <a href="dashboard.php" class="btn">Retornar ao Terminal</a>
        </div>
    </div>

    <footer style="padding: 40px 0; text-align: center; opacity: 0.3;">
        <p>&copy; 2026 Arcade Language | Academic Management System</p>
    </footer>
</body>
</html>