<?php
session_start();
require_once 'config/db.php';

// CONFIGURATION DE LA PAGINATION
$articlesParPage = 3;
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageActuelle <= 0) $pageActuelle = 1;
$offset = ($pageActuelle - 1) * $articlesParPage;

// GESTION DES FILTRES (CATÉGORIE ET RECHERCHE)
$id_cat = isset($_GET['id_cat']) ? (int)$_GET['id_cat'] : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$params = [];
$conditions = [];

// Filtre par catégorie
if ($id_cat > 0) {
    $conditions[] = "a.id_categorie = ?";
    $params[] = $id_cat;
}

// Filtre par recherche (titre ou contenu)
if (!empty($search)) {
    $conditions[] = "(a.titre LIKE ? OR a.contenu LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Construction de la clause WHERE
$whereClause = "";
if (count($conditions) > 0) {
    $whereClause = " WHERE " . implode(" AND ", $conditions);
}

// CALCUL DU TOTAL POUR LA PAGINATION
$sqlCount = "SELECT COUNT(*) FROM articles a" . $whereClause;
$stmtCount = $pdo->prepare($sqlCount);
$stmtCount->execute($params);
$totalArticles = $stmtCount->fetchColumn();
$totalPages = ceil($totalArticles / $articlesParPage);

// RÉCUPÉRATION DES ARTICLES
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
        <h1 style="color: #2c3e50; margin-bottom: 15px; border-left: 5px solid #6D071A; padding-left: 15px; font-size: 2rem;">
            <?php echo empty($search) ? 'Dernières Actualités' : 'Résultats pour : "' . htmlspecialchars($search) . '"'; ?>
        </h1>

        <?php if ($totalArticles > 0): ?>
            <div style="margin-top: 10px; padding-left: 20px;">
                <span style="background: #fdf2f2; color: #6D071A; padding: 6px 15px; border-radius: 4px; font-weight: bold; font-size: 0.85em; border-left: 3px solid #6D071A; display: inline-block;">
                    <?= $totalArticles ?> article<?= $totalArticles > 1 ? 's' : '' ?> trouvé<?= $totalArticles > 1 ? 's' : '' ?>
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
                        <span style="color: #6D071A; font-size: 0.7em; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">
                            <?= htmlspecialchars($a['categorie_nom'] ?? 'Général') ?>
                        </span>

                        <h2 style="margin: 10px 0; font-size: 1.15em; color: #2c3e50; line-height: 1.3;">
                            <?= htmlspecialchars($a['titre']) ?>
                        </h2>

                        <p style="color: #666; font-size: 0.88em; line-height: 1.5; margin-bottom: 20px;">
                            <?= substr(strip_tags($a['contenu']), 0, 90) ?>...
                        </p>

                        <div style="margin-top: auto; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f5f5f5; padding-top: 15px;">
                            <span style="font-size: 0.75em; color: #999;">
                                <?= date('d/m/Y', strtotime($a['date_creation'])) ?>
                            </span>
                            <a href="article_detail.php?id=<?= $a['id'] ?>" style="color: #6D071A; text-decoration: none; font-weight: bold; font-size: 0.85em;">
                                LIRE LA SUITE
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px; background: #f9f9f9; border-radius: 8px;">
                <i class="fas fa-search" style="font-size: 3rem; color: #ccc; margin-bottom: 20px;"></i>
                <p style="color: #7f8c8d; font-size: 1.1rem;">Aucun article ne correspond à votre recherche.</p>
                <a href="index.php" style="color: #6D071A; font-weight: bold; text-decoration: underline;">Voir tous les articles</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <div style="margin: 50px 0; display: flex; justify-content: flex-start; align-items: center; gap: 15px; padding-left: 10px;">

            <?php
            // On conserve les paramètres search et id_cat dans les liens de pagination
            $query_params = $_GET;
            ?>

            <?php if ($pageActuelle > 1): ?>
                <?php $query_params['page'] = $pageActuelle - 1; ?>
                <a href="?<?= http_build_query($query_params) ?>"
                    style="padding: 8px 18px; border: 1px solid #6D071A; color: #6D071A; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 0.9em;">
                    Précédent
                </a>
            <?php endif; ?>

            <span style="font-size: 0.9em; color: #777;">
                Page <?= $pageActuelle ?> sur <?= $totalPages ?>
            </span>

            <?php if ($pageActuelle < $totalPages): ?>
                <?php $query_params['page'] = $pageActuelle + 1; ?>
                <a href="?<?= http_build_query($query_params) ?>"
                    style="padding: 8px 18px; background: #6D071A; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 0.9em;">
                    Suivant
                </a>
            <?php endif; ?>

        </div>
    <?php endif; ?>

</main>

<?php include 'footer.php'; ?>