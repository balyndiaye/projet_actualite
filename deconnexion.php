<?php
session_start();
session_unset(); // On vide les variables de session
session_destroy(); // On détruit la session
header('Location: connexion.php'); // Retour à la page de connexion
exit();
?>