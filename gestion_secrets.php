<?php
require_once __DIR__ . '/vendor/autoload.php';

// Initialisation sécurisée de Dotenv (UnsafeImmutable permet à getenv() de lire les variables)
$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

// Chargez votre variable de hashage en début de fichier.
$hashKey = getenv('hashKey');
$cle_chiffrement = getenv('encryption_key');

// Fonction qui prend le mot de passe utilisateur en paramètre et retourne son hash.
function hasher_mdp(string $mdp): string {
    global $hashKey;

    // Utilisation de l'algorithme sha256
    $hash_du_mdp = hash_hmac('sha256', $mdp, $hashKey);

    return $hash_du_mdp;
}
// 2. Fonction qui vérifie si un mot de passe clair correspond à un hash enregistré
function verifier_mdp(string $mdp, string $hash): bool {
    // On re-hache le mot de passe saisi et on compare de manière sécurisée
    $hash_calcule = hasher_mdp($mdp);
    return hash_equals($hash, $hash_calcule);
}
?>