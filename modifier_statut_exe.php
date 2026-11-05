<?php
// On démarre la session pour être sûr que le lien avec le header reste actif
session_start();
require_once("info_bdd.php");

/**
 * noCommande : ID de la commande à modifier
 * nouveauStatut : Valeur '2' pour "Confirmer"
 */
$idCmd = $_POST['noCommande'] ?? null;
$statut = $_POST['nouveauStatut'] ?? null;

if ($idCmd && $statut) {
    try {
        // Mise à jour de la table commande (noms de colonnes selon Adminer)
        $stmt = $pdo->prepare("UPDATE commande SET idStatut = :statut WHERE noCommande = :id");
        
        $succes = $stmt->execute([
            'statut' => $statut,
            'id'     => $idCmd
        ]);

        if ($succes) {
            // Retour vers la page admin avec message de succès
            header("Location: index.php?page=admin&info=La commande n°$idCmd est désormais confirmée !");
            exit();
        } else {
            header("Location: index.php?page=admin&erreur=Échec de la mise à jour en base de données.");
            exit();
        }

    } catch (PDOException $e) {
        // En cas d'erreur SQL, on affiche le message précis pour débugger
        die("Erreur SQL : " . $e->getMessage());
    }
} else {
    // Si on accède au fichier sans les données du formulaire
    header("Location: index.php?page=admin&erreur=Données manquantes pour la modification.");
    exit();
}