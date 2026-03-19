<?php 
$role_requis = 'editeur';
include '../config/auth_check.php';
require_once '../config/db.php';
include '../entete.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

// SECURITÉ SUPPLÉMENTAIRE : Un éditeur ne peut modifier que SES articles
// (Sauf s'il est admin)
if ($_SESSION['role'] !== 'administrateur' && $article['id_auteur'] !== $_SESSION['id_user']) {
    header("Location: ../index.php?erreur=non_autorise");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $update = $pdo->prepare("UPDATE articles SET titre = ?, contenu = ?, id_categorie = ? WHERE id = ?");
    $update->execute([$_POST['titre'], $_POST['contenu'], $_POST['id_categorie'], $id]);
    header("Location: ../index.php");
    exit();
}
?>
<div class="container">
    <h2>Modifier l'article</h2>
    <form method="POST">
        <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required><br><br>
        <textarea name="contenu" rows="10"><?= htmlspecialchars($article['contenu']) ?></textarea><br><br>
        <button type="submit" class="btn">Mettre à jour</button>
    </form>
</div>