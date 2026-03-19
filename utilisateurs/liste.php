<?php
// On inclut la sécurité (seul un admin devrait voir ça, mais on commence par la base)
session_start();
require_once '../config/db.php'; // Note le ../ pour remonter d'un dossier

// On récupère tous les utilisateurs
$stmt = $pdo->query("SELECT id, login, role FROM utilisateurs");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des utilisateurs</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Gestion des utilisateurs</h1>
    <a href="../index.php">Retour à l'accueil</a>
    <br><br>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Login (Pseudo)</th>
                <th>Rôle</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo htmlspecialchars($u['login']); ?></td>
                <td><?php echo htmlspecialchars($u['role']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>