<?php
session_start();
require_once 'info_bdd.php';

$identifiant = trim($_POST['email'] ?? ''); 
$mdpParam = $_POST['password'] ?? '';

if (empty($identifiant) || empty($mdpParam)) {
    header("Location: index.php?page=connexionclient&erreur=Champs vides");
    exit();
}

try {
    // Hachage du mot de passe saisi avant comparaison avec la BDD
    $mdpHashe = hasher_mdp($mdpParam);

    $sql = "SELECT idClient, nom, prenom, pseudo FROM client 
            WHERE pseudo = :identifiant AND mdp = :mdpRequete";
    
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'identifiant' => $identifiant,
        'mdpRequete'  => $mdpHashe
    ]);
    
    $client = $statement->fetch(PDO::FETCH_ASSOC);

    if ($client) {
        // On stocke le nom complet pour l'affichage et l'ID pour l'historique
        $_SESSION['user'] = $client['prenom'] . " " . $client['nom'];
        $_SESSION['idClient'] = $client['idClient']; 
        
        header("Location: index.php?page=accueil");
        exit();
    } else {
        // Erreur : Le pseudo ou le mdp ne correspond pas
        header("Location: index.php?page=connexionclient&erreur=Pseudo ou mot de passe incorrect");
        exit();
    }

} catch (PDOException $e) {
    die("ERREUR SQL : " . $e->getMessage());
}
?>