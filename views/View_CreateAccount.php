<?php
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
                        <!-- Affiche le titre de création de compte -->
                        <h2 class="fw-bold"><?= $t['createAccount'] ?></h2>
                        <!-- Affiche le sous-titre avec les instructions -->
                        <p class="text-muted small"><?= $t['fillInfoToCreate'] ?></p>
                    </div>

                    <!-- Messages d'erreur -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <!-- Affiche le message d'erreur -->
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= $t['close'] ?>"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire -->
                    <form method="POST" action="">
                        <!-- Identifiant -->
                        <div class="mb-3">
                            <label for="identifiant" class="form-label fw-bold"><?= $t['identifiant'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary text-white">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <!-- Champ de saisie pour l'identifiant -->
                                <input type="text" name="identifiant" id="identifiant" class="form-control" placeholder="<?= $t['identifiantPlaceholder'] ?>" value="<?php echo isset($_POST['identifiant']) ? htmlspecialchars($_POST['identifiant']) : ''; ?>" required>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <!-- Lien pour afficher la convention de nommage pour un identifiant -->
                                <a href="#" data-bs-toggle="modal" data-bs-target="#identifiantInfo" class="text-decoration-none"><?= $t['conventionNommageID'] ?></a>
                            </small>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold"><?= $t['email'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-info text-white">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <!-- Champ de saisie pour l'email -->
                                <input type="email" name="email" id="email" class="form-control" placeholder="<?= $t['emailPlaceholder'] ?>" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold"><?= $t['password'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-danger text-white">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <!-- Champ de saisie pour le mot de passe -->
                                <input type="password" name="password" id="password" class="form-control" placeholder="<?= $t['passwordPlaceholder'] ?>" required>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <!-- Lien pour afficher les exigences du mot de passe -->
                                <a href="#" data-bs-toggle="modal" data-bs-target="#passwordInfo" class="text-decoration-none"><?= $t['viewPasswordRequirements'] ?></a>
                            </small>
                        </div>

                        <!-- Modal pour les exigences du mot de passe -->
                        <div class="modal fade" id="passwordInfo" tabindex="-1" aria-labelledby="passwordInfoLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="passwordInfoLabel"><?= $t['passwordRequirements'] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= $t['close'] ?>"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ul>
                                            <!-- Liste des exigences du mot de passe -->
                                            <li><?= $t['passwordMinLength'] ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal pour la convention de nommage d'un identifiant -->
                        <div class="modal fade" id="identifiantInfo" tabindex="-1" aria-labelledby="identifiantInfoLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="passwordInfoLabel"><?= $t['conventionNommage'] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= $t['close'] ?>"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><?= $t['conventionNommageTXT'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation du mot de passe -->
                        <div class="mb-3">
                            <label for="passwordconf" class="form-label fw-bold"><?= $t['confirmPassword'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-warning text-dark">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <!-- Champ de saisie pour la confirmation du mot de passe -->
                                <input type="password" name="passwordconf" id="passwordconf" class="form-control" placeholder="<?= $t['confirmPasswordPlaceholder'] ?>" required>
                            </div>
                        </div>

                        <!-- Choix du statut administrateur -->
                        <div class="mb-3 mt-4 form-check form-switch">
                            <input type="hidden" name="admin" value="0">
                            <input type="checkbox" name="admin" id="admin" class="form-check-input" value="1">
                            <label for="admin" class="form-check-label fw-semibold text-primary"><?= $t['admin'] ?></label>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-end align-items-center pt-2">
                            <!-- Bouton pour soumettre le formulaire -->
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-plus-fill"></i> <?= $t['signUp'] ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>