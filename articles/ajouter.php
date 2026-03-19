<?php 
$role_requis = 'editeur'; // L'éditeur ET l'admin (grâce au code dans auth_check) peuvent entrer
include '../config/auth_check.php';
require_once '../config/db.php';
include '../entete.php';

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $id_cat = $_POST['id_categorie'];
    $id_auteur = $_SESSION['id_user']; // On récupère l'ID de celui qui écrit

    $stmt = $pdo->prepare("INSERT INTO articles (titre, contenu, id_categorie, id_auteur) VALUES (?, ?, ?, ?)");
    $stmt->execute([$titre, $contenu, $id_cat, $id_auteur]);
    header("Location: ../index.php");
    exit();
}
?>
<div class="container">
    <h2>Rédiger un article</h2>
    <form method="POST">
        <input type="text" name="titre" placeholder="Titre" required style="width:100%"><br><br>
        <select name="id_categorie">
            <?php foreach($categories as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['libelle']) ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <textarea name="contenu" placeholder="Contenu..." rows="10" style="width:100%"></textarea><br><br>
        <button type="submit" class="btn">Publier</button>
    </form>
</div>