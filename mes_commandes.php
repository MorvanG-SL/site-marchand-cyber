<?php
require_once("info_bdd.php");
// La session est déjà démarrée dans index.php

// On vérifie si l'idClient existe en session
if (!isset($_SESSION['idClient'])) {
    echo "
    <div class='container mt-5'>
        <div class='alert alert-warning'>
            <i class='bi bi-exclamation-triangle'></i> 
            Veuillez vous <a href='index.php?page=connexionclient'>connecter</a> pour voir vos commandes.
        </div>
    </div>";
    return; // On arrête l'affichage du reste de la page
}

$idClient = $_SESSION['idClient'];
?>

<div class="container mt-5 text-white">
    <h2 class="mb-4"><i class="bi bi-bag-check-fill text-primary"></i> Mon Historique de Commandes</h2>

    <?php
    try {
        /**
         * On récupère 'libelleStatut' pour ne plus voir l'id du produit
         */
        $stmt = $pdo->prepare("
            SELECT c.noCommande, c.dateCommande, s.libelleStatut, s.idStatut
            FROM commande c
            JOIN statut s ON c.idStatut = s.idStatut
            WHERE c.idClient = :idClient
            ORDER BY c.dateCommande DESC
        ");
        $stmt->execute(['idClient' => $idClient]);
        $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($commandes) > 0) :
    ?>
            <div class="table-responsive shadow">
                <table class="table table-dark table-hover border-secondary">
                    <thead class="table-light text-dark">
                        <tr>
                            <th>N° Commande</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commandes as $cmd) : ?>
                            <tr class="align-middle">
                                <td><strong>#<?= $cmd['noCommande'] ?></strong></td>
                                <td><?= date('d/m/Y', strtotime($cmd['dateCommande'])) ?></td>
                                <td>
                                    <!-- Badge dynamique selon le statut -->
                                    <span class="badge <?= ($cmd['idStatut'] == 2) ? 'bg-success' : 'bg-warning text-dark' ?>">
                                        <i class="bi <?= ($cmd['idStatut'] == 2) ? 'bi-check-circle' : 'bi-clock' ?> me-1"></i>
                                        <?= htmlspecialchars($cmd['libelleStatut']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="alert alert-info bg-dark text-white border-secondary">
                Vous n'avez pas encore passé de commande sur BTS Store.
            </div>
        <?php endif;

    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erreur SQL : " . $e->getMessage() . "</div>";
    }
    ?>
</div>