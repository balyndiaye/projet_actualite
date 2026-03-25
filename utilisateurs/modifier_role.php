<?php
session_start();
require_once '../config/db.php';

// Sécurité : Seul un admin connecté peut modifier des rôles
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Vérification des paramètres reçus dans l'URL
if (isset($_GET['id']) && isset($_GET['role'])) {
    $id_a_modifier = $_GET['id'];
    $nouveau_role = $_GET['role'];

    // Empêcher de modifier son propre compte (pour ne pas s'enlever ses droits d'admin)
    if ($id_a_modifier != $_SESSION['id_user']) {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET role = ? WHERE id = ?");
        $stmt->execute([$nouveau_role, $id_a_modifier]);
    }
}

//Redirection immédiate vers la liste pour voir le changement
header("Location: liste.php");
exit();