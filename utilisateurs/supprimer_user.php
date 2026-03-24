<?php
session_start();
require_once '../config/db.php';

// 1. SÉCURITÉ : On vérifie que c'est bien l'admin (on utilise 'admin' comme dans ta base)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php?err=acces_refuse');
    exit();
}

// 2. VÉRIFICATION : On vérifie qu'on a bien un ID dans l'URL
if (isset($_GET['id'])) {
    $id_a_supprimer = $_GET['id'];

    // 3. PROTECTION CRITIQUE : Empêcher l'admin de se supprimer lui-même
    if ($id_a_supprimer == $_SESSION['id_user']) {
        header("Location: liste.php?err=auto_suppression");
        exit();
    }

    // 4. SUPPRESSION : On lance la requête
    try {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id_a_supprimer]);
        
        // 5. RETOUR : Succès (vers liste.php sans 's')
        header("Location: liste.php?msg=supprime");
        exit();
    } catch (PDOException $e) {
        // En cas d'erreur (ex: utilisateur lié à des articles)
        header("Location: liste.php?err=suppression_impossible");
        exit();
    }
}

// Si pas d'ID, on repart simplement
header("Location: liste.php");
exit();