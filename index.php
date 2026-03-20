<?php
// 1. Inclusion de la connexion à la base de données (Fichier créé par le Dév A)
// On utilise require_once car sans BDD, la page ne peut pas afficher d'articles
require_once 'config/db.php'; 

// 2. Gestion de la pagination
$articlesParPage = 5; // Le prof demande une pagination, on fixe à 5 articles
$pageActuelle = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($pageActuelle <= 0) $pageActuelle = 1;
$offset = ($pageActuelle - 1) * $articlesParPage;

// 3. Récupération des articles avec leur catégorie
try {
    // On récupère les articles du plus récent au plus ancien
    $query = "SELECT a.*, c.libelle as categorie_nom 
              FROM articles a 
              JOIN categories c ON a.id_categorie = c.id 
              ORDER BY a.date_publication DESC 
              LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':limit', $articlesParPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $articles = $stmt->fetchAll();
} catch (PDOException $e) {
    $erreur = "Erreur lors du chargement des articles.";
}

// 4. Inclusion de l'entête et du menu (Tes fichiers de structure)
include 'entete.php';
include 'menu.php';
?>

<main class="container">
    <h2 style="color: #671E30; border-bottom: 2px solid #CFA65B; padding-bottom: 10px;">
        Dernières Actualités
    </h2>

    <?php if (isset($erreur)): ?>
        <p class="error"><?php echo $erreur; ?></p>
    <?php elseif (empty($articles)): ?>
        <p>Aucun article n'est disponible pour le moment.</p>
    <?php else: ?>
        
        <?php foreach ($articles as $article): ?>
            <article class="article-card">
                <h3>
                    <a href="article_detail.php?id=<?php echo $article['id']; ?>" style="color: #671E30; text-decoration: none;">
                        <?php echo htmlspecialchars($article['titre']); ?>
                    </a>
                </h3>
                <p style="font-size: 0.8em; color: #666;">
                    Publié le <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?> 
                    dans la catégorie <strong><?php echo htmlspecialchars($article['categorie_nom']); ?></strong>
                </p>
                <p>
                    <?php echo htmlspecialchars($article['description_courte']); ?>
                </p>
                <a href="article_detail.php?id=<?php echo $article['id']; ?>" class="btn">Lire la suite</a>
            </article>
        <?php endforeach; ?>

        <div class="pagination" style="margin-top: 30px; text-align: center;">
            <?php if ($pageActuelle > 1): ?>
                <a href="index.php?p=<?php echo $pageActuelle - 1; ?>" class="btn">« Précédent</a>
            <?php endif; ?>
            
            <span style="margin: 0 15px;">Page <?php echo $pageActuelle; ?></span>
            
            <?php if (count($articles) == $articlesParPage): ?>
                <a href="index.php?p=<?php echo $pageActuelle + 1; ?>" class="btn">Suivant »</a>
            <?php endif; ?>
        </div>

    <?php endif; ?>
</main>

<?php
// 6. Inclusion du pied de page
include 'footer.php';
?>