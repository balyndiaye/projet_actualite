<?php
session_start();
require_once 'config/db.php';

// 1. RÉCUPÉRATION DES ARTICLES AVEC LEURS CATÉGORIES
$sql = "SELECT articles.*, categories.nom AS categorie_nom 
        FROM articles 
        LEFT JOIN categories ON articles.id_categorie = categories.id 
        ORDER BY articles.date_creation DESC";

$articles = $pdo->query($sql)->fetchAll();

include 'entete.php';
include 'menu.php';
?>

<main class="container" style="margin-top: 30px; font-family: 'Segoe UI', sans-serif;">
    <h1 style="text-align: center; color: #2c3e50; margin-bottom: 40px; border-bottom: 3px solid #3922e6; display: inline-block; padding-bottom: 10px;">
           Dernières Actualités
    </h1>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
        
        <?php if (count($articles) > 0): ?>
            <?php foreach ($articles as $a): ?>
                <article style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    
                    <div style="height: 200px; overflow: hidden; background: #eee;">
                        <?php if (!empty($a['image'])): ?>
                            <img src="uploads/<?= htmlspecialchars($a['image']) ?>" 
                                 alt="<?= htmlspecialchars($a['titre']) ?>" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #999;">
                                 Aucune image
                            </div>
                        <?php endif; ?>
                    </div>

                    <div style="padding: 20px;">
                        <span style="background: #3922e6; color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.75em; font-weight: bold; text-transform: uppercase;">
                            <?= htmlspecialchars($a['categorie_nom']) ?>
                        </span>

                        <h2 style="margin: 15px 0 10px; font-size: 1.4em; color: #2c3e50;">
                            <?= htmlspecialchars($a['titre']) ?>
                        </h2>

                        <p style="color: #7f8c8d; font-size: 0.95em; line-height: 1.6; margin-bottom: 20px;">
                            <?= substr(htmlspecialchars($a['contenu']), 0, 120) ?>...
                        </p>

                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px;">
                            <span style="font-size: 0.8em; color: #bdc3c7;">
                                📅 <?= date('d/m/Y', strtotime($a['date_creation'])) ?>
                            </span>
                            <a href="article_detail.php?id=<?= $a['id'] ?>" style="color: #3922e6; text-decoration: none; font-weight: bold; font-size: 0.9em;">
                                Lire la suite →
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; grid-column: 1 / -1; color: #7f8c8d; font-style: italic;">
                Aucun article n'a été publié pour le moment.
            </p>
        <?php endif; ?>

    </div>
</main>

<?php include 'footer.php'; ?>