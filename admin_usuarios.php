<?php
require_once 'includes/funcoes.php';
verificarAdmin(); 
require_once 'includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)$_POST['user_id'];
    $novo_xp = (int)$_POST['xp'];
    $nivel = calcularNivelEscolaridade($novo_xp);

    $stmt = $conn->prepare("UPDATE usuarios SET xp = ?, patente = ? WHERE id = ?");
    $stmt->bind_param("isi", $novo_xp, $nivel, $user_id);
    if ($stmt->execute()) {
        alertarERedirecionar("XP Acadêmico atualizado!", "admin_usuarios.php", "success");
    }
}

$usuarios = $conn->query("SELECT id, nome, email, xp, patente, nivel_acesso FROM usuarios ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Estudantes - Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <div class="container">
        <header style="padding: 40px 0; text-align: center;">
            <h1 class="glow-text">Gestão de Estudantes</h1>
            <a href="dashboard.php" class="cyber-link-bold">← Voltar ao Terminal</a>
        </header>

        <table class="ranking-table">
            <thead>
                <tr style="text-align: left; color: var(--neon-cyan);">
                    <th style="padding: 15px;">Nome</th>
                    <th>E-mail</th>
                    <th>Tipo</th>
                    <th>XP Acadêmico</th>
                    <th>Grau</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td style="font-weight: bold;"><?php echo htmlspecialchars($row['nome']); ?></td>
                    <td style="opacity: 0.7;"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><span style="font-size: 0.8rem; border: 1px solid var(--neon-gold); padding: 2px 8px; border-radius: 10px; color: var(--neon-gold);"><?php echo strtoupper($row['nivel_acesso']); ?></span></td>
                    <form action="admin_usuarios.php" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <td><input type="number" name="xp" value="<?php echo $row['xp']; ?>" style="width: 80px; padding: 5px; background: rgba(0,0,0,0.3); border: 1px solid var(--neon-cyan); color: white; border-radius: 5px;"></td>
                        <td><?php echo $row['patente']; ?></td>
                        <td><button type="submit" class="btn" style="padding: 5px 15px; font-size: 0.8rem;">Salvar</button></td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer style="padding: 60px 0; text-align: center; opacity: 0.3;">
        <p>&copy; 2026 Arcade Language | Academic Management System</p>
    </footer>
</body>
</html>