<?php 
// 1. Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

// 2. Détermination du chemin de base pour les LIENS HTML (href, src)
if (!defined('URL_BASE')) {
    $current_dir = basename(dirname($_SERVER['PHP_SELF']));
    $base = ($current_dir == 'articles' || $current_dir == 'categories' || $current_dir == 'utilisateurs') ? '../' : './';
    define('URL_BASE', $base);
}

// 3. CONNEXION DB AVEC CHEMIN ABSOLU (Pour éviter l'erreur Failed to open stream)
// On définit la racine du projet sur ton disque dur
$root_path = __DIR__; 
require_once $root_path . '/config/db.php';

// 4. Récupération des catégories pour le menu
try {
    $stmt_nav = $pdo->query("SELECT * FROM categories ORDER BY nom ASC");
    $nav_categories = $stmt_nav->fetchAll();
} catch (PDOException $e) {
    $nav_categories = [];
}
?>

<header class="site-header">
    <div class="container header-inner">
        <a href="<?php echo URL_BASE; ?>index.php" class="logo">ESP NEWS<span>.</span></a>
        
        <form action="<?php echo URL_BASE; ?>index.php" method="GET" class="search-container">
            <input type="text" name="search" placeholder="Recherche..." class="search-input">
            <button type="submit" style="border:none; background:none; cursor:pointer;">🔍</button>
        </form>

        <nav class="main-nav">
            <ul>
                <li><a href="<?php echo URL_BASE; ?>index.php">Accueil</a></li>

                <?php if(isset($_SESSION['login'])): ?>
                    <li><a href="<?php echo URL_BASE; ?>articles/listes.php">Gérer Articles</a></li>

                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li><a href="<?php echo URL_BASE; ?>categories/listes.php">Catégories</a></li>
                        <li><a href="<?php echo URL_BASE; ?>utilisateurs/liste.php">Utilisateurs</a></li>
                    <?php endif; ?>

                    <li><a href="<?php echo URL_BASE; ?>deconnexion.php" style="color:var(--color-accent); font-weight:bold;">Quitter</a></li>
                
                <?php else: ?>
                    <li><a href="<?php echo URL_BASE; ?>connexion.php">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<div class="category-nav">
    <div class="container category-list">
        <?php if (!empty($nav_categories)): ?>
            <?php foreach($nav_categories as $c): ?>
                <a href="<?php echo URL_BASE; ?>index.php?cat=<?php echo urlencode($c['nom']); ?>" class="category-link">
                    <?php echo htmlspecialchars($c['nom']); ?>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>