<?php
// includes/ai_engine.php
function solicitarMissoesIA($nivel = 'Iniciante', $tema = 'Geral') {
    global $conn;

    // Se o $conn não existir, tenta carregar
    if (!isset($conn)) {
        require_once dirname(__FILE__) . '/conexao.php';
    }

    $apiKey = "AIzaSyDyBSzpnkIgMsnfXm-HkBuOiPl1IcSKGyA";
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent?key=" . $apiKey;

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

    // Detecta erro de cota (429) ou erro genérico da API
    if ($http_code !== 200 || isset($resultado['error'])) {
        $usar_fallback = true;
    }

    if (!$usar_fallback) {
        $textoCandidato = $resultado['candidates'][0]['content']['parts'][0]['text'] ?? '';
        if (preg_match('/\[.*\]/s', $textoCandidato, $matches)) {
            return json_decode($matches[0], true);
        }
        $usar_fallback = true; // Se o formato falhar, usa fallback
    }

    // --- LÓGICA DE FALLBACK (BANCO DE DADOS) ---
    if ($usar_fallback) {
        $questoes_db = [];
        $res = $conn->query("SELECT * FROM perguntas ORDER BY RAND() LIMIT 3");

        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                // Converte resposta A, B, C, D para 0, 1, 2, 3
                $mapa_correta = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3];
                $idx_correta = $mapa_correta[strtoupper($row['resposta_correta'])] ?? 0;

                $questoes_db[] = [
                    "pergunta" => $row['pergunta'] . " [Modo Offline]",
                    "opcoes" => [$row['opcao_a'], $row['opcao_b'], $row['opcao_c'], $row['opcao_d']],
                    "correta" => $idx_correta,
                    "explicacao" => "Cota de IA excedida. Carregando dados do servidor local Arcadius."
                ];
            }
            return $questoes_db;
        }
    }

    return [["pergunta" => "Erro de Conexão", "opcoes" => ["Tentar de novo"], "correta" => 0, "explicacao" => "Não foi possível carregar a IA nem o banco local."]];
}