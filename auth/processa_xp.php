<?php
session_start();
require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

if (!isset($_SESSION['id'])) { 
    exit("Acesso negado"); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pergunta_id = (int)$_POST['pergunta_id'];
    $resposta_usuario = $_POST['resposta'];
    $usuario_id = (int)$_SESSION['id'];

    // Busca a pergunta no banco para validar a resposta
    $stmt_p = $conn->prepare("SELECT resposta_correta, xp_recompensa FROM perguntas WHERE id = ?");
    $stmt_p->bind_param("i", $pergunta_id);
    $stmt_p->execute();
    $pergunta = $stmt_p->get_result()->fetch_assoc();
    $stmt_p->close();

    $is_correto = ($pergunta && $resposta_usuario === $pergunta['resposta_correta']) ? 1 : 0;

    // REGISTRA O RESULTADO DA RESPOSTA
    $stmt_log_resp = $conn->prepare("INSERT INTO log_respostas (usuario_id, pergunta_id, is_correto) VALUES (?, ?, ?)");
    $stmt_log_resp->bind_param("iii", $usuario_id, $pergunta_id, $is_correto);
    $stmt_log_resp->execute();

    if ($is_correto) {
        $ganho_xp = (int)$pergunta['xp_recompensa'];
        
        // Atualiza XP com SQL seguro
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
        $patente = calcularNivelEscolaridade($novoXP); 
        
        // Atualiza grau de escolaridade se mudou
        $stmt_pat = $conn->prepare("UPDATE usuarios SET patente = ? WHERE id = ?");
        $stmt_pat->bind_param("si", $patente, $usuario_id);
        $stmt_pat->execute();
        $stmt_pat->close();
        
        // Atualiza sessão
        $_SESSION['xp'] = $novoXP;
        $_SESSION['patente'] = $patente;

        alertarERedirecionar("PARABÉNS! +$ganho_xp XP. Resposta acadêmica correta!", "../arena.php", "success");
    } else {
        alertarERedirecionar("Resposta incorreta! Estude mais, acadêmico.", "../arena.php", "error");
    }
}
$conn->close();
?>