<?php
session_start();
require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

// Log de depuração
function debug_log($msg) {
    if (defined('APP_ENV') && APP_ENV === 'production') return;
    file_put_contents(__DIR__ . '/debug_xp.log', date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, FILE_APPEND);
}

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    debug_log("ERRO: Sessão não encontrada.");
    echo json_encode(['success' => false, 'message' => 'Sessão expirada']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
debug_log("Requisição recebida do Usuário ID: " . $_SESSION['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($data['token_seguranca']) && $data['token_seguranca'] === 'ARCADIUS_LAB_XP_2026') {
        
        // Validação CSRF
        if (!validarCSRF($data['csrf_token'] ?? '')) {
            debug_log("ERRO: Falha na validação CSRF.");
            echo json_encode(['success' => false, 'message' => 'Erro de segurança (CSRF)']);
            exit;
        }

        $usuario_id = (int)$_SESSION['id'];
        $ganho_xp = 10;

        // 1. Atualiza XP do usuário
        $stmt_xp = $conn->prepare("UPDATE usuarios SET xp = xp + ? WHERE id = ?");
        $stmt_xp->bind_param("ii", $ganho_xp, $usuario_id);
        
        if ($stmt_xp->execute()) {
            debug_log("XP atualizado no banco para o usuário $usuario_id");
            $stmt_xp->close();

            // 2. Busca XP atualizado e recalcula patente
            $stmt_busca = $conn->prepare("SELECT xp FROM usuarios WHERE id = ?");
            $stmt_busca->bind_param("i", $usuario_id);
            $stmt_busca->execute();
            $user = $stmt_busca->get_result()->fetch_assoc();
            $stmt_busca->close();

            $novoXP = $user['xp'];
            $patente = calcularNivelEscolaridade($novoXP);
            debug_log("Novo XP: $novoXP, Nova Patente: $patente");

            // 3. Atualiza patente
            $stmt_pat = $conn->prepare("UPDATE usuarios SET patente = ? WHERE id = ?");
            $stmt_pat->bind_param("si", $patente, $usuario_id);
            $stmt_pat->execute();
            $stmt_pat->close();

            // 4. Atualiza a sessão
            $_SESSION['xp'] = $novoXP;
            $_SESSION['patente'] = $patente;

            echo json_encode([
                'success' => true, 
                'novo_xp' => $novoXP, 
                'patente' => $patente
            ]);
        } else {
            debug_log("ERRO ao executar SQL de UPDATE: " . $conn->error);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar banco']);
        }
    } else {
        debug_log("ERRO: Token de segurança inválido ou ausente.");
        echo json_encode(['success' => false, 'message' => 'Token inválido']);
    }
} else {
    debug_log("ERRO: Método não é POST.");
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
}
?>