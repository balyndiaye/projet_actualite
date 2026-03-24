<?php
session_start();
require_once '../config/db.php';

// 1. SÉCURITÉ : Seul l'admin peut supprimer une catégorie
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php?err=acces_refuse');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // 2. TENTATIVE DE SUPPRESSION
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        
        // Redirection vers la liste (vérifie bien s'il y a un "s" ou pas à ton fichier)
       header("Location: listes.php?msg=cat_supprimee");
        exit();

    } catch (PDOException $e) {
        // 3. GESTION D'ERREUR : Si la catégorie est liée à des articles (Contrainte de clé étrangère)
        // On ne peut pas supprimer une catégorie qui contient encore des articles
        header("Location: liste.php?err=cat_utilisee");
        exit();
    }
} else {
    header("Location: liste.php");
    exit();
}