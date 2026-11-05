<?php
// On démarre la session simplement
$tmpPath = __DIR__ . '/tmp';
if (is_dir($tmpPath)) {
    session_save_path($tmpPath);
}
session_start();

require_once("info_bdd.php");

if (!isset($_POST['numProduit'])) {
    header("location:index.php?page=produits");
    exit();
}

$numProduit = (int) $_POST['numProduit'];

$stmt = $pdo->prepare("
    SELECT numProduit, nomProduit, prixUnitaire 
    FROM produits 
    WHERE numProduit = :id
");
$stmt->execute([':id' => $numProduit]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    header("location:index.php?page=produits");
    exit();
}

// Initialiser le panier si besoin
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Ajouter ou incrémenter la quantité
if (isset($_SESSION['panier'][$numProduit])) {
    $_SESSION['panier'][$numProduit]['quantite']++;
} else {
    $_SESSION['panier'][$numProduit] = [
        'nom'      => $produit['nomProduit'],
        'prix'     => $produit['prixUnitaire'],
        'quantite' => 1,
    ];
}

header("location:index.php?page=panier");
exit();
?>