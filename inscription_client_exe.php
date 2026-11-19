<?php
require_once 'gestion_secrets.php';

session_save_path(__DIR__ . '/tmp');
session_start();

// Récupération des données du formulaire (on ne met pas htmlspecialchars sur le mot de passe pour ne pas altérer les caractères spéciaux avant le hachage)
$pseudoSaisi = trim($_POST['pseudo'] ?? '');
$mdpSaisi = $_POST['mdp'] ?? '';
$mdp2Saisi = $_POST['mdp2'] ?? '';
$nomSaisi = trim($_POST['nom'] ?? '');
$prenomSaisi = trim($_POST['prenom'] ?? '');
$mailSaisi = trim($_POST['mail'] ?? '');
$telephoneSaisi = trim($_POST['telephone'] ?? '');

// Vérification de la correspondance des deux mots de passe
if ($mdpSaisi !== $mdp2Saisi) {
    header("Location: index.php?page=inscription&erreur=Les 2 mots de passe doivent être identiques");
    exit;
}

// Vérification des champs obligatoires
if (empty($pseudoSaisi) || empty($mdpSaisi) || empty($nomSaisi) || empty($prenomSaisi)) {
    header("Location: index.php?page=inscription&erreur=Veuillez remplir tous les champs obligatoires");
    exit;
}

// Inclusion de info_bdd.php
require_once __DIR__ . '/info_bdd.php';

$nomChiffre = chiffrer_donnee($nomSaisi, $cle_chiffrement);

$prenomChiffre = chiffrer_donnee($prenomSaisi, $cle_chiffrement);

$mailChiffre = chiffrer_donnee($mailSaisi, $cle_chiffrement);

$telephoneChiffre = chiffrer_donnee($telephoneSaisi, $cle_chiffrement);
// Hachage sécurisé du mot de passe avant l'insertion en base de données
$mdpHashe = hasher_mdp($mdpSaisi);

$resultat = false;
try {
    $requeteInsertion = "INSERT INTO client (nom, prenom, pseudo, mdp, mail, telephone)
    VALUES (:nom, :prenom, :pseudo, :mdp, :mail, :telephone)";
    
    $statement = $pdo->prepare($requeteInsertion);
    $resultat = $statement->execute([
        'nom' => $nomChiffre,
        'prenom' => $prenomChiffre,
        'pseudo' => $pseudoSaisi,
        'mdp' => $mdpHashe,
        'mail' => $mailChiffre,
        'telephone' => $telephoneChiffre
    ]);
} catch (PDOException $monException) {
    header("Location: index.php?page=inscription&erreur=Erreur lors de l'inscription : pseudo déjà utilisé");
    exit;
}

if ($resultat) {
    header("Location: index.php?page=connexionclient&info=Inscription réussie, connectez-vous !");
    exit;
} else {
    header("Location: index.php?page=inscription&erreur=Erreur lors de l'inscription");
    exit;
}
?>