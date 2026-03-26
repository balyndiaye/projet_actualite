<?php
// Démarrage de la session
session_start();

// Affichage des erreurs (Utile pour le développement)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// onnexion à la base de données
require_once 'config/db.php';

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nettoyage des saisies pour éviter les espaces accidentels 
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    // Recherche de l'utilisateur par son login
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user) {
        // VÉRIFICATION DU MOT DE PASSE HACHÉ
        if (password_verify($password, $user['password'])) {

            // Connexion réussie : on remplit la session
            $_SESSION['id_user'] = $user['id'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['role'] = $user['role'];

            // Redirection vers la page d'accueil
            header('Location: index.php');
            exit();
        } else {
            // Le hash ne correspond pas au mot de passe saisi
            $erreur = " Mot de passe incorrect.";
        }
    } else {
        // Aucun utilisateur trouvé avec ce login
        $erreur = "L'utilisateur '$login' n'existe pas.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ESP NEWS</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            border-bottom: 4px solid #3498db;
            padding-bottom: 10px;
            display: inline-block;
            width: 100%;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #34495e;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #2980b9;
        }

        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h2>Connexion</h2>

        <?php if ($erreur): ?>
            <div class="error-msg"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validerFormulaire()">
            <div class="form-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="login" id="login" placeholder="Ex: admin" required autofocus>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
            </div>

            <button type="submit">Se connecter</button>
        </form>
    </div>

    <script>
        [cite_start] // Validation côté client obligatoire
        function validerFormulaire() {
            const login = document.getElementById('login').value.trim();
            const pass = document.getElementById('password').value;

            if (login === "" || pass === "") {
                alert("Tous les champs sont obligatoires.");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>