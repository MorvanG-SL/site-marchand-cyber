<?php
session_save_path(__DIR__ . '/tmp');
session_start();

$pseudoSaisi = htmlspecialchars($_POST['pseudo'] ?? '');
$mdpSaisi = htmlspecialchars($_POST['mdp'] ?? '');
$mdp2Saisi = htmlspecialchars($_POST['mdp2'] ?? '');
$nomSaisi = htmlspecialchars($_POST['nom'] ?? '');
$prenomSaisi = htmlspecialchars($_POST['prenom'] ?? '');
$mailSaisi = htmlspecialchars($_POST['mail'] ?? '');
$telephoneSaisi = htmlspecialchars($_POST['telephone'] ?? '');

if ($mdpSaisi !== $mdp2Saisi) {
    header("Location: index.php?page=inscription&&erreur=Les 2 mots de passe doivent être identiques");
    exit;
}

if (empty($pseudoSaisi) || empty($mdpSaisi) || empty($nomSaisi) || empty($prenomSaisi)) {
    header("Location: index.php?page=inscription&&erreur=Veuillez remplir tous les champs obligatoires");
    exit;
}

require 'info_bdd.php';

$resultat = false;
try {
    $requeteInsertion = "INSERT INTO client (nom, prenom, pseudo, mdp, mail, telephone)
    VALUES (:nom, :prenom, :pseudo, :mdp, :mail, :telephone)";
    
    $statement = $pdo->prepare($requeteInsertion);
    $resultat = $statement->execute([
        'nom' => $nomSaisi,
        'prenom' => $prenomSaisi,
        'pseudo' => $pseudoSaisi,
        'mdp' => $mdpSaisi,
        'mail' => $mailSaisi,
        'telephone' => $telephoneSaisi
    ]);
} catch (PDOException $monException) {
    echo "Erreur SQL : " . $monException->getMessage();
}

if ($resultat) {
    header("Location: index.php?page=connexionclient&&info=Inscription réussie, connectez-vous !");
} else {
    header("Location: index.php?page=inscription&&erreur=Erreur lors de l'inscription");
}
?>