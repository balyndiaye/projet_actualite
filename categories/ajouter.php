<?php 
session_start();
require_once '../config/db.php';

// 1. SÉCURITÉ : On vérifie si l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php?err=acces_refuse');
    exit();
}

// 2. TRAITEMENT : Enregistrement de la nouvelle catégorie
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CORRECTION : On utilise 'nom' pour correspondre à ta base de données
    $nom_cat = $_POST['nom_categorie']; 
    
    if (!empty($nom_cat)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (nom) VALUES (?)");
            $stmt->execute([$nom_cat]);
            
            // Redirection vers la liste (avec un "s" si ton fichier s'appelle listes.php)
            header("Location: listes.php?msg=cat_ajoutee");
            exit();
        } catch (PDOException $e) {
            $erreur = "Cette catégorie existe peut-être déjà.";
        }
    }
}

include '../entete.php';
include '../menu.php';
?>

<div class="container" style="margin-top: 40px; max-width: 500px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h2 style="color: var(--color-primary); border-bottom: 2px solid var(--color-accent); padding-bottom: 10px; margin-bottom: 20px;">
         Nouvelle Catégorie
    </h2>

    <?php if(isset($erreur)): ?>
        <p style="color: red; background: #fee; padding: 10px; border-radius: 5px;"><?= $erreur ?></p>
    <?php endif; ?>

    <form method="POST">
        <label style="font-weight:bold; display:block; margin-bottom:10px;">Nom de la catégorie :</label>
        <input type="text" name="nom_categorie" placeholder="Ex: Technologie, Santé..." required 
               style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 20px; font-size: 16px;">
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #274dae; color: white; padding: 12px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; flex: 2;">
                 Enregistrer
            </button>
            <a href="listes.php" style="background: #6c757d; color: white; padding: 12px 20px; border-radius: 5px; text-decoration: none; text-align: center; flex: 1;">
                Annuler
            </a>
        </div>
    </form>
</div>

<?php include '../footer.php'; ?>