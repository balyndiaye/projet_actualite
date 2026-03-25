<?php
session_start();
require_once '../config/db.php';

// Sécurité
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editeur')) {
    header('Location: ../index.php?err=acces_refuse');
    exit();
}

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = trim($_POST['titre']);
    $contenu = trim($_POST['contenu']);
    $id_categorie = $_POST['id_categorie']; // On récupère l'ID du formulaire
    $user_id = $_SESSION['id_user'];

    // GESTION DE L'IMAGE
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $nom_image = time() . '_' . basename($_FILES['image']['name']);
        $chemin_destination = "../uploads/" . $nom_image;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $chemin_destination)) {
            try {
                $sql = "INSERT INTO articles (titre, contenu, image, id_categorie,id_utilisateur, date_creation) 
                        VALUES (?, ?, ?, ?, ?, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$titre, $contenu, $nom_image, $id_categorie, $user_id]);

                header("Location: ../index.php?msg=article_publie");
                exit();
            } catch (PDOException $e) {
                $erreur = "Erreur SQL : " . $e->getMessage();
            }
        } else {
            $erreur = "Erreur : Vérifie que le dossier 'uploads' existe bien.";
        }
    } else {
        $erreur = "Veuillez sélectionner une image.";
    }
}


$categories = $pdo->query("SELECT * FROM categories ORDER BY nom ASC")->fetchAll();

include '../entete.php';
include '../menu.php';
?>

<main class="container" style="margin-top: 30px; max-width: 800px; font-family: 'Segoe UI', sans-serif;">
    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1);">
        
        <h2 style="color: #2c3e50; margin-bottom: 25px; border-bottom: 4px solid #3922e6; padding-bottom: 10px;">
             Ajouter une Actualité
        </h2>

        <?php if($erreur): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <?= $erreur ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Titre de l'actualité</label>
                <input type="text" name="titre" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div style="flex: 1;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px;">Catégorie</label>
                    <select name="id_categorie" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: white;">
                        <option value="">-- Choisir --</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="flex: 1;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #3922e6;">Photo (Explorateur)</label>
                    <input type="file" name="image" accept="image/*" required style="width: 100%;">
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Texte de l'article</label>
                <textarea name="contenu" rows="6" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; resize: vertical;"></textarea>
            </div>

            <button type="submit" style="width: 100%; background: #3922e6; color: white; border: none; padding: 18px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 18px;">
                Publier l'article
            </button>

        </form>
    </div>
</main>

<?php include '../footer.php'; ?>