<?php

$nom_base='site-marchand';
$user_bdd='adminer';
$mdp='MathAlgoBousiller0.20';

$pdo = new PDO(
"mysql:host=localhost;dbname=$nom_base;charset=utf8",
$user_bdd,
$mdp
);
?>