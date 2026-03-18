<?php
session_start();
session_unset(); // On vide les variables
session_destroy(); // On détruit la session
header('Location: connexion.php');
exit();
?>