<?php
session_start();
require_once '../config/db.php';

// SÉCURITÉ : On vérifie que c'est bien l'admin 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php?err=acces_refuse');
    exit();
}


if (isset($_GET['id'])) {
    $id_a_supprimer = $_GET['id'];

    // Empêcher l'admin de se supprimer lui-même
    if ($id_a_supprimer == $_SESSION['id_user']) {
        header("Location: liste.php?err=auto_suppression");
        exit();
    }

    //SUPPRESSION : On lance la requête
    try {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id_a_supprimer]);

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
