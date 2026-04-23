<?php
require_once 'includes/funcoes.php';
verificarLogado();
require_once 'includes/conexao.php';

// Busca dados atualizados
$stmt = $conn->prepare("SELECT email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$dados = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Configurações Acadêmicas - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .perfil-layout { display: flex; justify-content: center; padding: 60px 20px; }
        .perfil-card { width: 100%; max-width: 500px; }
        .section-divider { 
            border-bottom: 1px solid var(--glass-border); 
            margin: 30px 0 20px; 
            padding-bottom: 10px;
            color: var(--neon-cyan);
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container perfil-layout">
        <div class="perfil-card">
            <h1 class="glow-text" style="text-align: center; font-size: 2rem; margin-bottom: 40px;">PERFIL ACADÊMICO</h1>
            
            <form action="auth/update_perfil.php" method="POST" enctype="multipart/form-data">
                
                <!-- AVATAR UPLOAD -->
                <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 40px; width: 100%;">
                    <div class="avatar-container" style="width: 160px; height: 160px; margin: 0; position: relative; display: flex; justify-content: center; align-items: center; border-radius: 50%; padding: 0; overflow: hidden;">
                        <img src="<?php echo $_SESSION['foto']; ?>" class="status-avatar" id="previewFoto" 
                             style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;"
                             onerror="this.src='assets/img/default.png';">
                    </div>
                    <label for="foto_perfil" class="btn btn-secondary" style="padding: 8px 20px; font-size: 0.7rem; margin-top: 20px;">Alterar Avatar</label>
                    <input type="file" name="foto_perfil" id="foto_perfil" hidden onchange="previewImagem(event)">
                </div>

                <div class="section-divider">Dados de Identificação</div>
                
                <div class="input-group">
                    <label>Nome Acadêmico</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($_SESSION['nome']); ?>" required>
                </div>

                <div class="input-group">
                    <label>E-mail de Contato</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($dados['email']); ?>" required>
                </div>

                <div class="section-divider">Segurança da Chave</div>
                
                <div class="input-group">
                    <label>Nova Chave de Acesso (Deixe em branco para manter)</label>
                    <input type="password" name="nova_senha" placeholder="••••••••">
                </div>

                <div class="input-group">
                    <label>Confirmar Nova Chave</label>
                    <input type="password" name="confirma_senha" placeholder="••••••••">
                </div>

                <button type="submit" class="btn" style="width: 100%; margin-top: 20px;">ATUALIZAR REGISTROS</button>
            </form>

            <div style="text-align: center; margin-top: 30px;">
                <a href="dashboard.php" class="cyber-link-bold">← Voltar ao Terminal Acadêmico</a>
            </div>
        </div>
    </div>

    <footer style="padding: 60px 0; text-align: center; opacity: 0.3;">
        <p>&copy; 2026 Arcade Language | Academic Management System</p>
    </footer>

    <script>
        function previewImagem(event) {
            const reader = new FileReader();
            reader.onload = () => document.getElementById('previewFoto').src = reader.result;
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>