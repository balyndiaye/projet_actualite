<?php
session_start();
require_once '../config/db.php';

// CORRECTION SÉCURITÉ : On autorise l'admin ET l'editeur à accéder à la gestion des catégories
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editeur')) {
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
            <h2 style="color: #2c3e50;">📁 Gestion des Catégories</h2>
            <a href="ajouter.php" style="background: #3922e6; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">+ Nouvelle Catégorie</a>
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
                <?php if (count($categories) > 0): ?>
                    <?php foreach ($categories as $cat): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px;"><?= $cat['id'] ?></td>
                        <td style="padding: 15px; font-weight: bold;"><?= htmlspecialchars($cat['nom']) ?></td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="modifier.php?id=<?= $cat['id'] ?>" style="color: #f39c12; text-decoration: none; margin-right: 15px; font-weight: bold;">✏️ Modifier</a>
                            <a href="supprimer.php?id=<?= $cat['id'] ?>" style="color: #e74c3c; text-decoration: none; font-weight: bold;" onclick="return confirm('Attention : Supprimer cette catégorie pourrait affecter les articles liés. Confirmer ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="padding: 20px; text-align: center; color: #999;">Aucune catégorie trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../footer.php'; ?>