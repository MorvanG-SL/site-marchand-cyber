<body style="background: linear-gradient(135deg, #0f172a, #1e3a8a);">
<?php
require "./info_bdd.php";
// Requête pour vérifier si le couple login/mdp est bon
$requeteSQL = "SELECT numProduit, nomProduit, descriptif, stock, prixUnitaire, nomImage,
categorie.libelleCategorie FROM produits
INNER JOIN categorie ON produits.idCategorie = categorie.idCategorie;";
// Exécution de la requête
$reponse = $pdo->query($requeteSQL);
$liste_produits = $reponse->fetchall(PDO::FETCH_ASSOC);
?>
<div class="container">
    <h3 class="m-3 text-center text-white p-2 rounded" style="background-color: #4a90e2;">
<i class="bi bi-apple"></i>
        Liste des produits disponibles
<i class="bi bi-apple"></i>
    </h3>
    <div class="text-center p-2">
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-3">
            <?php foreach ($liste_produits as $produit) { ?>
                <div class="col">
                    <div class="card h-100 d-flex flex-column">
                        <img src="./images/<?php echo $produit['nomImage']; ?>" class="card-img-top"
                            alt="<?php echo $produit['nomImage']; ?>">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <p class="card-text"></p>
                            <a href="index.php?page=detailproduit&numProduit=<?php echo $produit['numProduit']; ?>" class="text-decoration-none">
                                <div class="text-white p-2 d-flex align-items-center justify-content-center" style="background-color:#5a94a1; height:52px;">
                                    <h6 class="card-title m-0 text-center text-truncate w-100">
                                        <?php echo $produit['nomProduit']; ?>
                                    </h6>
                                </div>
                            </a>
                        </div>
                        <div class="card-footer text-white">
                            <small class="text-body-secondary">
                                <?php echo $produit['prixUnitaire']; ?> €</small>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="text-center p-2">
        <a href="./index.php?page=panier" type="button" class="btn btn-primary opacity-75">
            <h5>Voir mon panier</h5>
        </a>
    </div>
</div>