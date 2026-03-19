<?php 
$role_requis = 'administrateur'; 
include '../config/auth_check.php';
require_once '../config/db.php';
include '../entete.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $libelle = $_POST['libelle'];
    if (!empty($libelle)) {
        $stmt = $pdo->prepare("INSERT INTO categories (libelle) VALUES (?)");
        $stmt->execute([$libelle]);
        header("Location: listes.php");
        exit();
    }
}
?>
<div class="container">
    <h2>Nouvelle Catégorie</h2>
    <form method="POST">
        <input type="text" name="libelle" placeholder="Nom (ex: Sport)" required>
        <button type="submit" class="btn">Enregistrer</button>
    </form>
</div>