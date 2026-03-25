<?php
session_start();
require_once '../config/db.php';

// 1. SÉCURITÉ : On autorise l'admin ET l'editeur
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editeur')) {
    header('Location: ../index.php?err=acces_refuse');
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // 2. VÉRIFICATION AVANT SUPPRESSION (Sécurité Backend)
        // On vérifie si des articles sont liés à cette catégorie
        $check = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE id_categorie = ?");
        $check->execute([$id]);
        $count = $check->fetchColumn();

        if ($count > 0) {
            // Si la catégorie contient des articles, on refuse la suppression
            header("Location: listes.php?err=cat_utilisee");
            exit();
        }

        // 3. TENTATIVE DE SUPPRESSION
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: listes.php?msg=cat_supprimee");
        exit();

    } catch (PDOException $e) {
        // En cas d'erreur SQL imprévue
        header("Location: listes.php?err=sql_error");
        exit();
    }
} else {
    header("Location: listes.php");
    exit();
}