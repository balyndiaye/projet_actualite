<?php 
session_start();
require_once '../config/db.php';

// Sécurité : Seul l'admin accède à la liste
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

include '../entete.php';
include '../menu.php';

// Récupération des utilisateurs mis à jour
$users = $pdo->query("SELECT id, login, role FROM utilisateurs ORDER BY id ASC")->fetchAll();
?>

<main class="container" style="margin-top: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 20px; border-radius: 10px 10px 0 0; border-bottom: 3px solid #3922e6; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h1 style="margin: 0; color: #2c3e50;"> Gestion des utilisateurs</h1>
        <a href="ajouter.php" style="background: #27ae60; color: white; padding: 12px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">
            + Nouvel Utilisateur
        </a>
    </div>

    <div style="background: white; padding: 20px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #ddd;">
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">Pseudo</th>
                    <th style="padding: 12px; text-align: left;">Rôle actuel</th>
                    <th style="padding: 12px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;"><?= $u['id'] ?></td>
                    <td style="padding: 12px; font-weight: bold;"><?= htmlspecialchars($u['login']) ?></td>
                    <td style="padding: 12px;">
                        <span style="padding: 5px 10px; border-radius: 4px; font-size: 0.85em; font-weight: bold; background: <?= $u['role'] === 'admin' ? '#d1ecf1' : '#e2e3e5' ?>; color: <?= $u['role'] === 'admin' ? '#0c5460' : '#383d41' ?>;">
                            <?= strtoupper($u['role']) ?>
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <?php if ($u['id'] != $_SESSION['id_user']): ?>
    
    <?php if ($u['role'] === 'admin'): ?>
        <a href="modifier_role.php?id=<?= $u['id'] ?>&role=editeur" 
           style="color: #17a2b8; text-decoration: none; font-size: 0.9em; margin-right: 15px;">
           Rendre Éditeur
        </a>
    <?php else: ?>
        <a href="modifier_role.php?id=<?= $u['id'] ?>&role=admin" 
           style="color: #2a0ba4; text-decoration: none; font-size: 0.9em; margin-right: 15px;">
           Rendre Admin
        </a>
    <?php endif; ?>

    <a href="supprimer_user.php?id=<?= $u['id'] ?>" 
       style="color: #dc3545; text-decoration: none; font-weight: bold; font-size: 0.9em;" 
       onclick="return confirm('Supprimer cet utilisateur ?')">
       Supprimer
    </a>

<?php else: ?>
    <small style="color: #999; font-style: italic;">(C'est vous)</small>
<?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>