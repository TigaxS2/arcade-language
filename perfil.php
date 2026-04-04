<?php
session_start();
if (!isset($_SESSION['id'])) { header("Location: login.php"); exit(); }
require_once 'includes/conexao.php';

// Busca dados atualizados do banco para garantir que o e-mail apareça
$stmt = $conn->prepare("SELECT email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$dados = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil - Arcade Language</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .perfil-card {
            background: rgba(20, 20, 30, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 240, 255, 0.4);
            padding: 35px;
            width: 100%;
            max-width: 550px;
            border-radius: 20px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }

        .section-title {
            color: #00f0ff;
            font-size: 0.9rem;
            letter-spacing: 2px;
            margin: 20px 0 10px;
            border-bottom: 1px solid rgba(0, 240, 255, 0.2);
            padding-bottom: 5px;
            text-transform: uppercase;
        }

        .input-group { margin-bottom: 15px; text-align: left; }
        label { color: rgba(255,255,255,0.7); font-size: 0.85rem; display: block; margin-bottom: 5px; }
        
        input[readonly] { background: rgba(255,255,255,0.05); cursor: not-allowed; border-color: rgba(255,255,255,0.1); }

        .btn-update { width: 100%; margin-top: 15px; }
        
        .perfil-foto-preview {
            width: 120px; height: 120px;
            border-radius: 50%;
            border: 3px solid #00f0ff;
            object-fit: cover;
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.4);
        }
    </style>
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <section class="auth-section">
        <div class="perfil-card">
            <h2 class="glow-text" style="text-align: center; font-size: 1.8rem;">CONFIGURAÇÕES DE CONTA</h2>
            
            <form action="auth/update_perfil.php" method="POST" enctype="multipart/form-data">
                
                <div style="text-align: center; margin-bottom: 25px;">
                    <img src="<?php echo $_SESSION['foto']; ?>" class="perfil-foto-preview" id="previewFoto" 
                         onerror="this.src='assets/img/default.png';">
                    <div style="margin-top: 10px;">
                        <label for="foto_perfil" class="btn" style="padding: 5px 15px; font-size: 0.8rem; cursor: pointer;">Alterar Avatar</label>
                        <input type="file" name="foto_perfil" id="foto_perfil" hidden onchange="previewImagem(event)">
                    </div>
                </div>

                <div class="section-title">Dados Pessoais</div>
                
                <div class="input-group">
                    <label>Nome de Exibição:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($_SESSION['nome']); ?>" required>
                </div>

                <div class="input-group">
                    <label>E-mail de Recuperação:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($dados['email']); ?>" required>
                </div>

                <div class="section-title">Segurança (Trocar Senha)</div>
                <p style="font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 10px;">Deixe em branco para manter a senha atual.</p>
                
                <div class="input-group">
                    <label>Nova Senha:</label>
                    <input type="password" name="nova_senha" placeholder="••••••••">
                </div>

                <div class="input-group">
                    <label>Confirmar Nova Senha:</label>
                    <input type="password" name="confirma_senha" placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-update">Salvar Todas as Alterações</button>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <a href="dashboard.php" style="color: rgba(255,255,255,0.5); text-decoration: none; font-size: 0.9rem;">← Voltar ao Terminal</a>
            </div>
        </div>
    </section>

    <script>
        function previewImagem(event) {
            var reader = new FileReader();
            reader.onload = function(){
                document.getElementById('previewFoto').src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>