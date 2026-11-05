<?php

// Chargez votre variable de hashage en début de fichier.
$hashKey = getenv('hashKey');

/**
 * Créez et complétez la fonction qui prend le mot de passe utilisateur en paramètre et retourne son hash.
 */
function hasher_mdp(string $mdp): string {
    global $hashKey; // Permet d'accéder à la variable définie en dehors de la fonction
    
    // Utilisation de l'algorithme sha256 combiné à votre clé d'environnement
    $hash_du_mdp = hash_hmac('sha256', $mdp, $hashKey);
    
    return $hash_du_mdp;
}
