<?php
// 1. SÉCURITÉ : Seul l'admin change le nom des catégories
$role_requis = 'administrateur'; 
include '../config/auth_check.php';
require_once '../config/db.php';
include '../entete.php'; // Pour garder le menu en haut

// 2. RÉCUPÉRATION : On cherche la catégorie actuelle
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $cat = $stmt->fetch();

    if (!$cat) {
        die("Catégorie introuvable.");
    }
}

// 3. TRAITEMENT : Si on clique sur le bouton "Enregistrer"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nouveau_nom = $_POST['libelle'];
    
    $update = $pdo->prepare("UPDATE categories SET libelle = ? WHERE id = ?");
    $update->execute([$nouveau_nom, $id]);
    
    // Redirection vers la liste des catégories
    header("Location: listes.php?msg=cat_modifiee");
    exit();
}
?>

<div class="container">
    <h2>Modifier la catégorie</h2>
    <form method="POST">
        <label>Nom de la catégorie :</label><br>
        <input type="text" name="libelle" value="<?= htmlspecialchars($cat['libelle']) ?>" required>
        <br><br>
        <button type="submit">Mettre à jour</button>
        <a href="listes.php">Annuler</a>
    </form>
</div>