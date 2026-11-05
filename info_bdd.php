<?php
// Récupération sécurisée des variables d'environnement
$host     = getenv('DB_HOST');
$dbname   = getenv('DB_NAME');
$user     = getenv('DB_USER');
$password = getenv('DB_PASSWORD');// À mettre dans votre fichier .env plutôt qu'ici !
$charset  = getenv('DB_CHARSET');

try {
    // Connexion via PDO en utilisant les variables récupérées
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=$charset",
        $user,
        $password
    );
    // Activation des erreurs SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}
?>
