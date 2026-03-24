<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../config/db.php';

// Sécurité : Vérifier si l'utilisateur est bien connecté
if (!isset($_SESSION['login'])) {
    header('Location: ../connexion.php');
    exit();
}

// On définit URL_BASE seulement s'il n'existe pas déjà
if(!defined('URL_BASE')) define('URL_BASE', '../'); 

try {
    if ($_SESSION['role'] === 'admin') {
        $query = "SELECT a.*, c.nom as categorie_nom, u.login as auteur_nom 
                  FROM articles a 
                  LEFT JOIN categories c ON a.id_categorie = c.id 
                  LEFT JOIN utilisateurs u ON a.id_utilisateur = u.id 
                  ORDER BY a.date_creation DESC";
        $stmt = $pdo->query($query);
    } else {
        $query = "SELECT a.*, c.nom as categorie_nom 
                  FROM articles a 
                  LEFT JOIN categories c ON a.id_categorie = c.id 
                  WHERE a.id_utilisateur = ? 
                  ORDER BY a.date_creation DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$_SESSION['id_user']]);
    }
    $articles = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}

include '../entete.php'; 
include '../menu.php'; 
?>

<style>
    .admin-wrapper {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        margin: 30px auto;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border-top: 4px solid var(--color-accent);
    }
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .table-admin {
        width: 100%;
        border-collapse: collapse;
    }
    .table-admin th {
        text-align: left;
        padding: 15px;
        background: #f9f9f9;
        color: #444;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
    }
    .table-admin td {
        padding: 15px;
        border-bottom: 1px solid #f1f1f1;
    }
    .badge-cat {
        background: var(--color-primary);
        color: white;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 11px;
    }
    .action-links a {
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
    }
</style>

<main class="container">
    <div class="admin-wrapper">
        <div class="admin-header">
            <h2 style="color: var(--color-primary); margin: 0;">Tableau de bord Articles</h2>
            <a href="ajouter.php" style="background: #27ae60; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">+ Nouvel Article</a>
        </div>

        <?php if (empty($articles)): ?>
            <div style="text-align:center; padding: 50px; color: #999;">
                <p>Vous n'avez pas encore rédigé d'articles.</p>
            </div>
        <?php else: ?>
            <table class="table-admin">
                <thead>
                    <tr>
                        <th>Aperçu</th>
                        <th>Titre de l'actu</th>
                        <th>Catégorie</th>
                        <?php if($_SESSION['role'] === 'admin'): ?><th>Auteur</th><?php endif; ?>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $art): ?>
                        <tr>
                            <td>
                                <img src="../<?php echo htmlspecialchars($art['image']); ?>" 
                                     style="width:70px; height:45px; object-fit:cover; border-radius:4px; border: 1px solid #ddd;" 
                                     onerror="this.src='../img/default.jpg'">
                            </td>
                            <td>
                                <div style="font-weight: bold; color: #333;"><?php echo htmlspecialchars($art['titre']); ?></div>
                                <small style="color: #888;">Publié le <?php echo date('d/m/Y', strtotime($art['date_creation'])); ?></small>
                            </td>
                            <td><span class="badge-cat"><?php echo htmlspecialchars($art['categorie_nom'] ?? 'Général'); ?></span></td>
                            
                            <?php if($_SESSION['role'] === 'admin'): ?>
                                <td style="color: var(--color-accent); font-weight: bold;"><?php echo htmlspecialchars($art['auteur_nom']); ?></td>
                            <?php endif; ?>

                            <td class="action-links" style="text-align: right;">
                                <a href="modifier.php?id=<?php echo $art['id']; ?>" style="color: #f39c12;">Modifier</a>
                                <span style="color: #ddd; margin: 0 10px;">|</span>
                                <a href="supprimer.php?id=<?php echo $art['id']; ?>" style="color: #e74c3c;" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<?php include '../footer.php'; ?>