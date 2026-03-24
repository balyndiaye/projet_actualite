<?php
session_start();
require_once '../config/db.php';

// Sécurité : Seul l'admin peut gérer les catégories
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Récupérer toutes les catégories
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom ASC")->fetchAll();

include '../entete.php';
include '../menu.php';
?>

<main class="container">
    <div style="background: white; padding: 30px; border-radius: 10px; margin-top: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="color: var(--color-primary);">📁 Gestion des Catégories</h2>
            <a href="ajouter.php" class="btn" style="background: var(--color-accent); color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">+ Nouvelle Catégorie</a>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                    <th style="padding: 15px; text-align: left;">ID</th>
                    <th style="padding: 15px; text-align: left;">Nom de la catégorie</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px;"><?= $cat['id'] ?></td>
                    <td style="padding: 15px; font-weight: bold;"><?= htmlspecialchars($cat['nom']) ?></td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="modifier.php?id=<?= $cat['id'] ?>" style="color: #f39c12; text-decoration: none; margin-right: 10px;">Modifier</a>
                        <a href="supprimer.php?id=<?= $cat['id'] ?>" style="color: #e74c3c; text-decoration: none;" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../footer.php'; ?>