<?php
require_once 'gestion_secrets.php';
session_save_path(__DIR__ . '/tmp');
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

// Vérification du format et de la longueur de l'identifiant (pseudo)
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
?>