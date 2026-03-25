<?php
session_start();
require_once '../config/db.php';

// Sécurité : Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: ../connexion.php');
    exit();
}

if (isset($_GET['id'])) {
    $id_article = $_GET['id'];
 $query = $pdo->prepare("SELECT id_utilisateur FROM articles WHERE id = ?");
    $query->execute([$id_article]);
    $article = $query->fetch();

    if (!$article) {
        header("Location: listes.php?err=article_inexistant");
        exit();
    }

    //  LOGIQUE DE DROITS
    if ($_SESSION['role'] === 'admin' || $article['id_utilisateur'] == $_SESSION['id_user']) {
        
        $delete = $pdo->prepare("DELETE FROM articles WHERE id = ?");
        $delete->execute([$id_article]);
        
        // Redirection vers la liste de gestion
        header("Location: listes.php?msg=article_supprime");
        exit();

    } else {
        // Accès refusé si ce n'est pas son article
        header("Location: listes.php?err=acces_refuse");
        exit();
    }
} else {
    header("Location: listes.php");
    exit();
}
?>