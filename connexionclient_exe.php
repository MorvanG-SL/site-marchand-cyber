<?php
session_start();
require_once 'info_bdd.php';

// 1. On récupère ce que l'utilisateur a tapé dans le champ Identifiant
$identifiant = trim($_POST['email'] ?? ''); 
$mdpParam = $_POST['password'] ?? '';

// 2. Sécurité : on ne lance pas la requête si c'est vide
if (empty($identifiant) || empty($mdpParam)) {
    header("Location: index.php?page=connexionclient&erreur=Champs vides");
    exit();
}

try {
    /**
     * On utilise 'pseudo' dans le WHERE car 'email' est inconnu en BDD.
     */
    $sql = "SELECT idClient, nom, prenom, pseudo FROM client 
            WHERE pseudo = :identifiant AND mdp = :mdpRequete";
    
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'identifiant' => $identifiant,
        'mdpRequete'  => $mdpParam
    ]);
    
    $client = $statement->fetch(PDO::FETCH_ASSOC);

    if ($client) {
        // On stocke le nom complet pour l'affichage et l'ID pour l'historique
        $_SESSION['user'] = $client['prenom'] . " " . $client['nom'];
        $_SESSION['idClient'] = $client['idClient']; 
        
        header("Location: index.php?page=accueil");
        exit();
    } else {
        //erreur : Le pseudo ou le mdp ne correspond pas
        header("Location: index.php?page=connexionclient&erreur=Pseudo ou mot de passe incorrect");
        exit();
    }

} catch (PDOException $e) {
    // En cas d'autre erreur (ex: faute de frappe dans le nom de la table)
    die("ERREUR SQL : " . $e->getMessage());
}
?>