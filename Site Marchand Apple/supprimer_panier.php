<?php
// On récupère la session
$tmpPath = __DIR__ . '/tmp';
if (is_dir($tmpPath)) {
    session_save_path($tmpPath);
}
session_start();

// On vérifie qu'un ID de produit a bien été envoyé dans l'URL
if (isset($_GET['id'])) {
    $numProduit = (int) $_GET['id'];
    
    // Si ce produit existe dans le panier, on le supprime
    if (isset($_SESSION['panier'][$numProduit])) {
        unset($_SESSION['panier'][$numProduit]);
    }
}

// On redirige l'utilisateur vers la page du panier
header("Location: index.php?page=panier");
exit();
?>