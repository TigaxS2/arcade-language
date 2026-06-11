<?php
require_once 'includes/dictionary_api.php';
require_once 'includes/funcoes.php';

if (isset($_GET['word'])) {
    $palavra = sanitize($_GET['word']);
    
    $palavraLimpa = preg_replace('/^to\s+/i', '', $palavra);
    
    echo buscarDefinicao($palavraLimpa);
}
?>