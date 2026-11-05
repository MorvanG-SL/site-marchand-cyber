<?php
// On démarre la session en tout premier !
session_start();

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 'accueil';
}

$pagesAutorisees = ["accueil", "admin", "connexion", "contact", "produits", "inscription", "connexionclient", "detailproduit", "choix_client", "panier", "mes_commandes"];
if (!in_array($page, $pagesAutorisees)) {
    $page = "accueil";
}

include './header.php';
include $page . '.php';
include './footer.php';
?>