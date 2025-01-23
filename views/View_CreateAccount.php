<main class="container my-auto">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="form-container p-4" style="max-width: 500px; width: 100%;">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <!-- Titre principal -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Créer un compte</h2>
                        <p class="text-muted small">Complétez les informations pour créer un nouveau compte.</p>
                    </div>

                    <!-- Messages d'erreur -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire -->
                    <form method="POST" action="">
                        <!-- Prénom -->
                        <div class="mb-3">
                            <label for="prenom" class="form-label fw-bold">Prénom</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <input type="text" name="prenom" id="prenom" class="form-control" placeholder="ex : Jean" value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>" required>
                            </div>
                        </div>

                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="nom" class="form-label fw-bold">Nom</label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary text-white">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <input type="text" name="nom" id="nom" class="form-control" placeholder="ex : Dupond" value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-info text-white">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="ex : dupondjean@gmail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-danger text-white">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Votre mot de passe" required>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#passwordInfo" class="text-decoration-none">Consultez les exigences du mot de passe</a>
                            </small>
                        </div>

                        <!-- Modal pour les exigences du mot de passe -->
                        <div class="modal fade" id="passwordInfo" tabindex="-1" aria-labelledby="passwordInfoLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="passwordInfoLabel">Exigences du mot de passe</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ul>
                                            <li>Minimum 8 caractères</li>
                                            <li>Au moins une lettre majuscule et une lettre minuscule</li>
                                            <li>Au moins un chiffre</li>
                                            <li>Au moins un caractère spécial</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation du mot de passe -->
                        <div class="mb-3">
                            <label for="passwordconf" class="form-label fw-bold">Confirmation du mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-warning text-dark">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" name="passwordconf" id="passwordconf" class="form-control" placeholder="Confirmez votre mot de passe" required>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <a href="/connexion" class="btn btn-link text-muted">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-plus-fill"></i> S'inscrire
                            </button>
                        </div>
                    </form>

                    <!-- Lien de connexion -->
                    <div class="pt-4 text-center">
                        <p class="small text-muted">
                            Vous avez déjà un compte ? <a href="/connexion" class="fw-semibold link-primary">Connectez-vous ici</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Import Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
