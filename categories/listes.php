<?php 

include '../config/auth_check.php';
require_once '../config/db.php';
include '../entete.php';
include '../menu.php';
?>

<div class="container">
    <h2>Gestion des Catégories</h2>
    <a href="ajouter.php" class="btn">Ajouter une catégorie</a>
    <table border="1" style="width:100%; margin-top:20px; border-collapse: collapse;">
        <tr >
            <th>ID</th><th>Libellé</th><th>Actions</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT * FROM categories");
        while($row = $stmt->fetch()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>" . htmlspecialchars($row['libelle']) . "</td>
                    <td><a href='modifier.php?id={$row['id']}'>Modifier</a></td>
                  </tr>";
        }
        ?>
    </table>
</div>
<?php include '../footer.php'; ?>