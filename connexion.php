<?php
session_start(); // On démarre la session en haut de page
require_once 'config/db.php'; // On inclut la connexion $pdo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // On récupère les variables du formulaire (login et password)
    $login = $_POST['login'];
    $password = $_POST['password'];

    // On prépare la requête avec les bons noms de colonnes
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    // Vérification avec password_verify
    if ($user && password_verify($password, $user['password'])) {
        // On stocke les variables en session (conforme à ta liste)
        $_SESSION['id_user'] = $user['id'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['role'] = $user['role']; // visiteur, editeur ou administrateur

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
</head>
<body>
    <form method="POST">
        <h2>Se connecter</h2>
        <?php if (isset($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>
        
        <input type="text" name="login" placeholder="Login" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        
        <button type="submit">Connexion</button>
    </form>
</body>
</html>