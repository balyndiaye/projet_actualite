<?php
session_start();
require_once 'config/db.php';

// Récupération de l'ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit();
}

// Requête SQL (On récupère bien TOUT avec a.*)
$sql = "SELECT a.*, c.nom as cat_nom, u.login as auteur_nom 
        FROM articles a 
        LEFT JOIN categories c ON a.id_categorie = c.id 
        LEFT JOIN utilisateurs u ON a.id_utilisateur = u.id 
        WHERE a.id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: index.php');
    exit();
}

include 'entete.php';
include 'menu.php';
?>

<main class="container" style="margin-top: 40px; max-width: 900px; font-family: 'Inter', sans-serif; margin-bottom: 80px;">

    <a href="index.php" style="color: #6D071A; text-decoration: none; font-weight: 800; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 25px; text-transform: uppercase; font-size: 0.85rem;">
        <i class="fas fa-arrow-left"></i> Retour
    </a>

    <article style="background: white; padding: 45px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eee;">

        <?php if (!empty($article['image'])): ?>
            <div style="width: 100%; margin-bottom: 35px; border-radius: 12px; overflow: hidden;">
                <img src="uploads/<?= htmlspecialchars($article['image']) ?>" style="width: 100%; display: block;">
            </div>
        <?php endif; ?>

        <h1 style="color: #1a1a1a; font-size: 2.8rem; margin-bottom: 20px; font-weight: 900; letter-spacing: -1px; line-height: 1.1;">
            <?= htmlspecialchars($article['titre']) ?>
        </h1>

        <div style="background: #fdfdfd; padding: 15px 25px; border-left: 5px solid #6D071A; margin-bottom: 40px; display: flex; gap: 20px; color: #777; font-size: 0.9rem; border: 1px solid #f0f0f0;">
            <span><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($article['date_creation'])) ?></span>
            <span><i class="far fa-user"></i> Par <strong><?= htmlspecialchars($article['auteur_nom'] ?? 'Anonyme') ?></strong></span>
            <span style="background:#6D071A; color:white; padding:2px 10px; border-radius:4px; font-weight:bold; font-size:0.75rem; text-transform:uppercase;">
                <?= htmlspecialchars($article['cat_nom']) ?>
            </span>
        </div>

        <div style="font-size: 1.2rem; line-height: 1.8; color: #333; font-family: 'Georgia', serif; white-space: pre-wrap;">
            <?php
            if (!empty($article['contenu'])) {
                echo nl2br(htmlspecialchars($article['contenu']));
            } else {
                echo "<p style='color: #999; font-style: italic;'>Cet article n'a pas encore de description.</p>";
            }
            ?>
        </div>

    </article>
</main>

<?php include 'footer.php'; ?>