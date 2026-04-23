<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- LÓGICA DE EXCLUSÃO (EXECUTAR ANTES DE QUALQUER COISA) ---
require_once 'includes/conexao.php';
if (isset($_POST['excluir_id'])) {
    $id_excluir = (int)$_POST['excluir_id'];
    
    // Deleta logs e depois a pergunta
    $conn->query("DELETE FROM log_respostas WHERE pergunta_id = $id_excluir");
    $res = $conn->query("DELETE FROM perguntas WHERE id = $id_excluir");
    
    if ($res) {
        header("Location: admin_perguntas.php?msg=Pergunta Removida do Campus!&type=success");
        exit();
    }
}

require_once 'includes/funcoes.php';
verificarAdmin(); 

// --- LÓGICA DE SALVAR/EDITAR ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pergunta'])) {
    $id = $_POST['id'] ?? null;
    $pergunta = $_POST['pergunta'];
    $oa = $_POST['opcao_a'];
    $ob = $_POST['opcao_b'];
    $oc = $_POST['opcao_c'];
    $od = $_POST['opcao_d'];
    $resp = $_POST['resposta_correta'];
    $xp = (int)$_POST['xp_recompensa'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE perguntas SET pergunta=?, opcao_a=?, opcao_b=?, opcao_c=?, opcao_d=?, resposta_correta=?, xp_recompensa=? WHERE id=?");
        $stmt->bind_param("ssssssii", $pergunta, $oa, $ob, $oc, $od, $resp, $xp, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO perguntas (pergunta, opcao_a, opcao_b, opcao_c, opcao_d, resposta_correta, xp_recompensa) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $pergunta, $oa, $ob, $oc, $od, $resp, $xp);
    }
    
    if ($stmt->execute()) {
        header("Location: admin_perguntas.php?msg=Módulo Acadêmico Salvo com Sucesso!&type=success");
        exit();
    }
}

if (isset($_GET['msg'])) {
    // A lógica de exibição agora é automática via script.js e handleURLMessages
}

$perguntas = $conn->query("SELECT * FROM perguntas ORDER BY id DESC");
$edit_data = null;
if (isset($_GET['editar'])) {
    $id_edit = (int)$_GET['editar'];
    $edit_data = $conn->query("SELECT * FROM perguntas WHERE id = $id_edit")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Perguntas - Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .admin-form { background: rgba(20,20,30,0.8); padding: 25px; border-radius: 15px; border: 1px solid #00f0ff; margin-bottom: 40px; }
        .admin-form input, .admin-form select, .admin-form textarea { width: 100%; padding: 10px; margin-bottom: 15px; background: #0b0b1a; border: 1px solid rgba(0,240,255,0.3); color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .btn-delete-trigger { background: none; border: none; color: #ff4d4d; cursor: pointer; font-weight: bold; font-family: inherit; font-size: 1rem; padding: 0; }
        .btn-delete-trigger:hover { text-shadow: 0 0 10px #ff4d4d; }
    </style>
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <div class="container" style="padding-top: 50px;">
        <h1 class="glow-text">GERENCIAR PERGUNTAS</h1>
        <a href="dashboard.php" class="cyber-link-bold" style="display: inline-block; margin-bottom: 20px;">← Voltar ao Terminal</a>

        <div class="admin-form">
            <h3><?php echo $edit_data ? 'Editar Pergunta' : 'Nova Pergunta'; ?></h3>
            <form action="admin_perguntas.php" method="POST">
                <?php if($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>
                
                <textarea name="pergunta" placeholder="Digite a pergunta aqui..." required><?php echo $edit_data['pergunta'] ?? ''; ?></textarea>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <input type="text" name="opcao_a" placeholder="Opção A" value="<?php echo $edit_data['opcao_a'] ?? ''; ?>" required>
                    <input type="text" name="opcao_b" placeholder="Opção B" value="<?php echo $edit_data['opcao_b'] ?? ''; ?>" required>
                    <input type="text" name="opcao_c" placeholder="Opção C" value="<?php echo $edit_data['opcao_c'] ?? ''; ?>" required>
                    <input type="text" name="opcao_d" placeholder="Opção D" value="<?php echo $edit_data['opcao_d'] ?? ''; ?>" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <select name="resposta_correta" required>
                        <option value="">Selecione a resposta correta</option>
                        <option value="A" <?php echo ($edit_data && $edit_data['resposta_correta'] == 'A') ? 'selected' : ''; ?>>Opção A</option>
                        <option value="B" <?php echo ($edit_data && $edit_data['resposta_correta'] == 'B') ? 'selected' : ''; ?>>Opção B</option>
                        <option value="C" <?php echo ($edit_data && $edit_data['resposta_correta'] == 'C') ? 'selected' : ''; ?>>Opção C</option>
                        <option value="D" <?php echo ($edit_data && $edit_data['resposta_correta'] == 'D') ? 'selected' : ''; ?>>Opção D</option>
                    </select>
                    <input type="number" name="xp_recompensa" placeholder="XP Recompensa" value="<?php echo $edit_data['xp_recompensa'] ?? '50'; ?>" required>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; gap: 15px; margin-top: 20px;">
                    <button type="submit" class="btn" style="flex: 1;"><?php echo $edit_data ? 'Atualizar Pergunta' : 'Criar Pergunta'; ?></button>
                    <?php if($edit_data): ?>
                        <a href="admin_perguntas.php" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancelar</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Pergunta</th>
                    <th>Resp.</th>
                    <th>XP</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $perguntas->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars(substr($row['pergunta'], 0, 50)) . '...'; ?></td>
                    <td><?php echo $row['resposta_correta']; ?></td>
                    <td><?php echo $row['xp_recompensa']; ?></td>
                    <td style="display: flex; gap: 15px; align-items: center;">
                        <a href="admin_perguntas.php?editar=<?php echo $row['id']; ?>" class="cyber-link-bold">Editar</a>
                        
                        <form action="admin_perguntas.php" method="POST" onsubmit="return confirm('Deseja excluir?');" style="display: inline;">
                            <input type="hidden" name="excluir_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn-delete-trigger">Excluir</button>
                        </form>
                    </td>
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