<?php
// On définit le chemin de base dynamiquement si ce n'est pas déjà fait
// Si on est dans un sous-dossier, on remonte d'un cran, sinon on reste à la racine
$url_base = (basename(dirname($_SERVER['PHP_SELF'])) == 'articles' || basename(dirname($_SERVER['PHP_SELF'])) == 'utilisateurs' || basename(dirname($_SERVER['PHP_SELF'])) == 'categories') ? '../' : './';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ESP NEWS - L'actualité en continu</title>
    <link rel="stylesheet" href="<?php echo $url_base; ?>css/style.css">
</head>
<body>
    <div style="background: var(--color-accent); color: white; padding: 8px 0; font-weight: bold;">
        <div class="container">
            <marquee>Bienvenue sur le nouveau portail ESP NEWS. Toute l'actualité de l'école en direct.</marquee>
        </div>
    </div>