<?php
// On récupère le message d'erreur s'il y en a un dans l'URL
$erreur = $_GET['erreur'] ?? null;
?>

<div class="container mt-5 text-white">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <!-- Carte de connexion -->
            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>Connexion au site</h4>
                </div>
                
                <div class="card-body p-4">
                    <form action="connexionclient_exe.php" method="post">
                        
                        <!-- Champ Email / Login -->
                        <div class="mb-4">
                            <label for="email" class="form-label text-secondary small fw-bold text-uppercase">Identifiant (Email) :</label>
                            <div class="input-group">
                                <span class="input-group-text bg-black border-secondary text-secondary"><i class="bi bi-envelope"></i></span>
                                <input type="text" 
                                       name="email" 
                                       id="email" 
                                       class="form-control bg-black text-white border-secondary" 
                                       placeholder="votre@email.com" 
                                       required>
                            </div>
                        </div>
                        
                        <!-- Champ Mot de passe -->
                        <div class="mb-4">
                            <label for="password" class="form-label text-secondary small fw-bold text-uppercase">Mot de passe :</label>
                            <div class="input-group">
                                <span class="input-group-text bg-black border-secondary text-secondary"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control bg-black text-white border-secondary" 
                                       placeholder="••••••••" 
                                       required>
                            </div>
                        </div>
                        
                        <!-- Bouton de validation -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                Se connecter
                            </button>
                        </div>
                    </form>

                    <!-- Affichage dynamique des erreurs (Champs vides, Identifiants incorrects, etc.) -->
                    <?php if ($erreur): ?>
                        <div class="alert alert-danger mt-4 d-flex align-items-center border-0" style="background-color: rgba(220, 53, 69, 0.2); color: #ff8585;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <?= htmlspecialchars($erreur) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pied de carte pour lien d'inscription -->
                <div class="card-footer bg-transparent border-secondary text-center py-3">
                    <p class="mb-0 text-secondary small">
                        Pas encore de compte ? <a href="index.php?page=inscription" class="text-primary text-decoration-none fw-bold">Créer un compte</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>