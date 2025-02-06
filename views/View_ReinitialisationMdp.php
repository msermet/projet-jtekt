<?php

// Démarre une nouvelle session ou reprend une session existante
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Vérifie si la variable de traduction $t n'est pas définie
if (!isset($t)) {
    // Inclut le fichier de traduction
    $translations = include 'lang.php';
    // Définit la langue par défaut à 'fr' si elle n'est pas définie dans la session
    $lang = $_SESSION['lang'] ?? 'fr';
    // Récupère les traductions pour la langue sélectionnée
    $t = $translations[$lang];
}

// Récupère l'id de l'utilisateur depuis l'URL
$id = $_GET['id'] ?? null;

?>

<main class="container my-auto">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="form-container p-4" style="max-width: 500px; width: 100%;">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <!-- Titre principal -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold"><?= $t['resetPassword'] ?></h2>
                        <p class="text-muted small"><?= $t['enterNewPassword'] ?></p>
                    </div>

                    <!-- Messages d'erreur et de succès -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?php echo htmlspecialchars($error); ?>
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

                    <!-- Formulaire de réinitialisation -->
                    <form method="POST" action="">
                        <input type="hidden" name="id" id="id" value="<?= htmlspecialchars($id) ?>">
                        <!-- Champ pour le nouveau mot de passe -->
                        <div class="mb-3">
                            <label for="newPassword" class="form-label fw-bold"><?= $t['newPassword'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary text-white">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" name="newPassword" id="newPassword" class="form-control" placeholder="<?= $t['newPasswordPlaceholder'] ?>" required>
                            </div>
                        </div>

                        <!-- Champ pour la confirmation du nouveau mot de passe -->
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label fw-bold"><?= $t['confirmPassword'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary text-white">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="<?= $t['confirmPasswordPlaceholder'] ?>" required>
                            </div>
                        </div>

                        <!-- Boutons de soumission -->
                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <a href="/mdpOublie" class="text-muted"><?= $t['back'] ?></a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-arrow-repeat"></i> <?= $t['resetPassword'] ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>