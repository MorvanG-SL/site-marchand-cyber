<h1 class="m-3 text-center text-white p-3 rounded" style="background-color: #4a90e2;">
    Inscription
</h1>

<?php if (isset($_GET["erreur"])) { ?>
    <div class="mt-2 p-3 w-75 mx-auto alert alert-danger">
        <!-- Protection contre la faille XSS lors de l'affichage du message d'erreur -->
        <?php echo htmlspecialchars($_GET["erreur"]); ?>
    </div>
<?php } ?>

<div class="card shadow mt-2 p-3 w-50 mx-auto">
    <form action="inscription_client_exe.php" method="POST">
        <div class="row mb-3">
            <div class="col">
                <label for="pseudo" class="form-label">Pseudo *</label>
                <input type="text" class="form-control" id="pseudo" name="pseudo" required>
            </div>
            <div class="col">
                <label for="mdp" class="form-label">Mot de passe *</label>
                <input type="password" class="form-control" id="mdp" name="mdp" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="mdp2" class="form-label">Confirmer le mot de passe *</label>
                <input type="password" class="form-control" id="mdp2" name="mdp2" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="nom" class="form-label">Nom *</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="col">
                <label for="prenom" class="form-label">Prénom *</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="mail" class="form-label">Mail</label>
                <input type="email" class="form-control" id="mail" name="mail">
            </div>
            <div class="col">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone">
            </div>
        </div>
        <div class="mb-3 text-center">
            <button type="submit" class="btn btn-primary">Valider</button>
        </div>
    </form>
</div>