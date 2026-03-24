<?php
// 1. Connexion à la base de données
session_start();
require_once 'config/db.php';

// 2. Récupération de l'identifiant
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$article = false;

if ($id > 0) {
    // 3. REQUÊTE CORRIGÉE : on utilise 'nom' au lieu de 'libelle' 
    // et 'id_categorie' au lieu de 'categorie_id' (si c'est ton cas)
    $sql = "SELECT a.*, c.nom as cat_nom 
            FROM articles a 
            LEFT JOIN categories c ON a.id_categorie = c.id 
            WHERE a.id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $article = $stmt->fetch();
}

// Si l'article n'existe pas, retour à l'accueil
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
                     style="width: 100%; height: auto; object-fit: cover;">
            </div>
        <?php endif; ?>

        <h1 style="color: #2c3e50; font-size: 2.5em; margin-bottom: 15px; line-height: 1.2;">
            <?= htmlspecialchars($article['titre']) ?>
        </h1>

        <div style="background: #f8f9fa; padding: 15px; border-left: 5px solid #3922e6; margin-bottom: 30px; border-radius: 0 8px 8px 0;">
            <p style="margin: 0; font-size: 0.95em; color: #666;">
                 Publié le <strong><?= date('d/m/Y', strtotime($article['date_creation'])) ?></strong> 
                |  Catégorie : <span style="color: #3922e6; font-weight: bold;"><?= htmlspecialchars($article['cat_nom']) ?></span>
            </p>
        </div>

        <div class="article-content" style="line-height: 1.8; font-size: 1.2em; color: #34495e; white-space: pre-line;">
            <?= nl2br(htmlspecialchars($article['contenu'])) ?>
        </div>

    </article>
</main>

<div style="margin-bottom: 50px;"></div>

<?php include 'footer.php'; ?>