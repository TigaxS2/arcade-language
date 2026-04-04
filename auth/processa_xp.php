<?php
session_start();
// Certifique-se que o nome do arquivo abaixo está correto (db.php ou conexao.php)
require_once '../includes/db.php'; 

if (!isset($_SESSION['id'])) { 
    exit("Acesso negado"); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resposta = $_POST['resposta'];
    $usuario_id = (int)$_SESSION['id']; // Garante que é um número

    if ($resposta == "correto") {
        $ganho_xp = 50;
        
        // Atualiza XP
        $conn->query("UPDATE usuarios SET xp = xp + $ganho_xp WHERE id = $usuario_id");
        
        // Busca dados atualizados para a Gamificação
        $busca = $conn->query("SELECT xp FROM usuarios WHERE id = $usuario_id");
        $user = $busca->fetch_assoc();
        
        $novoXP = $user['xp']; // Corrigido aqui ($)
        
        // Lógica de Patentes
        $patente = "Iniciante";
        if ($novoXP >= 300) { 
            $patente = "Mestre"; 
        } elseif ($novoXP >= 150) { 
            $patente = "Intermediário"; 
        }
        
        // Atualiza a patente no banco
        $stmt = $conn->prepare("UPDATE usuarios SET patente = ? WHERE id = ?");
        $stmt->bind_param("si", $patente, $usuario_id);
        $stmt->execute();
        
        // Atualiza a SESSÃO para refletir na Dashboard sem precisar deslogar
        $_SESSION['xp'] = $novoXP;
        $_SESSION['patente'] = $patente;

        echo "<script>alert('BOOOM! +50 XP. Sua patente agora é: $patente'); window.location.href='../dashboard.php';</script>";
    } else {
        echo "<script>alert('Resposta incorreta! Estude mais, recruta.'); window.location.href='../arena.php';</script>";
    }
}