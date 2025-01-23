<?php
$inscriptionMessage = '';
if (isset($_GET['inscription']) && $_GET['inscription'] === 'succeed') {
    $inscriptionMessage = 'Inscription réussie.';
}

$connexionMessage = '';
if (isset($_GET['erreur']) && $_GET['erreur'] === 'connexion') {
    $connexionMessage = 'Vous devez être connecté pour accéder à cette page.';
}
?>

<main class="container my-auto">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="form-container p-4" style="max-width: 400px; width: 100%;">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <!-- Titre principal -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Connexion</h2>
                        <p class="text-muted small">Entrez vos informations pour vous connecter.</p>
                    </div>

                    <!-- Messages d'erreur et de succès -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($inscriptionMessage)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <?php echo htmlspecialchars($inscriptionMessage); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($connexionMessage)): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle-fill"></i>
                            <?php echo htmlspecialchars($connexionMessage); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire -->
                    <form method="POST" action="">
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="ex : dupondjean@gmail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary text-white">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Votre mot de passe" required>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <a href="/" class="btn btn-link text-decoration-none text-muted">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Se connecter
                            </button>
                        </div>
                    </form>

                    <!-- Lien d'inscription -->
                    <div class="pt-4 text-center">
                        <p class="small text-muted">
                            Vous n'avez pas de compte ? <a href="/creationcompte" class="fw-semibold link-primary">Inscrivez-vous ici</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Import Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
