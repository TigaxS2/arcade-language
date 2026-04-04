<?php
session_start();
require_once '../includes/conexao.php'; // CORRIGIDO: Era db.php
require_once '../includes/funcoes.php';

if (!isset($_SESSION['id'])) { 
    exit("Acesso negado"); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resposta = $_POST['resposta'];
    $usuario_id = (int)$_SESSION['id']; 

    if ($resposta == "correto") {
        $ganho_xp = 50;
        
        // Atualiza XP com SQL seguro (Prepared Statements)
        $stmt_xp = $conn->prepare("UPDATE usuarios SET xp = xp + ? WHERE id = ?");
        $stmt_xp->bind_param("ii", $ganho_xp, $usuario_id);
        $stmt_xp->execute();
        $stmt_xp->close();
        
        // Busca XP atualizado
        $stmt_busca = $conn->prepare("SELECT xp FROM usuarios WHERE id = ?");
        $stmt_busca->bind_param("i", $usuario_id);
        $stmt_busca->execute();
        $user = $stmt_busca->get_result()->fetch_assoc();
        $stmt_busca->close();
        
        $novoXP = $user['xp'];
        $patente = calcularPatente($novoXP); // Usa a função centralizada
        
        // Atualiza patente se mudou
        $stmt_pat = $conn->prepare("UPDATE usuarios SET patente = ? WHERE id = ?");
        $stmt_pat->bind_param("si", $patente, $usuario_id);
        $stmt_pat->execute();
        $stmt_pat->close();
        
        // Atualiza sessão
        $_SESSION['xp'] = $novoXP;
        $_SESSION['patente'] = $patente;

        alertarERedirecionar("BOOOM! +$ganho_xp XP. Sua patente agora é: $patente", "../dashboard.php");
    } else {
        alertarERedirecionar("Resposta incorreta! Estude mais, recruta.", "../arena.php");
    }
}
$conn->close();
?>