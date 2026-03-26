<?php
session_start();

// Vérification de la connexion globale
// On vérifie si le rôle existe (ce qui prouve que l'utilisateur est connecté)
if (!isset($_SESSION['role'])) {
    header('Location: ../connexion.php');
    exit();
}

// Vérification du niveau de droits (si la page demande un rôle spécifique)
if (isset($role_requis)) {
    // Si l'utilisateur n'a pas le rôle demandé ET n'est pas administrateur
    if ($_SESSION['role'] !== $role_requis && $_SESSION['role'] !== 'administrateur') {
        // On le renvoie à l'accueil avec un message d'erreur
        header("Location: ../index.php?erreur=acces_refuse");
        exit();
    }
}
