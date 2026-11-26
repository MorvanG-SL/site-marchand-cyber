<?php
session_start();
require_once("info_bdd.php");
require_once 'journal.php';
require_once 'gestion_secrets.php';

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
            session_regenerate_id(true);

            // CRÉATION DE LA SESSION : On stocke le login et le rôle
            $_SESSION['login'] = $user['login'];
            $_SESSION['role']  = $user['role']; 

            // Journalisation du succès AVANT exit()
            journaliser('INFO', 'CONNEXION_ADMIN_OK', 'login=' . nettoyer_log($user['login']));

            // Redirection vers l'espace admin
            header("Location: index.php?page=admin");
            exit();
        } else {
            // Journalisation de l'échec AVANT exit()
            journaliser('WARNING', 'CONNEXION_ADMIN_ECHEC', 'login=' . nettoyer_log($login));

            // Identifiants faux
            header("Location: index.php?page=connexion&erreur=" . urlencode("Identifiants ou mot de passe incorrects"));
            exit();
        }
    } catch (PDOException $e) {
        // Journalisation de l'erreur SQL AVANT exit()
        journaliser('ERROR', 'BDD_ERREUR', 'Erreur SQL lors de la connexion admin');

        header("Location: index.php?page=connexion&erreur=" . urlencode("Erreur technique"));
        exit();
    }
} else {
    journaliser('WARNING', 'CONNEXION_ADMIN_ECHEC', 'Champs vides lors de la tentative de connexion');

    header("Location: index.php?page=connexion&erreur=" . urlencode("Veuillez remplir tous les champs"));
    exit();
}
?>