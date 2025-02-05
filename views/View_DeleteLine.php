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

// Récupère les paramètres de l'URL ou définit à null s'ils ne sont pas présents
$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;

// Récupère le nom de l'usine et de la ligne
$nomUsine = null;
$nomLigne = null;
foreach ($usines as $usine) {
    if ($usine->getId() == $idUsine) {
        $nomUsine = $usine->getNom();
        foreach ($usine->getLignes() as $ligne) {
            if ($ligne->getId() == $idLigne) {
                $nomLigne = $ligne->getNom();
                break;
            }
        }
        break;
    }
}

// Redirige si l'usine ou la ligne n'est pas trouvée
if (!$nomUsine) {
    header("Location: /usine-introuvable");
    exit;
} elseif (!$nomLigne) {
    header("Location: /ligne-introuvable");
    exit;
}
?>

<main class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-light mb-3"><?= $t['deleteLine'] ?></h1>
        <h2 class="fw-semibold text-secondary">
            <?= htmlspecialchars($nomUsine) ?> - <?= htmlspecialchars($nomLigne) ?>
        </h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 mb-4" style="background-color: #1c1c1c; color: #f8f9fa; border-radius: 1rem;">
                <div class="card-body text-center">
                    <p class="mb-4"><?= $t['confirmDeleteLine'] ?></p>
                    <form id="deleteForm" action="/ligne/supprimer" method="post">
                        <input type="hidden" name="usine" value="<?= htmlspecialchars($idUsine) ?>">
                        <input type="hidden" name="ligne" value="<?= htmlspecialchars($idLigne) ?>">
                        <div class="mb-3">
                            <button type="button" class="btn btn-danger option-button" onclick="selectOption('yes', this)"><?= $t['yes'] ?></button>
                            <button type="button" class="btn btn-secondary option-button" onclick="selectOption('no', this)"><?= $t['no'] ?></button>
                        </div>
                        <div id="validateButton" class="mb-3" style="display: none;">
                            <button type="submit" class="btn btn-primary"><?= $t['validate'] ?></button>
                        </div>
                        <!-- Lien pour retourner à la page précédente -->
                        <a href="/ligne?usine=<?= $idUsine ?>&ligne=<?= $idLigne ?>" class="btn btn-link text-secondary mt-3">
                            <i class="bi bi-arrow-left"></i> <?= $t['back'] ?>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .selected-option {
        transform: scale(1.1);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }
    .deselected-option {
        filter: brightness(0.7);
    }
</style>

<script>
    function selectOption(value, button) {
        const form = document.getElementById('deleteForm');
        const validateButton = document.getElementById('validateButton');

        // Ajoute l'input caché pour la confirmation
        let confirmInput = form.querySelector('input[name="confirm"]');
        if (!confirmInput) {
            confirmInput = document.createElement('input');
            confirmInput.type = 'hidden';
            confirmInput.name = 'confirm';
            form.appendChild(confirmInput);
        }
        confirmInput.value = value;

        // Affiche le bouton de validation
        validateButton.style.display = 'block';

        // Retire les classes 'selected-option' et 'deselected-option' de tous les boutons
        document.querySelectorAll('.option-button').forEach(btn => {
            btn.classList.remove('selected-option', 'deselected-option');
        });

        // Ajoute la classe 'selected-option' au bouton cliqué et 'deselected-option' à l'autre bouton
        button.classList.add('selected-option');
        document.querySelectorAll('.option-button').forEach(btn => {
            if (btn !== button) {
                btn.classList.add('deselected-option');
            }
        });
    }
</script>