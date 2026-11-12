<?php
// Inclusion de l'autoloader de Composer pour charger la bibliothèque Dotenv
require_once __DIR__ . '/vendor/autoload.php';

// Initialisation sécurisée de Dotenv (UnsafeImmutable permet à getenv() de lire les variables)
$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

// Chargez votre variable de hashage en début de fichier.
$hashKey = getenv('hashKey');
$cle_chiffrement = getenv('encryption_key');

// Fonction qui prend le mot de passe utilisateur en paramètre et retourne son hash.
function hasher_mdp(string $mdp): string {
    global $hashKey; // Permet d'accéder à la variable définie en dehors de la fonction

    // Utilisation de l'algorithme sha256 combiné à la clé d'environnement
    $hash_du_mdp = hash_hmac('sha256', $mdp, $hashKey);

    return $hash_du_mdp;
}
?>