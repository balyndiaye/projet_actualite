<?php
// 1. Connexion à la base de données et session
session_start();
require_once 'config/db.php';

// 2. Récupération de l'identifiant de l'article depuis l'URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$article = false;

if ($id > 0) {
    // 3. REQUÊTE SQL : On récupère l'article, sa catégorie et le login de l'auteur
    // On utilise 'u.login' car c'est le nom de la colonne dans ta table utilisateurs
    $sql = "SELECT a.*, c.nom as cat_nom, u.login as auteur_nom 
            FROM articles a 
            LEFT JOIN categories c ON a.id_categorie = c.id 
            LEFT JOIN utilisateurs u ON a.id_utilisateur = u.id 
            WHERE a.id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $article = $stmt->fetch();
}

// Si l'article n'existe pas ou ID invalide, retour à l'accueil
if (!$article) {
    header('Location: index.php');
    exit();
}

include 'entete.php';
include 'menu.php';
?>

<main class="container" style="margin-top: 40px; max-width: 900px; font-family: 'Segoe UI', sans-serif;">
    <a href="index.php" style="color: #3922e6; text-decoration: none; font-weight: bold; display: inline-block; margin-bottom: 20px;">
        ← Retour aux actualités
    </a>

    <article style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
        
        <?php if (!empty($article['image'])): ?>
            <div style="width: 100%; max-height: 450px; overflow: hidden; border-radius: 10px; margin-bottom: 30px;">
                <img src="uploads/<?= htmlspecialchars($article['image']) ?>" 
                     alt="<?= htmlspecialchars($article['titre']) ?>"
                     style="width: 100%; height: auto; object-fit: cover;">
            </div>
        <?php endif; ?>

        <h1 style="color: #2c3e50; font-size: 2.5em; margin-bottom: 15px; line-height: 1.2;">
            <?= htmlspecialchars($article['titre']) ?>
        </h1>

        <div style="background: #f8f9fa; padding: 15px; border-left: 5px solid #3922e6; margin-bottom: 30px; border-radius: 0 8px 8px 0;">
            <p style="margin: 0; font-size: 0.95em; color: #666;">
                 Publié le <strong><?= date('d/m/Y', strtotime($article['date_creation'])) ?></strong>
                |  Par : <strong><?= htmlspecialchars($article['auteur_nom'] ?? 'Anonyme') ?></strong>
                |  Catégorie : <span style="color: #3922e6; font-weight: bold;"><?= htmlspecialchars($article['cat_nom']) ?></span>
            </p>
        </div>