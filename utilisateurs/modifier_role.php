<?php
$role_requis = 'administrateur';
include '../config/auth_check.php';
require_once '../config/db.php';

if (isset($_GET['id']) && isset($_GET['role'])) {
    $stmt = $pdo->prepare("UPDATE utilisateurs SET role = ? WHERE id = ?");
    $stmt->execute([$_GET['role'], $_GET['id']]);
}
header("Location: listes.php");
exit();