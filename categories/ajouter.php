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
    // Nettoyage de la donnée pour éviter les espaces inutiles
    $nom_cat = trim($_POST['nom_categorie']); 
    
    if (!empty($nom_cat)) {
        try {
            // On vérifie si elle n'existe pas déjà (insensible à la casse)
            $check = $pdo->prepare("SELECT id FROM categories WHERE LOWER(nom) = LOWER(?)");
            $check->execute([$nom_cat]);
            
            if ($check->rowCount() > 0) {
                $erreur = "Cette catégorie existe déjà dans la base de données.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO categories (nom) VALUES (?)");
                $stmt->execute([$nom_cat]);
                
                header("Location: listes.php?msg=cat_ajoutee");
                exit();
            }
        } catch (PDOException $e) {
            $erreur = "Une erreur est survenue lors de l'enregistrement.";
        }
    } else {
        $erreur = "Veuillez saisir un nom de catégorie.";
    }
}

include '../entete.php';
include '../menu.php';
?>

<div class="container" style="margin-top: 40px; max-width: 500px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
    <h2 style="color: #2c3e50; border-bottom: 3px solid #3922e6; padding-bottom: 12px; margin-bottom: 25px; font-size: 1.6em;">
         📁 Nouvelle Catégorie
    </h2>

    <?php if(isset($erreur)): ?>
        <div style="color: #721c24; background: #f8d7da; padding: 12px; border-radius: 6px; border: 1px solid #f5c6cb; margin-bottom: 20px; font-size: 0.95em;">
            ⚠️ <?= $erreur ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div style="margin-bottom: 20px;">
            <label style="font-weight:bold; display:block; margin-bottom:8px; color: #34495e;">Nom de la catégorie :</label>
            <input type="text" name="nom_categorie"