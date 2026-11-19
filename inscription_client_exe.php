<?php
require_once 'gestion_secrets.php';

session_save_path(__DIR__ . '/tmp');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=inscription");
    exit;
}

$pseudoSaisi = trim($_POST['pseudo'] ?? '');
$mdpSaisi = $_POST['mdp'] ?? '';
$mdp2Saisi = $_POST['mdp2'] ?? '';
$nomSaisi = trim($_POST['nom'] ?? '');
$prenomSaisi = trim($_POST['prenom'] ?? '');
$mailSaisi = trim($_POST['mail'] ?? '');
// Suppression des espaces, tirets et points dans le numéro de téléphone
$telephoneSaisi = preg_replace('/[ .-]/', '', trim($_POST['telephone'] ?? ''));

// Vérification des champs obligatoires
if (empty($pseudoSaisi) || empty($mdpSaisi) || empty($nomSaisi) || empty($prenomSaisi)) {
    header("Location: index.php?page=inscription&erreur=" . urlencode("Veuillez remplir tous les champs obligatoires."));
    exit;
}

// Vérification de la longueur et du format des champs texte
if (mb_strlen($pseudoSaisi) < 3 || mb_strlen($pseudoSaisi) > 50 || !preg_match('/^[a-zA-Z0-9_-]+$/', $pseudoSaisi)) {
    header("Location: index.php?page=inscription&erreur=" . urlencode("Le pseudo doit contenir entre 3 et 50 caractères alphanumériques."));
    exit;
}

if (mb_strlen($nomSaisi) > 100 || mb_strlen($prenomSaisi) > 100) {
    header("Location: index.php?page=inscription&erreur=" . urlencode("Le nom ou le prénom ne doit pas dépasser 100 caractères."));
    exit;
}

// Vérification du mail et du téléphone (s'ils sont renseignés)
if (!empty($mailSaisi) && !filter_var($mailSaisi, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.php?page=inscription&erreur=" . urlencode("Le format de l'adresse e-mail est invalide."));
    exit;
}

if (!empty($telephoneSaisi) && !preg_match('/^0[1-9][0-9]{8}$/', $telephoneSaisi)) {
    header("Location: index.php?page=inscription&erreur=" . urlencode("Le numéro de téléphone doit contenir 10 chiffres valides."));
    exit;
}

// Vérification de la correspondance et de la robustesse du mot de passe
if ($mdpSaisi !== $mdp2Saisi) {
    header("Location: index.php?page=inscription&erreur=" . urlencode("Les 2 mots de passe doivent être identiques."));
    exit;
}

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/', $mdpSaisi)) {
    header("Location: index.php?page=inscription&erreur=" . urlencode("Le mot de passe doit contenir au moins 12 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial."));
    exit;
}

// Connexion à la BDD et vérification de l'unicité du pseudo avant l'insertion
require_once __DIR__ . '/info_bdd.php';

$reqVerif = $pdo->prepare("SELECT COUNT(*) FROM client WHERE pseudo = :pseudo");
$reqVerif->execute(['pseudo' => $pseudoSaisi]);
if ($reqVerif->fetchColumn() > 0) {
    header("Location: index.php?page=inscription&erreur=" . urlencode("Ce pseudo est déjà utilisé."));
    exit;
}

// Chiffrement des données personnelles et hachage sécurisé du mot de passe
$nomChiffre = chiffrer_donnee($nomSaisi, $cle_chiffrement);
$prenomChiffre = chiffrer_donnee($prenomSaisi, $cle_chiffrement);
$mdpHashe = hasher_mdp($mdpSaisi);

// Insertion sécurisée en base de données avec gestion des échecs
try {
    $requeteInsertion = "INSERT INTO client (nom, prenom, pseudo, mdp, mail, telephone)
                         VALUES (:nom, :prenom, :pseudo, :mdp, :mail, :telephone)";
    
    $statement = $pdo->prepare($requeteInsertion);
    $resultat = $statement->execute([
        'nom' => $nomChiffre,
        'prenom' => $prenomChiffre,
        'pseudo' => $pseudoSaisi,
        'mdp' => $mdpHashe,
        'mail' => $mailSaisi,
        'telephone' => $telephoneSaisi
    ]);

    if ($resultat) {
        header("Location: index.php?page=connexionclient&info=" . urlencode("Inscription réussie, connectez-vous !"));
        exit;
    } else {
        header("Location: index.php?page=inscription&erreur=" . urlencode("Erreur lors de l'inscription."));
        exit;
    }
} catch (PDOException $monException) {
    error_log("Erreur SQL inscription : " . $monException->getMessage());
    header("Location: index.php?page=inscription&erreur=" . urlencode("Une erreur technique est survenue lors de l'inscription."));
    exit;
}
?>