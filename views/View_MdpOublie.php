<?php

// Démarre une nouvelle session ou reprend une session existante
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifie si la variable $t n'est pas définie
if (!isset($t)) {
    // Inclut le fichier de traduction
    $translations = include 'lang.php';
    // Définit la langue par défaut à 'fr' si elle n'est pas définie dans la session
    $lang = $_SESSION['lang'] ?? 'fr';
    // Récupère les traductions pour la langue sélectionnée
    $t = $translations[$lang];
}
?>

<main class="container my-auto">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="form-container p-4" style="max-width: 500px; width: 100%;">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <!-- Titre principal -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold"><?= $t['resetPassword'] ?></h2>
                        <p class="text-muted small"><?= $t['enterInfoToReset'] ?></p>
                    </div>

                    <!-- Messages d'erreur et de succès -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= $t['close'] ?>"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire de réinitialisation -->
                    <form method="POST" action="">
                        <!-- Champ pour l'identifiant -->
                        <div class="mb-3">
                            <label for="identifiant" class="form-label fw-bold"><?= $t['identifiant'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <input type="text" name="identifiant" id="identifiant" class="form-control" placeholder="<?= $t['identifiantPlaceholder'] ?>" value="<?php echo isset($_POST['identifiant']) ? htmlspecialchars($_POST['identifiant']) : ''; ?>" required>
                            </div>
                        </div>

                        <!-- Champ pour l'email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold"><?= $t['email'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-info text-white">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="<?= $t['emailPlaceholder'] ?>" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                            </div>
                        </div>

                        <!-- Boutons de soumission -->
                        <div class="d-flex justify-content-end align-items-center pt-3">
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