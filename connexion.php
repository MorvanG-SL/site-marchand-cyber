<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4 border p-4 shadow-sm bg-blue rounded">
            <h3 class="text-center mb-4">Connexion Administration</h3>
            
            <?php if(isset($_GET['erreur'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['erreur']) ?></div>
            <?php endif; ?>

            <form action="connexion_exe.php" method="post">
                <div class="mb-3">
                    <label for="login" class="form-label">Identifiant</label>
                    <input type="text" name="login" id="login" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>
        </div>
    </div>
</div>