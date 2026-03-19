<?php
// 1. On autorise l'entrée à partir du rang 'editeur'
$role_requis = 'editeur'; 
include '../config/auth_check.php';
require_once '../config/db.php';

// On vérifie si l'ID de l'article est bien présent dans l'URL
if (isset($_GET['id'])) {
    $id_article = $_GET['id'];

    // 2. ÉTAPE DE VÉRIFICATION : On va chercher qui est l'auteur de cet article
    $query = $pdo->prepare("SELECT id_auteur FROM articles WHERE id = ?");
    $query->execute([$id_article]);
    $article = $query->fetch();

    // Si l'article n'existe pas, on arrête tout
    if (!$article) {
        header("Location: ../index.php?err=article_inexistant");
        exit();
    }

    // 3. LA LOGIQUE DE DROITS :
    // On autorise la suppression SI :
    // - L'utilisateur est 'administrateur' 
    // - OU SI l'id_auteur de l'article correspond à l'ID de la personne connectée
    if ($_SESSION['role'] === 'administrateur' || $article['id_auteur'] == $_SESSION['id_user']) {
        
        $delete = $pdo->prepare("DELETE FROM articles WHERE id = ?");
        $delete->execute([$id_article]);
        
        // Succès !
        header("Location: ../index.php?msg=article_supprime");
        exit();

    } else {
        // TENTATIVE DE FRAUDE : L'éditeur essaie de supprimer l'article d'un autre
        header("Location: ../index.php?err=acces_refuse");
        exit();
    }
} else {
    // Pas d'ID fourni dans l'URL
    header("Location: ../index.php");
    exit();
}
?>