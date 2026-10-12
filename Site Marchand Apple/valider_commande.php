<?php
session_start();
require_once("info_bdd.php");

// 1. Sécurité : Vérification de la connexion
if (!isset($_SESSION['idClient'])) {
    header("Location: index.php?page=connexionclient");
    exit();
}

$idClient = $_SESSION['idClient'];
$panier = $_SESSION['panier'] ?? [];

if (empty($panier)) {
    header("Location: index.php?page=panier");
    exit();
}

try {
    $pdo->beginTransaction();

    // 2. Insertion de la commande
    // CHANGEMENT : On met l'idStatut à 1 pour "En Attente"
    $stmtCmd = $pdo->prepare("INSERT INTO commande (dateCommande, idClient, idStatut) VALUES (NOW(), :idC, 1)");
    $stmtCmd->execute(['idC' => $idClient]);
    $noCommande = $pdo->lastInsertId();

    // 3. Insertion dans 'contenir'
    foreach ($panier as $idProduit => $details) {
        // On utilise 'numProduit' comme vu sur ta structure de table
        $stmtArt = $pdo->prepare("INSERT INTO contenir (noCommande, numProduit, quantite) VALUES (:no, :idP, :qte)");
        $stmtArt->execute([
            'no'  => $noCommande,
            'idP' => $idProduit,
            'qte' => $details['quantite']
        ]);
    }

    $pdo->commit();
    $_SESSION['panier'] = []; 

    header("Location: index.php?page=mes_commandes&info=Commande envoyée. En attente de confirmation.");
    exit();

} catch (Exception $e) {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    die("Erreur lors de la validation : " . $e->getMessage());
}