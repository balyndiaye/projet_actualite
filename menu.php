Contenu collé
5.50 Ko •89 lignes
•
Le formatage peut être différent de la source
<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Gestion dynamique des chemins (URL_BASE) pour que le menu marche partout
if (!defined('URL_BASE')) {
    $current_dir = basename(dirname($_SERVER['PHP_SELF']));
    $base = ($current_dir == 'articles' || $current_dir == 'categories' || $current_dir == 'utilisateurs') ? '../' : './';
    define('URL_BASE', $base);
}

require_once __DIR__ . '/config/db.php';

// Récupération des catégories pour la barre de navigation secondaire
try {
    $nav_categories = $pdo->query("SELECT id, nom FROM categories ORDER BY nom ASC")->fetchAll();
} catch (PDOException $e) { 
    $nav_categories = []; 
}
?>

<div style="position: sticky; top: 0; z-index: 9999; box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-family: 'Inter', sans-serif;">

    <header style="background-color: #6D071A; padding: 25px 0; border-bottom: 1px solid rgba(255,255,255,0.1);"> 
        <div class="container" style="display: flex; align-items: center; justify-content: space-between; max-width: 1350px; margin: 0 auto; padding: 0 25px;">
            
            <a href="<?php echo URL_BASE; ?>index.php" style="text-decoration: none; font-size: 1.9rem; font-weight: 900; color: #FFFFFF; letter-spacing: -1px; white-space: nowrap;">
                ESP NEWS<span style="color: #ffffff;">.</span>
            </a>
            
            <form action="<?php echo URL_BASE; ?>index.php" method="GET" style="flex-grow: 1; max-width: 450px; position: relative; margin: 0 40px;">
                <input type="text" name="search" placeholder="Rechercher une actualité..." 
                       style="width: 100%; padding: 12px 0px 12px 20px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255, 255, 255, 0.1); color: #FFFFFF; outline: none; font-size: 0.95rem;">
                <button type="submit" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer; color: #FFFFFF; font-size: 1.1rem;">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <nav>
                <ul style="list-style: none; display: flex; gap: 20px; margin: 0; padding: 0; align-items: center;">
                    <li><a href="<?php echo URL_BASE; ?>index.php" style="text-decoration: none; color: #FFFFFF; font-weight: 700; font-size: 0.95rem;">Accueil</a></li>
                    
                    <?php if(isset($_SESSION['login'])): ?>
                        <li><a href="<?php echo URL_BASE; ?>articles/listes.php" style="text-decoration: none; color: #FFFFFF; font-size: 0.9rem; font-weight: 600;">Gérer Articles</a></li>
                        
                        <?php if(isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editeur')): ?>
                            <li><a href="<?php echo URL_BASE; ?>categories/listes.php" style="text-decoration: none; color: #FFFFFF; font-size: 0.9rem; font-weight: 600;">Catégories</a></li>
                        <?php endif; ?>

                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li><a href="<?php echo URL_BASE; ?>utilisateurs/liste.php" style="text-decoration: none; color: #FFFFFF; font-size: 0.9rem; font-weight: 600;">Utilisateurs</a></li>
                        <?php endif; ?>
                        
                        <li>
                            <a href="<?php echo URL_BASE; ?>deconnexion.php" style="color: #FFFFFF; font-weight: 800; text-decoration: none; font-size: 0.8rem; border: 2px solid #FFFFFF; padding: 7px 15px; border-radius: 5px; margin-left: 10px; text-transform: uppercase; transition: 0.3s;">
                                Quitter
                            </a>
                        </li>
                    
                    <?php else: ?>
                        <li>
                            <a href="<?php echo URL_BASE; ?>connexion.php" 
                               style="text-decoration: none; color: #FFFFFF; font-weight: 900; font-size: 0.95rem; border: none; outline: none; transition: 0.3s;"
                               onmouseover="this.style.color='#CFA65B'" 
                               onmouseout="this.style.color='#FFFFFF'">
                                CONNEXION
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <nav style="background: #FFFFFF; border-bottom: 1px solid #e0e0e0; padding: 15px 0;">
        <div class="container" style="display: flex; gap: 40px; max-width: 1350px; margin: 0 auto; padding: 0 25px; overflow-x: auto; white-space: nowrap;">
            <a href="<?php echo URL_BASE; ?>index.php" style="text-decoration: none; color: #6D071A; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">TOUTES</a>
            
            <?php if (!empty($nav_categories)): ?>
                <?php foreach($nav_categories as $c): ?>
                    <a href="<?php echo URL_BASE; ?>index.php?id_cat=<?php echo $c['id']; ?>" style="text-decoration: none; color: #666; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; transition: 0.2s;">
                        <?php echo htmlspecialchars($c['nom']); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </nav>
</div>