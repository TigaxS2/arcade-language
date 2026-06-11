<?php
// includes/ai_engine.php
require_once dirname(__FILE__) . '/config.php';

function solicitarMissoesIA($nivel = 'Iniciante', $tema = 'Geral') {
    global $conn;

    // Se o $conn não existir, tenta carregar
    if (!isset($conn)) {
        require_once dirname(__FILE__) . '/conexao.php';
    }

    // Nota: Recomenda-se mover a API Key para o config.php para maior segurança
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . GEMINI_API_KEY;

    $prompt = "Atue como o sistema central do Arcadius Language.
            Gere 3 perguntas de múltipla escolha para um aluno nível $nivel sobre o tema $tema.
            IMPORTANTE: Sempre coloque as frases ou palavras em inglês entre aspas simples.
            Exemplo: Qual a tradução de 'I love apples'?
            Retorne em JSON: [{\"pergunta\": \"txt\", \"opcoes\": [\"A\", \"B\", \"C\", \"D\"], \"correta\": 0, \"explicacao\": \"txt\"}]";

    $data = ["contents" => [["parts" => [["text" => $prompt]]]]];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $resultado = json_decode($response, true);
    $usar_fallback = false;

    if ($http_code !== 200 || isset($resultado['error'])) {
        $usar_fallback = true;
    }

    if (!$usar_fallback) {
        $textoCandidato = $resultado['candidates'][0]['content']['parts'][0]['text'] ?? '';
        if (preg_match('/\[.*\]/s', $textoCandidato, $matches)) {
            $parsed = json_decode($matches[0], true);
            if ($parsed) return $parsed;
        }
        $usar_fallback = true; 
    }

    // --- LÓGICA DE FALLBACK (BANCO DE DADOS) ---
    if ($usar_fallback) {
        $questoes_db = [];
        $stmt = $conn->prepare("SELECT * FROM perguntas ORDER BY RAND() LIMIT 3");
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $mapa_correta = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3];
                $idx_correta = $mapa_correta[strtoupper($row['resposta_correta'])] ?? 0;

                $questoes_db[] = [
                    "pergunta" => $row['pergunta'] . " [Modo Offline]",
                    "opcoes" => [$row['opcao_a'], $row['opcao_b'], $row['opcao_c'], $row['opcao_d']],
                    "correta" => $idx_correta,
                    "explicacao" => "Cota de IA excedida ou erro de rede. Carregando dados do servidor local Arcadius."
                ];
            }
            $stmt->close();
            return $questoes_db;
        }
        $stmt->close();
    }

    return [["pergunta" => "Erro de Conexão", "opcoes" => ["Tentar de novo"], "correta" => 0, "explicacao" => "Não foi possível carregar a IA nem o banco local."]];
}
?>