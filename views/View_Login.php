<?php

// Vérifie si la variable de traduction $t n'est pas définie
if (!isset($t)) {
    // Inclut le fichier de traduction
    $translations = include 'lang.php';
    // Définit la langue par défaut à 'fr' si elle n'est pas définie dans la session
    $lang = $_SESSION['lang'] ?? 'fr';
    // Récupère les traductions pour la langue sélectionnée
    $t = $translations[$lang];
}

// Initialise le message d'inscription
$inscriptionMessage = '';
// Vérifie si l'inscription a réussi et définit le message approprié
if (isset($_GET['inscription']) && $_GET['inscription'] === 'succeed') {
    $inscriptionMessage = $t['registrationSuccess'];
}

// Initialise le message de connexion
$connexionMessage = '';
// Vérifie s'il y a une erreur de connexion et définit le message approprié
if (isset($_GET['erreur']) && $_GET['erreur'] === 'connexion') {
    $connexionMessage = $t['mustBeLoggedIn'];
}

// Message de succès de réinitialisation
$resetSuccess = '';
if (isset($_GET['reset']) && $_GET['reset'] === 'succeed') {
    $resetSuccess = $t['resetSuccess'];
}
?>

<main class="container my-auto">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="form-container p-4" style="max-width: 500px; width: 100%;">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <!-- Titre principal -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold"><?= $t['login'] ?></h2>
                        <p class="text-muted small"><?= $t['enterInfoToLogin'] ?></p>
                    </div>

                    <!-- Messages d'erreur et de succès -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= $t['close'] ?>"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($inscriptionMessage)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <?php echo htmlspecialchars($inscriptionMessage); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= $t['close'] ?>"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($connexionMessage)): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle-fill"></i>
                            <?php echo htmlspecialchars($connexionMessage); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= $t['close'] ?>"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($resetSuccess)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <?php echo htmlspecialchars($resetSuccess); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= $t['close'] ?>"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire de connexion -->
                    <form method="POST" action="">
                        <!-- Champ pour l'identifiant -->
                        <div class="mb-3">
                            <label for="identifiant" class="form-label fw-bold"><?= $t['identifiant'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <input type="text" name="identifiant" id="identifiant" class="form-control" placeholder="<?= $t['identifiantPlaceholder'] ?>" value="<?php echo isset($_POST['identifiant']) ? htmlspecialchars($_POST['identifiant']) : ''; ?>" required>
                            </div>
                        </div>

                        <!-- Champ pour le mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold"><?= $t['password'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary text-white">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="<?= $t['passwordPlaceholder'] ?>" required>
                            </div>
                        </div>

                        <!-- Boutons de soumission et de retour -->
                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <a href="/mdpOublie" class="text-muted"><?= $t['forgotPassword'] ?></a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> <?= $t['login'] ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>