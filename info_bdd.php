<?php

$nom_base='site-marchand';
$user_bdd='adminer';
$mdp='MathAlgoBousiller0.20';

$pdo = new PDO(
"mysql:host=localhost;dbname=$nom_base;charset=utf8",
$user_bdd,
$mdp
);

// 3. Récupération des variables de connexion
$host     = getenv('DB_HOST');
$dbname   = getenv('DB_NAME');
$user     = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$charset  = getenv('DB_CHARSET');
?>