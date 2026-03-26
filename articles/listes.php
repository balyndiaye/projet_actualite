<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../config/db.php';

// 1. Sécurité : Vérifier si l'utilisateur est bien connecté
if (!isset($_SESSION['login'])) {
    header('Location: ../connexion.php');
    exit();
}

// On définit URL_BASE seulement s'il n'existe pas déjà
if (!defined('URL_BASE')) define('URL_BASE', '../');

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
        padding: 30px;
        border-radius: 12px;
        margin: 40px auto;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border-top: 5px solid #6D071A;
        /* Ton Bordeaux */
        max-width: 1200px;
    }

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

    .badge-cat {
        background: #6D071A;
        /* Bordeaux */
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .btn-add {
        background: #1a1a1a;
        color: white;
        padding: 12px 25px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .btn-add:hover {
        background: #6D071A;
    }
</style>

<main class="container">
    <div class="admin-wrapper">
        <div class="admin-header">
            <h2 style="color: #1a1a1a; margin: 0; font-weight: 900; font-size: 1.8rem; letter-spacing: -1px;">Gestion des Articles</h2>
            <a href="ajouter.php" class="btn-add">
                <i class="fas fa-plus" style="margin-right: 8px;"></i> Nouvel Article
            </a>
        </div>

        <?php if (empty($articles)): ?>
            <div style="text-align:center; padding: 60px; color: #bbb;">
                <i class="fas fa-file-alt" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.3;"></i>
                <p style="font-size: 1.1rem;">Aucun article trouvé dans votre base.</p>
            </div>
        <?php else: ?>
            <table class="table-admin">
                <thead>
                    <tr>
                        <th style="width: 100px;">Aperçu</th>
                        <th>Titre de l'actu</th>
                        <th>Catégorie</th>
                        <?php if ($_SESSION['role'] === 'admin'): ?><th>Auteur</th><?php endif; ?>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $art): ?>
                        <tr>
                            <td>
                                <?php
                                // CHEMIN IMAGE : On sort de /articles/ et on va dans /uploads/
                                $imagePath = "../uploads/" . $art['image'];
                                ?>
                                <img src="<?php echo htmlspecialchars($imagePath); ?>"
                                    style="width:80px; height:55px; object-fit:cover; border-radius:6px; background: #eee;"
                                    onerror="this.src='../img/default.jpg'">
                            </td>
                            <td>
                                <div style="font-weight: 800; color: #1a1a1a; font-size: 1.05rem; margin-bottom: 5px;">
                                    <?php echo htmlspecialchars($art['titre']); ?>
                                </div>
                                <div style="font-size: 0.8rem; color: #999;">
                                    <i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y', strtotime($art['date_creation'])); ?>
                                </div>
                            </td>
                            <td><span class="badge-cat"><?php echo htmlspecialchars($art['categorie_nom'] ?? 'Général'); ?></span></td>

                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <td style="color: #6D071A; font-weight: 700; font-size: 0.9rem;">
                                    <i class="far fa-user" style="margin-right: 5px; opacity: 0.5;"></i>
                                    <?php echo htmlspecialchars($art['auteur_nom']); ?>
                                </td>
                            <?php endif; ?>

                            <td style="text-align: right; white-space: nowrap;">
                                <a href="modifier.php?id=<?php echo $art['id']; ?>" style="color: #6D071A; text-decoration: none; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; margin-right: 15px;">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <a href="supprimer.php?id=<?php echo $art['id']; ?>" style="color: #cc0000; text-decoration: none; font-weight: 800; font-size: 0.8rem; text-transform: uppercase;" onclick="return confirm('Confirmer la suppression ?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<?php include '../footer.php'; ?>