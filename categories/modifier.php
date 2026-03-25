<?php 
session_start();
require_once '../config/db.php';

// 1. SÉCURITÉ : On autorise l'admin ET l'editeur
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editeur')) {
    header('Location: ../index.php?err=acces_refuse');
    exit();
}

// 2. RÉCUPÉRATION de la catégorie
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
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
    $nouveau_nom = trim($_POST['nom_categorie']); 
    
    if (!empty($nouveau_nom)) {
        try {
            // Vérifier si le nouveau nom n'est pas déjà utilisé par UNE AUTRE catégorie
            $check = $pdo->prepare("SELECT id FROM categories WHERE LOWER(nom) = LOWER(?) AND id != ?");
            $check->execute([$nouveau_nom, $id]);
            
            if ($check->rowCount() > 0) {
                $erreur = "Ce nom de catégorie est déjà utilisé.";
            } else {
                $update = $pdo->prepare("UPDATE categories SET nom = ? WHERE id = ?");
                $update->execute([$nouveau_nom, $id]);
                
                header("Location: listes.php?msg=success"); 
                exit();
            }
        } catch (PDOException $e) {
            $erreur = "Erreur lors de la mise à jour.";
        }
    } else {
        $erreur = "Le nom ne peut pas être vide.";
    }
}

include '../entete.php';
include '../menu.php';
?>

<div class="container" style="margin-top: 40px; max-width: 600px; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
    <h2 style="color: #2c3e50; border-left: 5px solid #3922e6; padding-left: 15px; margin-bottom: 30px; font-size: 1.8em;">
         Modifier la catégorie
    </h2>

    <?php if(isset($erreur)): ?>
        <div style="color: #721c24; background: #f8d7da; padding: 15px; border-radius: 8px; border: 1px solid #f5c6cb; margin-bottom: 25px;">
             <?= $erreur ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div style="margin-bottom: 25px;">
            <label style="font-weight:bold; display:block; margin-bottom:10px; color: #34495e;">Nom actuel de la catégorie :</label>
            <input type="text" name="nom_categorie" value="<?= htmlspecialchars($cat['nom']) ?>" required 
                   style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; outline: none; border-color: #3922e6;">
        </div>
        
        <div style="display: flex; gap: 15px;">
            <button type="submit" style="background: #3922e6; color: white; padding: 15px 30px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; flex: 2; font-size: 1.1em;">
                 Enregistrer les modifications
            </button>
            <a href="listes.php" style="background: #f1f1f1; color: #555; padding: 15px 25px; border-radius: 8px; text-decoration: none; text-align: center; flex: 1; font-weight: 600; font-size: 1.1em;">
                Annuler
            </a>
        </div>
    </form>
</div>

<?php include '../footer.php'; ?>