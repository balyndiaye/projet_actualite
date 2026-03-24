<?php
// 1. DÉMARRAGE DE LA SESSION ET SÉCURITÉ
session_start();
require_once '../config/db.php';

// On vérifie si l'utilisateur est admin (on utilise 'admin' comme dans ta base)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php?err=acces_refuse');
    exit();
}

// 2. TRAITEMENT DU FORMULAIRE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    // Hachage du mot de passe pour la sécurité
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; 

    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (login, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$login, $password, $role]);
        
        // Redirection vers la liste
        header("Location: liste.php?msg=user_ajoute");
        exit();
    } catch (PDOException $e) {
        $erreur = "Erreur : Ce nom d'utilisateur existe déjà.";
    }
}

include '../entete.php';
include '../menu.php';
?>

<main class="container" style="margin-top: 40px; max-width: 500px;">
    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        
        <h2 style="color: #2c3e50; margin-bottom: 25px; border-bottom: 2px solid #3922e6; padding-bottom: 10px;">
             Nouvel Utilisateur
        </h2>

        <?php if(isset($erreur)): ?>
            <p style="color: #721c24; background: #f8d7da; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <?= $erreur ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Identifiant (Login) :</label>
                <input type="text" name="login" required placeholder="Ex: editeur_dakar" 
                       style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Mot de passe :</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" name="password" id="password_field" required placeholder="Entrez le mot de passe" 
                           style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; padding-right: 45px;">
                    
                    <span id="btn_toggle" style="position: absolute; right: 12px; cursor: pointer; color: #7f8c8d; display: flex; align-items: center; user-select: none;">
                        <svg id="eye_svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </span>
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Rôle du compte :</label>
                <select name="role" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; background: white; cursor: pointer;">
                    <option value="editeur">Éditeur (Articles uniquement)</option>
                    <option value="admin">Administrateur (Contrôle total)</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 2; background: #27ae60; color: white; border: none; padding: 14px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 16px;">
                    Enregistrer
                </button>
                <a href="liste.php" style="flex: 1; text-align: center; background: #95a5a6; color: white; text-decoration: none; padding: 14px; border-radius: 6px; font-weight: bold;">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</main>

<script>
    const btnToggle = document.getElementById('btn_toggle');
    const inputPass = document.getElementById('password_field');
    const eyeSvg = document.getElementById('eye_svg');

    btnToggle.addEventListener('click', function () {
        // On bascule entre 'password' et 'text'
        if (inputPass.type === 'password') {
            inputPass.type = 'text';
            // Icône de l'œil barré (mode "Visible")
            eyeSvg.innerHTML = '<line x1="1" y1="1" x2="23" y2="23"></line><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>';
        } else {
            inputPass.type = 'password';
            // Icône de l'œil normal (mode "Caché")
            eyeSvg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    });
</script>

<?php include '../footer.php'; ?>