<?php
require_once 'includes/dictionary_api.php';

if (isset($_GET['word'])) {
    $palavra = trim($_GET['word']);
    
    $palavraLimpa = preg_replace('/^to\s+/i', '', $palavra);
    
    echo buscarDefinicao($palavraLimpa);
}
?>