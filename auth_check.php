<?php
session_start();

// Si la variable de session 'login' n'existe pas, cela signifie que
// l'utilisateur n'est pas passé par la page de connexion.
if (!isset($_SESSION['login'])) {
    // On le redirige immédiatement vers la page de connexion
    header('Location: connexion.php');
    exit();
}
?>