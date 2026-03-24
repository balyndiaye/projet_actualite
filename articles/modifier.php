<?php 
session_start();
require_once '../config/db.php';

// 1. Vérification de connexion
if (!isset($_SESSION['login'])) {
    header('Location: ../connexion.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) { die("Article introuvable."); }

// 2. SÉCURITÉ : Un éditeur ne modifie que SES articles (sauf si admin)
// Correction : 'admin' au lieu de 'administrateur' et 'id_utilisateur' au lieu de 'id_auteur'
if ($_SESSION['role'] !== 'admin' && $article['id_utilisateur'] !== $_SESSION['id_user']) {
    header("Location: listes.php?erreur=non_autorise");
    exit();
}

// 3. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // On récupère les catégories pour être sûr du lien
    $update = $pdo->prepare("UPDATE articles SET titre = ?, contenu = ?, id_categorie = ? WHERE id = ?");
    $update->execute([$_POST['titre'], $_POST['contenu'], $_POST['id_categorie'], $id]);
    
    // On redirige vers la liste de gestion pour voir le résultat
    header("Location: listes.php");
    exit();
}

include '../entete.php';
include '../menu.php';

// On récupère les catégories pour le menu déroulant
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom ASC")->fetchAll();
?>

<div class="container" style="margin-top: 40px; max-width: 800px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h2 style="color: var(--color-primary); border-bottom: 2px solid var(--color-accent); padding-bottom: 10px; margin-bottom: 20px;">
        Modifier l'article
    </h2>

    <form method="POST">
        <label style="font-weight:bold; display:block; margin-bottom:5px;">Titre</label>
        <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required 
               style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 20px;">

        <label style="font-weight:bold; display:block; margin-bottom:5px;">Catégorie</label>
        <select name="id_categorie" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 20px;">
            <?php foreach($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($c['id'] == $article['id_categorie']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label style="font-weight:bold; display:block; margin-bottom:5px;">Contenu</label>
        <textarea name="contenu" rows="10" required 
                  style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 20px; font-family: Arial;"><?= htmlspecialchars($article['contenu']) ?></textarea>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: var(--color-primary); color: white; padding: 12px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; flex: 2;">
                Enregistrer les modifications
            </button>
            <a href="listes.php" style="background: #6c757d; color: white; padding: 12px 20px; border-radius: 5px; text-decoration: none; text-align: center; flex: 1;">
                Annuler
            </a>
        </div>
    </form>
</div>

<?php include '../footer.php'; ?>