<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTS Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<?php
// On récupère les deux types de sessions
$clientConnecte = isset($_SESSION['user']);
$adminConnecte  = isset($_SESSION['login']);

// Variable pour savoir si n'importe qui est connecté
$estConnecte = ($clientConnecte || $adminConnecte);
$page = $_GET['page'] ?? 'accueil';

// Compter les articles du panier
$nbArticles = 0;
if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        $nbArticles += $item['quantite'] ?? 1;
    }
}

// Fonction helper pour générer la classe active
if (!function_exists('navClass')) {
    function navClass(string $lien, string $page): string {
        if ($lien === $page) {
            return 'nav-link text-white bg-primary bg-opacity-25 rounded-2 px-3 py-1';
        }
        return 'nav-link text-secondary rounded-2 px-3 py-1';
    }
}
?>

<body class="bg-dark">
<nav class="navbar bg-black border-bottom border-secondary sticky-top px-4 py-0" style="height:62px;">
  <div class="container-fluid px-0">
    <!-- Logo -->
    <a href="./index.php?page=accueil" class="navbar-brand d-flex align-items-center gap-2 me-4">
      <img src="images/LogoSiteMarchand.png" alt="BTS Store" style="width:36px; height:36px; filter: drop-shadow(0 0 8px rgba(80,150,255,0.4));">
      <span class="fw-bold text-white">BTS <span class="text-secondary fw-light">Store</span></span>
    </a>

    <!-- Menu Navigation -->
    <div class="d-flex align-items-center gap-1 flex-grow-1">
      <a class="<?= navClass('accueil', $page) ?>" href="./index.php?page=accueil">Accueil</a>
      <a class="<?= navClass('produits', $page) ?>" href="./index.php?page=produits">Produits</a>
      <a class="<?= navClass('contact', $page) ?>" href="./index.php?page=contact">Contact</a>
      
      <!-- Lien "Mes commandes" visible uniquement pour le Client -->
      <?php if ($clientConnecte) : ?>
          <a class="<?= navClass('mes_commandes', $page) ?>" href="index.php?page=mes_commandes">Mes commandes</a>
      <?php endif; ?>

      <div class="vr bg-secondary mx-2 opacity-25" style="height:22px;"></div>
      
      <!-- Lien Admin (met en surbrillance si on est sur la page admin) -->
      <a class="<?= navClass('admin', $page) ?><?= navClass('connexion', $page) ?>" href="./index.php?page=<?= $adminConnecte ? 'admin' : 'connexion' ?>">Administration</a>
      
      <a class="<?= navClass('inscription', $page) ?>" href="./index.php?page=inscription">Inscription</a>
    </div>

    <!-- Panier et Compte -->
    <div class="d-flex align-items-center gap-3 ms-auto">
      <a href="./index.php?page=panier" class="btn <?= $page === 'panier' ? 'btn-primary' : 'btn-outline-secondary' ?> position-relative d-flex align-items-center justify-content-center p-0" style="width:40px;height:40px;border-radius:10px;">
        <i class="bi bi-cart3 text-white fs-5"></i>
        <?php if ($nbArticles > 0): ?>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" style="font-size:0.6rem;">
            <?= $nbArticles ?>
          </span>
        <?php endif; ?>
      </a>

      <?php if ($estConnecte): ?>
        <!-- Formulaire de Déconnexion (Client ou Admin) -->
        <form action="deconnexion.php" method="post" class="m-0">
          <button type="submit" class="btn btn-outline-light d-flex align-items-center gap-2 fw-semibold" style="border-radius:9px;">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
            <small class="opacity-50" style="font-size: 0.75rem;">(<?= htmlspecialchars($_SESSION['user'] ?? $_SESSION['login']) ?>)</small>
          </button>
        </form>
      <?php else: ?>
        <!-- Bouton Connexion si personne n'est là -->
        <a href="./index.php?page=connexionclient" class="btn <?= $page === 'connexionclient' ? 'btn-primary' : 'btn-outline-primary' ?> d-flex align-items-center gap-2 fw-semibold" style="border-radius:9px;">
          <i class="bi bi-person-circle"></i> Connexion
        </a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>