<?php
// session déjà démarrée dans header.php
$panier = $_SESSION['panier'] ?? [];
$total = 0;
?>
<div class="container mt-5">
    <h1 class="mb-4 text-white"> Mon panier</h1>
    <?php if (empty($panier)) : ?>
        <div class="alert alert-secondary text-center">
            Ton panier est vide
        </div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-dark table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($panier as $numProduit => $produit) :
                    $sousTotal = $produit['prix'] * $produit['quantite'];
                    $total += $sousTotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($produit['nom']) ?></td>
                        <td><?= number_format($produit['prix'], 2) ?> €</td>
                        <td><?= $produit['quantite'] ?></td>
                        <td><?= number_format($sousTotal, 2) ?> €</td>
                        <td>
                            <a href="supprimer_panier.php?id=<?= (int) $numProduit ?>" 
                               class="btn btn-sm btn-outline-danger">
                                🗑 Retirer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-end mt-3">
            <h3 class="text-white">Total : <span class="text-success"><?= number_format($total, 2) ?> €</span></h3>
        </div>
    <?php endif; ?>
    <div class="mt-4 d-flex justify-content-between">
    <a href="./index.php?page=produits" class="btn btn-outline-light">
        ← Continuer mes achats
    </a>
    
    <?php if (!empty($panier)) : ?>
        <form action="valider_commande.php" method="POST" class="m-0">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-cart-check-fill"></i> Commander
            </button>
        </form>
    <?php endif; ?>
</div>