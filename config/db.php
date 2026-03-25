<?php
define('URL_BASE', '/projet_actualite/');
// On définit les paramètres de connexion
$host = 'localhost';
$dbname = 'projet_actualite';
$user = 'root';
$pass = ''; // Vide par défaut sur XAMPP Windows

try {
    // Création de l'objet PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    
    // On active la gestion des erreurs pour le développement
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si ça échoue, on arrête tout
    die("Erreur de connexion : " . $e->getMessage());
}
?>