<?php
require_once 'gestion_secrets.php';
session_start();
require_once 'info_bdd.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=connexionclient");
    exit();
}

// Récupération et normalisation des données
$identifiant = trim($_POST['email'] ?? ''); 
$mdpParam = $_POST['password'] ?? '';

// Vérification pour voir si les champs sont vides
if (empty($identifiant) || empty($mdpParam)) {
    header("Location: index.php?page=connexionclient&erreur=" . urlencode("Veuillez remplir tous les champs."));
    exit();
}

// Vérification du format et de la longueur de l'identifiant
$identifiantSansTirets = str_replace(['-', '_'], '', $identifiant);
if (strlen($identifiant) < 3 || strlen($identifiant) > 50 || !ctype_alnum($identifiantSansTirets)) {
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

        // Journalisation du succès (uniquement l'ID et le pseudo, aucune donnée sensible)
        error_log("[INFO] [connexionclient_exe.php] : Connexion réussie pour le client ID " . $client['idClient'] . " (pseudo : " . $client['pseudo'] . ").");
        
        header("Location: index.php?page=accueil");
        exit();
    } else {
        // Message pour indiquer si le pseudo existe ou non
        header("Location: index.php?page=connexionclient&erreur=" . urlencode("Pseudo ou mot de passe incorrect."));
        exit();
    }

} catch (PDOException $e) {
    error_log("Erreur SQL connexionclient : " . $e->getMessage());
    header("Location: index.php?page=connexionclient&erreur=" . urlencode("Une erreur technique est survenue."));
    exit();
}

require_once 'journal.php';

// Succès
journaliser('INFO', 'CONNEXION_ADMIN_OK', 'login=' . nettoyer_log($user['login']));

// Échec d'identifiants
journaliser('WARNING', 'CONNEXION_ADMIN_ECHEC', 'login=' . nettoyer_log($login));

// Erreur BDD (dans le catch)
journaliser('ERROR', 'BDD_ERREUR', 'Erreur SQL lors de la connexion admin');
?>