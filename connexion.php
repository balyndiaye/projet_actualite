<?php
session_start(); // On démarre la session en haut de page
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // On prépare la requête pour éviter les injections SQL
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    // Vérification : on utilise password_verify pour la sécurité
    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['id_user'] = $user['id'];
        $_SESSION['login'] = $user['pseudo'];
        // Si tu as une colonne 'role' dans ta table :
        $_SESSION['role'] = $user['role'] ?? 'visiteur'; 

        header('Location: index.php'); // Redirection vers l'accueil
        exit();
    } else {
        $erreur = "Identifiants incorrects !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css"> </head>
<body>
    <form method="POST">
        <h2>Se connecter</h2>
        <?php if (isset($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>
        
        <input type="text" name="login" placeholder="Pseudo" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        
        <button type="submit">Connexion</button>
    </form>
</body>
</html>