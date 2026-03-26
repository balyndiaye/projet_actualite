<?php 
session_start();
require_once '../config/db.php';

// 1. SÉCURITÉ : On autorise l'admin ET l'editeur
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editeur')) {
    header('Location: ../index.php?err=acces_refuse');
    exit();
}

// 2. TRAITEMENT : Enregistrement de la nouvelle catégorie
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_cat = trim($_POST['nom_categorie']); 
    
    if (!empty($nom_cat)) {
        try {
            $check = $pdo->prepare("SELECT id FROM categories WHERE LOWER(nom) = LOWER(?)");
            $check->execute([$nom_cat]);
            
            if ($check->rowCount() > 0) {
                $erreur = "Cette catégorie existe déjà.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO categories (nom) VALUES (?)");
                $stmt->execute([$nom_cat]);
                
                header("Location: listes.php?msg=cat_ajoutee");
                exit();
            }
        } catch (PDOException $e) {
            $erreur = "Erreur lors de l'enregistrement.";
        }
    } else {
        $erreur = "Veuillez saisir un nom.";
    }
}

include '../entete.php';
include '../menu.php';
?>

<div class="container" style="margin-top: 60px; max-width: 600px;">
    <div style="background: #1a1a1a; padding: 20px; border-radius: 8px 8px 0 0; border-bottom: 4px solid #671E30;">
        <h2 style="color: white; margin: 0; font-size: 1.4em; display: flex; align-items: center;">
            <span style="background: #671E30; width: 10px; height: 25px; display: inline-block; margin-right: 15px;"></span>
            GESTION DES CATÉGORIES
        </h2>
    </div>

    <div style="background: white; padding: 40px; border-radius: 0 0 8px 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        
        <?php if(isset($erreur)): ?>
            <div style="color: white; background: #671E30; padding: 15px; border-radius: 4px; margin-bottom: 25px; border-left: 5px solid black;">
                <strong>Erreur :</strong> <?= $erreur ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div style="margin-bottom: 25px;">
                <label style="font-weight:bold; display:block; margin-bottom:10px; color: #1a1a1a; text-transform: uppercase; font-size: 0.85em; letter-spacing: 1px;">
                    Nom de la nouvelle catégorie
                </label>
                <input type="text" name="nom_categorie" required 
                       placeholder="Saisissez le nom ici..." 
                       style="width: 100%; padding: 15px; border: 2px solid #eee; border-radius: 4px; font-size: 1em; outline: none; transition: border 0.3s;">
            </div>

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit" 
                        style="background: #671E30; color: white; border: none; padding: 15px 25px; border-radius: 4px; cursor: pointer; font-weight: bold; flex: 2; text-transform: uppercase; transition: background 0.3s;">
                    Confirmer l'ajout
                </button>
                
                <a href="listes.php" 
                   style="background: #1a1a1a; color: white; text-decoration: none; padding: 15px 25px; border-radius: 4px; text-align: center; flex: 1; font-weight: bold; text-transform: uppercase; font-size: 0.9em;">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>