<?php
function buscarDefinicao($palavra) {
    $url = "https://api.dictionaryapi.dev/api/v2/entries/en/" . urlencode($palavra);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $resposta = curl_exec($ch);
    curl_close($ch);
    
    $dados = json_decode($resposta, true);

    // Se encontrar a definição, retorna o primeiro significado
    if (isset($dados[0]['meanings'][0]['definitions'][0]['definition'])) {
        return $dados[0]['meanings'][0]['definitions'][0]['definition'];
    }
    
    return "Definição não encontrada, mas continue a praticar!";
}
?>