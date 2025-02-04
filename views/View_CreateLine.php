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

// Récupère l'ID de l'usine depuis l'URL
$idUsine = $_GET['usine'] ?? null;
$nomUsine = null;

// Recherche le nom de l'usine correspondante
foreach ($usines as $usine) {
    if ($usine->getId() == $idUsine) {
        $nomUsine = $usine->getNom();
        break;
    }
}

// Redirection si l'usine est introuvable
if (!$nomUsine) {
    header("Location: /usine-introuvable");
    exit;
}

// Message de succès d'ajout
$ajoutReussi = '';
if (isset($_GET['ajout']) && $_GET['ajout'] === 'succeed') {
    $ajoutReussi = $t['saveSuccess'];
}
?>

<main class="container my-auto">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="form-container p-4" style="max-width: 500px; width: 100%;">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <!-- Titre principal -->
                    <div class="text-center mb-4">
                        <!-- Affiche le titre de création de ligne -->
                        <h2 class="fw-bold"><?= $t['creerLigne'] ?></h2>
                        <!-- Affiche le nom de l'usine -->
                        <h3 class="fw-bold"><?= htmlspecialchars($nomUsine) ?></h3>
                        <!-- Affiche le sous-titre avec les instructions -->
                        <p class="text-muted small"><?= $t['fillInfoToCreateLine'] ?></p>
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
                    <?php if (!empty($ajoutReussi)): ?>
                        <!-- Affichage des messages de succès -->
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($ajoutReussi); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= $t['close'] ?>"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire -->
                    <form method="POST" action="">
                        <input type="hidden" name="usine" value="<?= htmlspecialchars($idUsine) ?>">

                        <!-- Nom de la ligne -->
                        <div class="mb-3">
                            <label for="nom" class="form-label fw-bold"><?= $t['nomLigne'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white">
                                    <i class="bi bi-pencil-fill"></i>
                                </span>
                                <!-- Champ de saisie pour le nom de la ligne -->
                                <input type="text" name="nom" id="nom" class="form-control" placeholder="<?= $t['nomLignePlaceholder'] ?>" value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" required>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-end align-items-center pt-4">
                            <!-- Bouton pour soumettre le formulaire -->
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle-fill"></i> <?= $t['creer'] ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>