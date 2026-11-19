<?php
require_once __DIR__ . '/vendor/autoload.php';

// Initialisation sécurisée de Dotenv (UnsafeImmutable permet à getenv() de lire les variables)
$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

// Chargez votre variable de hashage en début de fichier.
$hashKey = getenv('hashKey');
$cle_chiffrement = getenv('encryption_key');
$cle_dechiffrement = getenv('encryption_key'); // on utilise la même clé pour le chiffrement et le déchiffrement

// Fonction qui prend le mot de passe utilisateur en paramètre et retourne son hash.
function hasher_mdp(string $mdp): string {
    global $hashKey;

    // Utilisation de l'algorithme sha256
    $hash_du_mdp = hash_hmac('sha256', $mdp, $hashKey);

    return $hash_du_mdp;
}

// Fonction qui vérifie si un mot de passe clair correspond à un hash enregistré
function chiffrer_donnee (string $donnee, string $cle_chiffrement): string {
    $methode = 'AES-128-CBC';

    $longueur_iv = openssl_cipher_iv_length($methode);
    $iv = random_bytes($longueur_iv);

    $donnee_chiffree = openssl_encrypt($donnee, $methode, $cle_chiffrement, OPENSSL_RAW_DATA, $iv);


    return base64_encode($iv . $donnee_chiffree);
}

function dechiffrer_donnee (string $donnee_chiffree, string $cle_dechiffrement): string {
    $methode = 'AES-128-CBC';
    $donnees_brutes = base64_decode($donnee_chiffree);

    $longueur_iv = openssl_cipher_iv_length($methode);
    $iv = substr($donnees_brutes, 0, $longueur_iv);
    $texte_chiffre = substr($donnees_brutes, $longueur_iv);

    $donnee_claire = openssl_decrypt($texte_chiffre, $methode, $cle_dechiffrement, OPENSSL_RAW_DATA, $iv);

    return $donnee_claire === false ? '' : $donnee_claire;
}

?>