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
                $erreur = "Ce nom de catégorie est déjà utilisé par une autre rubrique.";
            } else {
                $update = $pdo->prepare("UPDATE categories SET nom = ? WHERE id = ?");
                $update->execute([$nouveau_nom, $id]);

                header("Location: listes.php?msg=success");
                exit();
            }
        } catch (PDOException $e) {
            $erreur = "Erreur technique lors de la mise à jour.";
        }
    } else {
        $erreur = "Le champ nom ne peut pas rester vide.";
    }
}

include '../entete.php';
include '../menu.php';
?>

<div class="container" style="margin-top: 60px; max-width: 600px;">

    <div style="background: #1a1a1a; padding: 20px; border-radius: 8px 8px 0 0; border-bottom: 4px solid #671E30;">
        <h2 style="color: white; margin: 0; font-size: 1.4em; display: flex; align-items: center; text-transform: uppercase; letter-spacing: 1px;">
            <span style="background: #671E30; width: 10px; height: 25px; display: inline-block; margin-right: 15px;"></span>
            Édition de Catégorie
        </h2>
    </div>

    <div style="background: white; padding: 40px; border-radius: 0 0 8px 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">

        <?php if (isset($erreur)): ?>
            <div style="color: white; background: #671E30; padding: 15px; border-radius: 4px; margin-bottom: 25px; border-left: 5px solid black; font-weight: 500;">
                <strong>Attention :</strong> <?= $erreur ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div style="margin-bottom: 30px;">
                <label style="font-weight:bold; display:block; margin-bottom:12px; color: #1a1a1a; text-transform: uppercase; font-size: 0.85em; letter-spacing: 1px;">
                    Nom de la catégorie à modifier
                </label>
                <input type="text" name="nom_categorie" value="<?= htmlspecialchars($cat['nom']) ?>" required
                    style="width: 100%; padding: 15px; border: 2px solid #eee; border-radius: 4px; font-size: 1.1em; outline: none; transition: border-color 0.3s;"
                    onfocus="this.style.borderColor='#671E30'" onblur="this.style.borderColor='#eee'">
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit"
                    style="background: #671E30; color: white; border: none; padding: 15px 30px; border-radius: 4px; cursor: pointer; font-weight: bold; flex: 2; text-transform: uppercase; font-size: 0.95em;">
                    Sauvegarder les changements
                </button>

                <a href="listes.php"
                    style="background: #1a1a1a; color: white; text-decoration: none; padding: 15px 20px; border-radius: 4px; text-align: center; flex: 1; font-weight: bold; text-transform: uppercase; font-size: 0.9em; display: flex; align-items: center; justify-content: center;">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>