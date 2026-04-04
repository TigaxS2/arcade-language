<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Lógica para o usuário logado
$nomeCompleto = isset($_SESSION['nome']) ? $_SESSION['nome'] : "Recruta";
$primeiroNome = explode(" ", $nomeCompleto)[0];

// Verifica a foto: se não existir na sessão ou no servidor, usa a default
$fotoPerfil = (isset($_SESSION['foto']) && !empty($_SESSION['foto'])) ? $_SESSION['foto'] : 'assets/img/default.png';
?>

<nav class="main-nav">
    <div class="nav-container">
        <a href="index.php" class="nav-logo">ARCADE <span>LANGUAGE</span></a>
        
        <ul class="nav-links">
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="about.php">Quem Somos</a></li>
            <li><a href="ranking_geral.php">Ranking</a></li>
            
            <?php if(isset($_SESSION['id'])): ?>
                <li class="user-profile-nav">
                    <a href="perfil.php" class="nav-user">
                        <img src="<?php echo $fotoPerfil; ?>" class="nav-avatar" alt="Perfil">
                        <span>Olá, <?php echo htmlspecialchars($primeiroNome); ?></span>
                    </a>
                </li>
                <li><a href="auth/logout.php" class="btn-logout">Sair</a></li>
            
            <?php else: ?>
                <li><a href="login.php" class="btn-login-nav">Login</a></li>
            <?php endif; ?>
        </ul>
    </div> 
</nav>

<style>
/* --- ESTILO UNIFICADO DA NAVBAR --- */
.main-nav {
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(10px);
    border-bottom: 2px solid rgba(0, 240, 255, 0.3);
    padding: 12px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
}

.nav-logo {
    font-size: 1.6rem;
    font-weight: bold;
    color: #fff;
    text-decoration: none;
    letter-spacing: 2px;
}

.nav-logo span { color: #00f0ff; text-shadow: 0 0 10px rgba(0, 240, 255, 0.5); }

.nav-links { list-style: none; display: flex; gap: 25px; align-items: center; }

.nav-links a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-size: 0.95rem;
    transition: 0.3s;
}

.nav-links a:hover { color: #00f0ff; text-shadow: 0 0 8px #00f0ff; }

/* Foto e Nome do Usuário */
.nav-user { 
    display: flex; 
    align-items: center; 
    gap: 12px; 
    color: #00f0ff !important; 
    font-weight: 600; 
}

.nav-avatar { 
    width: 38px; 
    height: 38px; 
    border-radius: 50%; 
    border: 2px solid #00f0ff; 
    object-fit: cover; 
    box-shadow: 0 0 10px rgba(0, 240, 255, 0.2);
}

.btn-logout { 
    background: rgba(255, 77, 77, 0.2); 
    color: #ff4d4d !important;
    border: 1px solid #ff4d4d;
    padding: 6px 15px; 
    border-radius: 20px; 
}

.btn-logout:hover {
    background: #ff4d4d;
    color: white !important;
}

.btn-login-nav { 
    border: 1px solid #00f0ff; 
    padding: 6px 18px; 
    border-radius: 20px; 
    color: #00f0ff !important;
}
</style>