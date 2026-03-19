<?php 
// Démarrer la session si elle n'existe pas déjà
if (session_status() === PHP_SESSION_NONE) session_start(); 
?>
<nav>
    <a href="index.php">Accueil</a>
    
    <?php if(isset($_SESSION['role'])): ?>
        <a href="articles/liste.php">Gérer Articles</a>
        <a href="categories/liste.php">Catégories</a>
        
        <?php if($_SESSION['role'] == 'administrateur'): ?>
            <a href="utilisateurs/liste.php">Gérer Utilisateurs</a>
        <?php endif; ?>
        
        <a href="deconnexion.php" style="color:red;">Déconnexion (<?php echo $_SESSION['login']; ?>)</a>
        
    <?php else: ?>
        <a href="connexion.php">Connexion</a>
    <?php endif; ?>
</nav>

<div class="container"> ```

---

### 4. La Page d'Accueil (`index.php`)
[cite_start]Elle assemble les morceaux et affiche la liste des articles avec pagination [cite: 52-74].

```php
<?php
// Inclusion des fichiers de structure
require_once 'config/db.php'; // Fichier du Dév A
include 'entete.php';
include 'menu.php';

// Logique de pagination simple
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$parPage = 5;
$offset = ($page - 1) * $parPage;

// Requête SQL pour les articles
$stmt = $pdo->query("SELECT a.*, c.libelle as cat FROM articles a 
                     LEFT JOIN categories c ON a.id_categorie = c.id 
                     ORDER BY date_publication DESC LIMIT $parPage OFFSET $offset");
$articles = $stmt->fetchAll();
?>

<h2>Dernières Actualités</h2>

<?php foreach($articles as $a): ?>
    <div class="article-card">
        <h3><?php echo htmlspecialchars($a['titre']); ?></h3> <p><em>Publié le : <?php echo $a['date_publication']; ?> | Catégorie : <?php echo $a['cat']; ?></em></p>
        <p><?php echo htmlspecialchars($a['description_courte']); ?></p>
        <a href="article_detail.php?id=<?php echo $a['id']; ?>">Lire la suite</a>
    </div>
<?php endforeach; ?>

<div class="pagination">
    <a href="index.php?p=<?php echo max(1, $page-1); ?>" class="btn">Précédent</a>
    <a href="index.php?p=<?php echo $page+1; ?>" class="btn">Suivant</a>
</div>

<?php include 'footer.php'; ?>