<?php 
require_once '../config/db.php';
include '../entete.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $libelle = $_POST['libelle'];
    if (!empty($libelle)) {
        // Requête préparée pour la sécurité
        $stmt = $pdo->prepare("INSERT INTO categories (libelle) VALUES (?)");
        $stmt->execute([$libelle]);
        header("Location: liste.php");
        exit();
    }
}
?>
<div class="container">
    <h2>Ajouter une Catégorie</h2>
    <form method="POST">
        <label>Nom de la catégorie :</label><br>
        <input type="text" name="libelle" required><br><br>
        <button type="submit" class="btn">Enregistrer</button>
        <a href="liste.php">Annuler</a>
    </form>
</div>