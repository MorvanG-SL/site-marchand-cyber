<?php
session_start();
require_once("info_bdd.php");

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

if (!empty($login) && !empty($password)) {
    try {
        // Hachage du mot de passe administrateur avant la requête
        $mdpHashe = hasher_mdp($password);

        // On cherche l'utilisateur dans la table 'users'
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login AND mdp = :password");
        $stmt->execute([
            'login'    => $login, 
            'password' => $mdpHashe
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // CRÉATION DE LA SESSION : On stocke le login et le rôle
            $_SESSION['login'] = $user['login'];
            $_SESSION['role']  = $user['role']; 

            // Redirection vers l'espace admin
            header("Location: index.php?page=admin");
            exit();
        } else {
            // Identifiants faux
            header("Location: index.php?page=connexion&erreur=Identifiants ou mot de passe incorrects");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: index.php?page=connexion&erreur=Erreur technique");
        exit();
    }
} else {
    header("Location: index.php?page=connexion&erreur=Veuillez remplir tous les champs");
    exit();
}
?>