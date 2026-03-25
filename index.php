<?php
session_start();
require_once 'config/db.php';

// --- 1. CONFIGURATION DE LA PAGINATION ---
$articlesParPage = 6; 
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageActuelle <= 0) $pageActuelle = 1;
$offset = ($pageActuelle - 1) * $articlesParPage;

// --- 2. GESTION DU FILTRE PAR CATÉGORIE ---
$id_cat = isset($_GET['id_cat']) ? (int)$_GET['id_cat'] : 0;
$params = [];
$whereClause = "";

if ($id_cat > 0) {
    $whereClause = " WHERE a.id_categorie = ? ";
    $params[] = $id_cat;
}

// --- 3. CALCUL DU TOTAL ---
$sqlCount = "SELECT COUNT(*) FROM articles a" . $whereClause;
$stmtCount = $pdo->prepare($sqlCount);
$stmtCount->execute($params);
$totalArticles = $stmtCount->fetchColumn();
$totalPages = ceil($totalArticles / $articlesParPage);

// --- 4. RÉCUPÉRATION DES ARTICLES ---
$sql = "SELECT a.*, c.nom AS categorie_nom, u.login AS auteur_nom 
        FROM articles a 
        LEFT JOIN categories c ON a.id_categorie = c.id 
        LEFT JOIN utilisateurs u ON a.id_utilisateur = u.id
        $whereClause
        ORDER BY a.date_creation DESC 
        LIMIT $articlesParPage OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$articles = $stmt->fetchAll();

include 'entete.php';
include 'menu.php';
?>

<main class="container" style="margin-top: 40px; font-family: 'Segoe UI', sans-serif;">
    
    <div style="text-align: left; margin-bottom: 35px; padding-left: 10px;">
        <h1 style="color: #2c3e50; margin-bottom: 15px; border-left: 5px solid #3922e6; padding-left: 15px; font-size: 2rem;">
               Dernières Actualités
        </h1>

        <?php if ($id_cat > 0): ?>
            <div style="margin-top: 10px; padding-left: 20px;">
                <span style="background: #f0f2ff; color: #3922e6; padding: 6px 15px; border-radius: 4px; font-weight: bold; font-size: 0.85em; border-left: 3px solid #3922e6; display: inline-block;">
                    Il y a <?= $totalArticles ?> article<?= $totalArticles > 1 ? 's' : '' ?> dans cette catégorie
                </span>
            </div>
        <?php endif; ?>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
        
        <?php if (count($articles) > 0): ?>
            <?php foreach ($articles as $a): ?>
                <article style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; border: 1px solid #eee;">
                    
                    <div style="height: 180px; overflow: hidden; background: #f8f9fa;">
                        <?php if (!empty($a['image'])): ?>
                            <img src="uploads/<?= htmlspecialchars($a['image']) ?>" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #bbb;">
                                 Image non disponible
                            </div>
                        <?php endif; ?>
                    </div>

                    <div style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">
                        <span style="color: #3922e6; font-size: 0.7em; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">
                            <?= htmlspecialchars($a['categorie_nom']) ?>
                        </span>

                        <h2 style="margin: 10px 0; font-size: 1.15em; color: #2c3e50; line-height: 1.3;">
                            <?= htmlspecialchars($a['titre']) ?>
                        </h2>

                        <p style="color: #666; font-size: 0.88em; line-height: 1.5; margin-bottom: 20px;">
                            <?= substr(htmlspecialchars($a['contenu']), 0, 90) ?>...
                        </p>

                        <div style="margin-top: auto; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f5f5f5; padding-top: 15px;">
                            <span style="font-size: 0.75em; color: #999;">
                                📅 <?= date('d/m/Y', strtotime($a['date_creation'])) ?>
                            </span>
                            <a href="article_detail.php?id=<?= $a['id'] ?>" style="color: #3922e6; text-decoration: none; font-weight: bold; font-size: 0.85em;">
                                LIRE LA SUITE
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="grid-column: 1 / -1; text-align: left; color: #7f8c8d; padding: 20px;">
                Aucun article trouvé dans cette section.
            </p>
        <?php endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div style="margin: 50px 0; display: flex; justify-content: flex-start; align-items: center; gap: 15px; padding-left: 10px;">
        
        <?php if ($pageActuelle > 1): ?>
            <a href="?page=<?= $pageActuelle - 1 ?><?= $id_cat ? '&id_cat='.$id_cat : '' ?>" 
               style="padding: 8px 18px; border: 1px solid #3922e6; color: #3922e6; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 0.9em;">
               Précédent
            </a>
        <?php endif; ?>

        <span style="font-size: 0.9em; color: #777;">
            Page <?= $pageActuelle ?> sur <?= $totalPages ?>
        </span>

        <?php if ($pageActuelle < $totalPages): ?>
            <a href="?page=<?= $pageActuelle + 1 ?><?= $id_cat ? '&id_cat='.$id_cat : '' ?>" 
               style="padding: 8px 18px; background: #3922e6; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 0.9em;">
               Suivant
            </a>
        <?php endif; ?>
        
    </div>
    <?php endif; ?>

</main>

<?php include 'footer.php'; ?>