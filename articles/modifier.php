<?php
session_start();
require_once '../config/db.php';

// 1. Vérification de connexion
if (!isset($_SESSION['login'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupération de l'article
if (!isset($_GET['id'])) {
    header("Location: listes.php");
    exit();
}
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    die("Article introuvable.");
}

// 2. SÉCURITÉ : Un éditeur ne modifie que SES articles (sauf si admin)
if ($_SESSION['role'] !== 'admin' && $article['id_utilisateur'] !== $_SESSION['id_user']) {
    header("Location: listes.php?erreur=non_autorise");
    exit();
}

// 3. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $update = $pdo->prepare("UPDATE articles SET titre = ?, contenu = ?, id_categorie = ? WHERE id = ?");
    $update->execute([$_POST['titre'], $_POST['contenu'], $_POST['id_categorie'], $id]);

    header("Location: listes.php?success=modifie");
    exit();
}

include '../entete.php';
include '../menu.php';

// Récupération des catégories pour le menu déroulant
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom ASC")->fetchAll();
?>

<div class="container" style="margin-top: 50px; margin-bottom: 50px; max-width: 900px; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); font-family: 'Inter', sans-serif;">

    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px; border-bottom: 2px solid #f0f0f0; padding-bottom: 20px;">
        <div style="background: #6D071A; width: 10px; height: 35px; border-radius: 5px;"></div>
        <h2 style="margin: 0; color: #333; font-size: 1.8rem; font-weight: 800;">Modifier l'article</h2>
    </div>

    <form method="POST">
        <div style="margin-bottom: 25px;">
            <label style="font-weight: 700; display: block; margin-bottom: 8px; color: #555; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px;">Titre de l'actualité</label>
            <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required
                style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; outline: none; transition: 0.3s; box-sizing: border-box;"
                onfocus="this.style.borderColor='#6D071A'">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="font-weight: 700; display: block; margin-bottom: 8px; color: #555; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px;">Catégorie</label>
            <select name="id_categorie" required
                style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background-color: #f9f9f9; outline: none; box-sizing: border-box;">
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($c['id'] == $article['id_categorie']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="font-weight: 700; display: block; margin-bottom: 8px; color: #555; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px;">Contenu de l'article</label>
            <textarea name="contenu" rows="12" required
                style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; line-height: 1.6; outline: none; transition: 0.3s; font-family: Arial, sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#6D071A'"><?= htmlspecialchars($article['contenu']) ?></textarea>
        </div>

        <div style="display: flex; gap: 15px; align-items: center; border-top: 1px solid #eee; padding-top: 30px;">
            <button type="submit" style="background: #6D071A; color: white; padding: 15px 35px; border: none; border-radius: 8px; font-weight: 800; font-size: 1rem; cursor: pointer; flex: 2; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; box-shadow: 0 4px 12px rgba(109, 7, 26, 0.3);">
                <i class="fas fa-check-circle" style="margin-right: 8px;"></i> Enregistrer les modifications
            </button>

            <a href="listes.php" style="background: #f8f9fa; color: #666; padding: 15px 25px; border-radius: 8px; text-decoration: none; text-align: center; flex: 1; font-weight: 700; border: 1px solid #ddd; font-size: 0.9rem; transition: 0.2s;">
                Annuler
            </a>
        </div>
    </form>
</div>

<?php include '../footer.php'; ?>