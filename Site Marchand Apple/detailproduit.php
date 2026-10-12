<?php
require "./info_bdd.php";
if (isset($_GET["numProduit"])) 
    $numProduit = $_GET["numProduit"];
else 
{
    $numProduit = "";
}
$requeteSQL = "SELECT numProduit, nomProduit, descriptif, stock, prixUnitaire, nomImage,
idCategorie FROM produits WHERE numProduit = $numProduit;";
$reponse = $pdo->query($requeteSQL);
$produit = $reponse->fetch(PDO::FETCH_ASSOC);
?>

<div class="container mt-5 mb-5">

    <!-- Nom du produit -->
    <h1 class="text-white text-center mb-5 fw-bold">
        <?= htmlspecialchars($produit['nomProduit']) ?>
    </h1>

    <!-- Carte principale -->
    <div class="row align-items-center g-5 bg-dark rounded-4 p-4 shadow border border-secondary border-opacity-25">

        <!-- Image -->
        <div class="col-12 col-md-5 text-center">
            <img src="./images/<?= htmlspecialchars($produit['nomImage']) ?>"
                 alt="<?= htmlspecialchars($produit['nomProduit']) ?>"
                 class="img-fluid rounded-3"
                 style="max-height: 380px; object-fit: contain;">
        </div>

        <!-- Infos -->
        <div class="col-12 col-md-7 d-flex flex-column gap-3">

            <!-- Prix -->
            <div>
                <span class="fs-2 fw-bold text-white">
                    <?= number_format($produit['prixUnitaire'], 2) ?> €
                </span>
            </div>

            <!-- Stock -->
            <div>
                <?php if ($produit['stock'] > 0): ?>
                    <span class="badge bg-success fs-6">
                        <i class="bi bi-check-circle"></i> En stock (<?= $produit['stock'] ?> disponibles)
                    </span>
                <?php else: ?>
                    <span class="badge bg-danger fs-6">
                        <i class="bi bi-x-circle"></i> Rupture de stock
                    </span>
                <?php endif; ?>
            </div>

            <!-- Description -->
            <div>
                <h5 class="text-white mb-2">Description</h5>
                <p class="text-secondary" style="line-height: 1.7;">
                    <?= nl2br(htmlspecialchars($produit['descriptif'])) ?>
                </p>
            </div>

            <!-- Bouton ajouter au panier -->
            <form action="ajouter_panier.php" method="POST">
        <input type="hidden" name="numProduit" 
               value="<?php echo $produit['numProduit'] ?>">
        <button type="submit" class="btn btn-primary">
             Ajouter au panier
        </button>
    </form>

        </div>
    </div>

    <!-- Retour aux produits -->
    <div class="mt-4">
        <a href="./index.php?page=produits" class="btn btn-outline-light">
            ← Retour aux produits
        </a>
    </div>

</div>