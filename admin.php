<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?page=connexion&erreur=Accès refusé");
    exit();
}
require_once("info_bdd.php");
?>

<div class="container mt-4 text-white">
    <div class="text-center">
        <h2 class="mb-4">Espace Administration</h2>
        
        <!-- Affichage des alertes de succès -->
        <?php if (isset($_GET['info'])): ?>
            <div class="alert alert-success alert-dismissible fade show w-75 mx-auto mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($_GET['info']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="alert alert-primary w-50 mx-auto border-0 shadow-sm" style="background-color: rgba(13, 110, 253, 0.2); color: white;">
            <h3>Bienvenue, <?php echo htmlspecialchars($_SESSION['login'] ?? 'Admin'); ?></h3>
        </div>
    </div>

    <!-- SECTION UTILISATEURS -->
    <h3 class="mt-5 mb-3 text-info"><i class="bi bi-people-fill"></i> Liste des utilisateurs (Back-office)</h3>
    <?php
    $reponseUsers = $pdo->query("SELECT login, nom, prenom, role FROM users");
    $users = $reponseUsers->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-dark table-hover border-secondary">
            <thead class="table-secondary text-dark">
                <tr><th>Login</th><th>Nom</th><th>Prénom</th><th>Rôle</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?= htmlspecialchars($user['login']) ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['prenom']) ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($user['role']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- SECTION GESTION DES COMMANDES (FILTRÉE) -->
    <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
        <h3 class="text-info m-0"><i class="bi bi-cart-fill"></i> Commandes à traiter (En attente)</h3>
        <span class="badge bg-danger">Action requise</span>
    </div>

    <?php
    // permet de masquer automatiquement les commandes une fois confirmées.
    $reponseCmd = $pdo->query("
        SELECT c.noCommande, c.dateCommande, cl.nom as clientNom, cl.prenom as clientPrenom, s.idStatut, s.libelleStatut
        FROM commande c
        JOIN client cl ON c.idClient = cl.idClient
        JOIN statut s ON c.idStatut = s.idStatut
        WHERE s.idStatut = 1
        ORDER BY c.dateCommande ASC
    ");
    $commandes = $reponseCmd->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-dark table-hover border-secondary mt-2">
            <thead class="table-primary text-dark">
                <tr>
                    <th class="text-center">N° Commande</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($commandes) > 0) : ?>
                    <?php foreach ($commandes as $cmd) : ?>
                        <tr class="align-middle">
                            <td class="text-center"><strong>#<?= $cmd['noCommande'] ?></strong></td>
                            <td><?= htmlspecialchars($cmd['clientNom'] . " " . $cmd['clientPrenom']) ?></td>
                            <td><?= date('d/m/Y', strtotime($cmd['dateCommande'])) ?></td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-clock me-1"></i>
                                    <?= htmlspecialchars($cmd['libelleStatut']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <!-- Formulaire pour passer le statut à 2 -->
                                <form action="modifier_statut_exe.php" method="POST" class="m-0">
                                    <input type="hidden" name="noCommande" value="<?= $cmd['noCommande'] ?>">
                                    <input type="hidden" name="nouveauStatut" value="2">
                                    <button type="submit" class="btn btn-sm btn-success px-3 fw-bold shadow">
                                        <i class="bi bi-check-lg"></i> Confirmer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-secondary">
                            <i class="bi bi-check-all fs-2 d-block mb-2"></i>
                            Aucune commande en attente. Beau travail !
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>