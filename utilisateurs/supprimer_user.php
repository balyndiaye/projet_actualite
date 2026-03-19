<?php
// 1. SÉCURITÉ : Seul l'admin peut supprimer un utilisateur
$role_requis = 'administrateur'; 
include '../config/auth_check.php';
require_once '../config/db.php';

// 2. VÉRIFICATION : On vérifie qu'on a bien un ID dans l'URL
if (isset($_GET['id'])) {
    $id_a_supprimer = $_GET['id'];

    // 3. PROTECTION CRITIQUE : Empêcher l'admin de se supprimer lui-même !
    if ($id_a_supprimer == $_SESSION['id_user']) {
        header("Location: listes.php?err=auto_suppression_interdite");
        exit();
    }

    // 4. SUPPRESSION : On lance la requête
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $stmt->execute([$id_a_supprimer]);
}

// 5. RETOUR : On renvoie vers la liste avec un message de succès
header("Location: listes.php?msg=utilisateur_supprime");
exit();
?>