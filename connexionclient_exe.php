<?php
require_once 'gestion_secrets.php';
require_once 'journal.php';
session_start();
require_once 'info_bdd.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    journaliser('WARNING', 'ACCES_INVALIDE', 'Tentative accès direct sans POST sur connexionclient_exe.php');
    header("Location: index.php?page=connexionclient");
    exit();
}

// Récupération et normalisation des données
$identifiant = trim($_POST['email'] ?? ''); 
$mdpParam = $_POST['password'] ?? '';

// Vérification pour voir si les champs sont vides
if (empty($identifiant) || empty($mdpParam)) {
    journaliser('WARNING', 'CONNEXION_CLIENT_ECHEC', 'Champs vides lors de la tentative de connexion');
    header("Location: index.php?page=connexionclient&erreur=" . urlencode("Veuillez remplir tous les champs."));
    exit();
}

// Vérification du format et de la longueur de l'identifiant
$identifiantSansTirets = str_replace(['-', '_'], '', $identifiant);
if (strlen($identifiant) < 3 || strlen($identifiant) > 50 || !ctype_alnum($identifiantSansTirets)) {
    journaliser('WARNING', 'CONNEXION_CLIENT_ECHEC', 'Format identifiant invalide : pseudo=' . nettoyer_log($identifiant));
    header("Location: index.php?page=connexionclient&erreur=" . urlencode("Pseudo ou mot de passe incorrect."));
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
        session_regenerate_id(true);

        // Déchiffrement du prénom et du nom pour l'affichage dans la session
        $prenomClair = dechiffrer_donnee($client['prenom'], $cle_chiffrement);
        $nomClair = dechiffrer_donnee($client['nom'], $cle_chiffrement);

        $_SESSION['user'] = $prenomClair . " " . $nomClair;
        $_SESSION['idClient'] = $client['idClient']; 

        // Journalisation du succès (uniquement l'ID et le pseudo nettoyé, aucune donnée sensible)
        journaliser('INFO', 'CONNEXION_CLIENT_OK', 'idClient=' . $client['idClient'] . ' pseudo=' . nettoyer_log($client['pseudo']));
        
        header("Location: index.php?page=accueil");
        exit();
    } else {
        // Journalisation de l'échec d'identifiants
        journaliser('WARNING', 'CONNEXION_CLIENT_ECHEC', 'Identifiants incorrects : pseudo=' . nettoyer_log($identifiant));

        header("Location: index.php?page=connexionclient&erreur=" . urlencode("Pseudo ou mot de passe incorrect."));
        exit();
    }

} catch (PDOException $e) {
    // Journalisation de l'erreur SQL
    journaliser('ERROR', 'BDD_ERREUR', 'Erreur SQL lors de la connexion client');

    header("Location: index.php?page=connexionclient&erreur=" . urlencode("Une erreur technique est survenue."));
    exit();
}
?>