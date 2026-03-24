<?php
session_start();
require_once '../config/db.php';

// 1. SÉCURITÉ : On vérifie le rôle 'admin' (tel qu'écrit dans ta base)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php?err=acces_refuse');
    exit();
}

// 2. RÉCUPÉRATION de la catégorie
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $cat = $stmt->fetch();

    if (!$cat) {
        header("Location: listes.php?err=introuvable");
        exit();
    }
} else {
    header("Location: listes.php");
    exit();
}

// 3. TRAITEMENT de la modification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CORRECTION : On utilise 'nom' car c'est le nom dans ta table SQL
    $nouveau_nom = $_POST['nom_categorie']; 
    
    $update = $pdo->prepare("UPDATE categories SET nom = ? WHERE id = ?");
    
    try {
        $update->execute([$nouveau_nom, $id]);
        // Attention : vérifie si ton fichier s'appelle liste.php ou listes.php
        header("Location: listes.php?msg=success"); 
        exit();
    } catch (PDOException $e) {
        $erreur = "Erreur : " . $e->getMessage();
    }
}

include '../entete.php';
include '../menu.php';
?>

<div class="container" style="margin-top: 40px; max-width: 600px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h2 style="color: var(--color-primary); border-bottom: 2px solid var(--color-accent); padding-bottom: 10px; margin-bottom: 20px;">
        Modifier la catégorie
    </h2>

    <?php if(isset($erreur)): ?>
        <p style="color: red;"><?= $erreur ?></p>
    <?php endif; ?>

    <form method="POST">
        <label style="font-weight:bold; display:block; margin-bottom:10px;">Nom de la catégorie :</label>
        <input type="text" name="nom_categorie" value="<?= htmlspecialchars($cat['nom']) ?>" required 
               style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 20px; font-size: 16px;">
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: var(--color-primary); color: white; padding: 12px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; flex: 2;">
                Mettre à jour
            </button>
            <a href="listes.php" style="background: #6c757d; color: white; padding: 12px 20px; border-radius: 5px; text-decoration: none; text-align: center; flex: 1;">
                Annuler
            </a>
        </div>
    </form>
</div>

<?php include '../footer.php'; ?>