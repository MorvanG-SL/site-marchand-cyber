<?php
// 1. On récupère la session en cours
session_start();

// 2. On supprime l'utilisateur de la session
unset($_SESSION['user']);

// 3. On détruit la session
session_destroy();

// 4. On redirige vers l'accueil ou la page de connexion
header("Location: index.php?page=connexionclient&info=Déconnexion réussie");
exit();
?>