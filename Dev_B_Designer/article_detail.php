<?php
// 1. Connexion à la base de données
require_once 'config/db.php';

// 2. Récupération de l'identifiant de l'article depuis l'URL (ex: article_detail.php?id=5)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // 3. Requête pour récupérer l'article spécifique avec le nom de sa catégorie
    $stmt = $pdo->prepare("SELECT a.*, c.libelle as cat_nom 
                           FROM articles a 
                           JOIN categories c ON a.id_categorie = c.id 
                           WHERE a.id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch();
}

// Si l'article n'existe pas, on redirige vers l'accueil
if (!$article) {
    header('Location: index.php');
    exit();
}

// 4. Inclusion de tes fichiers de structure
include 'entete.php';
include 'menu.php';
?>

<main class="container">
    <a href="index.php" style="color: #CFA65B; text-decoration: none; font-weight: bold;">
        ← Retour aux actualités
    </a>

    <article style="margin-top: 20px;">
        <h1 style="color: #671E30; font-size: 2.5em; margin-bottom: 10px;">
            <?php echo htmlspecialchars($article['titre']); ?>
        </h1>

        <div style="background: #f9f9f9; padding: 10px; border-left: 5px solid #CFA65B; margin-bottom: 20px;">
            <p style="margin: 0; font-style: italic; color: #555;">
                Publié le <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?> 
                | Catégorie : <strong><?php echo htmlspecialchars($article['cat_nom']); ?></strong>
            </p>
        </div>

        <div class="article-content" style="line-height: 1.8; font-size: 1.1em; color: #333;">
            <?php echo nl2br(htmlspecialchars($article['contenu'])); ?>
        </div>
    </article>
</main>

<?php 
// 5. Inclusion du pied de page
include 'footer.php'; 
?>