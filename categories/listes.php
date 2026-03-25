<?php
session_start();
require_once '../config/db.php';

// SÉCURITÉ : On autorise l'admin ET l'editeur à accéder à la gestion des catégories
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editeur')) {
    header('Location: ../index.php');
    exit();
}

// Récupérer toutes les catégories
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom ASC")->fetchAll();

include '../entete.php';
include '../menu.php';
?>

<style>
    /* Conteneur principal style Dashboard */
    .admin-wrapper {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        margin: 40px auto;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border-top: 5px solid #6D071A; /* Ton bordeaux */
        max-width: 1000px;
    }

    /* Entête du tableau */
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f8f9fa;
    }

    .table-admin {
        width: 100%;
        border-collapse: collapse;
    }

    .table-admin th {
        text-align: left;
        padding: 15px;
        background: #fdfdfd;
        color: #888;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1.5px;
        font-weight: 800;
        border-bottom: 2px solid #eee;
    }

    .table-admin td {
        padding: 20px 15px;
        border-bottom: 1px solid #f1f1f1;
        vertical-align: middle;
    }

    /* Badge Icône pour le style */
    .cat-icon {
        width: 35px;
        height: 35px;
        background: #f8f1f2;
        color: #6D071A;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin-right: 12px;
        font-size: 0.9rem;
    }

    /* Bouton Ajouter */
    .btn-add {
        background: #1a1a1a;
        color: white;
        padding: 12px 25px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        font-size: 0.9rem;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-add:hover { background: #6D071A; }

    /* Liens d'actions */
    .action-link {
        text-decoration: none;
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
</style>

<main class="container">
    <div class="admin-wrapper">
        <div class="admin-header">
            <h2 style="color: #1a1a1a; margin: 0; font-weight: 900; font-size: 1.8rem; letter-spacing: -1px;">
                Gestion des Rubriques
            </h2>
            <a href="ajouter.php" class="btn-add">
                <i class="fas fa-plus-circle"></i> Nouvelle Catégorie
            </a>
        </div>

        <table class="table-admin">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nom de la catégorie</th>
                    <th style="text-align: right; padding-right: 30px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($categories) > 0): ?>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td style="color: #bbb; font-weight: bold; font-size: 0.85rem;">
                            #<?= $cat['id'] ?>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <div class="cat-icon"><i class="fas fa-tag"></i></div>
                                <span style="font-weight: 700; color: #333; font-size: 1.05rem;">
                                    <?= htmlspecialchars($cat['nom']) ?>
                                </span>
                            </div>
                        </td>
                        <td style="text-align: right; padding-right: 20px; white-space: nowrap;">
                            <a href="modifier.php?id=<?= $cat['id'] ?>" class="action-link" style="color: #6D071A; margin-right: 20px;">
                                <i class="fas fa-edit"></i> Editer
                            </a>
                            <a href="supprimer.php?id=<?= $cat['id'] ?>" class="action-link" style="color: #cc0000;" 
                               onclick="return confirm('Attention : Supprimer cette catégorie pourrait affecter les articles liés. Confirmer ?')">
                                <i class="fas fa-trash-alt"></i> Retirer
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="padding: 40px; text-align: center; color: #bbb;">
                            <i class="fas fa-info-circle" style="display: block; font-size: 2rem; margin-bottom: 10px;"></i>
                            Aucune catégorie trouvée.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../footer.php'; ?>