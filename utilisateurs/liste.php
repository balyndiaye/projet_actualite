<?php
$role_requis = 'administrateur'; 
include '../config/auth_check.php'; 
require_once '../config/db.php';
include '../entete.php';

$users = $pdo->query("SELECT id, login, role FROM utilisateurs")->fetchAll();
?>
<div class="container">
    <h1>Gestion des utilisateurs</h1>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>ID</th><th>Pseudo</th><th>Rôle</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['login']) ?></td>
                <td><strong><?= $u['role'] ?></strong></td>
                <td>
                    <?php if ($u['id'] != $_SESSION['id_user']): ?>
                        <a href="modifier_role.php?id=<?= $u['id'] ?>&role=editeur">Rendre Éditeur</a> |
                        <a href="supprimer_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>