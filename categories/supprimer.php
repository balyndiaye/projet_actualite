<?php
$role_requis = 'administrateur';
include '../config/auth_check.php';
require_once '../config/db.php';

if (isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header("Location: listes.php?msg=cat_ok");
    } catch (Exception $e) {
        // Erreur si la catégorie contient encore des articles
        header("Location: listes.php?err=cat_utilisee");
    }
}
exit();