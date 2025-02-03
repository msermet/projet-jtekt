<?php

$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;
$annee = $_GET['annee'] ?? null;
$mois = $_GET['mois'] ?? null;

if (!isset($t)) {
    $translations = include 'lang.php';
    $lang = $_SESSION['lang'] ?? 'fr';
    $t = $translations[$lang];
}

$ajoutReussi = '';
if (isset($_GET['ajout']) && $_GET['ajout'] === 'succeed') {
    $ajoutReussi = $t['saveSuccess'];
}

// Filtrer les enregistrements de PatternMois
$filteredPatterns = array_filter($patternMois, function ($pattern) use ($idLigne, $annee, $mois) {
    return $pattern->getAnnee() == $annee && $pattern->getMois() == $mois && $pattern->getProduit()->getLigne() == $idLigne;
});

?>

<div class="container p-5">
    <h1 class="text-center mb-4 text-light"><?= $t['editPatternMois'] ?></h1>
    <h3 class="fw-bold text-light">
        <?php
        $nomUsine = null;
        foreach ($usines as $usine) {
            if ($usine->getId() == $idUsine) {
                $nomUsine = $usine->getNom();
                echo htmlspecialchars($nomUsine);
                break;
            }
        }

        $nomLigne = null;
        if ($nomUsine) {
            foreach ($usines as $usine) {
                if ($usine->getId() == $idUsine) {
                    foreach ($usine->getLignes() as $ligne) {
                        if ($ligne->getId() == $idLigne) {
                            $nomLigne = $ligne->getNom();
                            echo " - " . htmlspecialchars($nomLigne);
                            break 2;
                        }
                    }
                }
            }
        }

        if (!$nomUsine) {
            header("Location: /usine-introuvable");
        } elseif (!$nomLigne) {
            header("Location: /ligne-introuvable");
        }
        ?>
    </h3>
    <h4 class="text-light pb-2 fst-italic"><?php echo $mois . "/" . $annee; ?></h4>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= $t['close'] ?>"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($ajoutReussi)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($ajoutReussi); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= $t['close'] ?>"></button>
        </div>
    <?php endif; ?>
    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="ligne" value="<?= htmlspecialchars($idLigne); ?>">
                <input type="hidden" name="annee" value="<?= htmlspecialchars($annee); ?>">
                <input type="hidden" name="mois" value="<?= htmlspecialchars($mois); ?>">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="patternTable">
                        <thead class="table-dark">
                        <tr>
                            <th><?= $t['sebango'] ?> <span class="text-danger">*</span></th>
                            <th><?= $t['reference'] ?></th>
                            <th><?= $t['designation'] ?></th>
                            <th><?= $t['quantity'] ?> <span class="text-danger">*</span></th>
                            <th><?= $t['delete'] ?></th>
                            <th><?= $t['move'] ?></th>
                        </tr>
                        </thead>
                        <tbody id="sortableTable">
                        <?php foreach ($filteredPatterns as $pattern): ?>
                            <tr>
                                <td>
                                    <input type="text" class="form-control sebango-input" name="sebango[]"
                                           value="<?= htmlspecialchars($pattern->getSebango()); ?>" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control reference-input" name="reference[]"
                                           readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control designation-input" name="designation[]"
                                           readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="quantite[]"
                                           value="<?= htmlspecialchars($pattern->getQuantite()); ?>" required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                                <td class="text-center handle">
                                    <i class="bi bi-arrows-move"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="text-muted mt-2"><span class="text-danger">*</span> <?= $t['requiredFields'] ?></p>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-success" id="addRow">
                        <i class="bi bi-plus"></i> <?= $t['addLine'] ?>
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveButton"><?= $t['save'] ?></button>
                </div>
                <a href="/ligne?usine=<?= $idUsine ?>&ligne=<?= $idLigne ?>" class="btn btn-link text-muted mt-3">
                    <i class="bi bi-arrow-left"></i> <?= $t['back'] ?>
                </a>
            </form>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const produits = <?php echo json_encode(array_map(function ($produit) {
            return [
                'sebango' => $produit->getSebango(),
                'reference' => $produit->getArticle(),
                'designation' => $produit->getDesignation(),
                'ligne' => $produit->getLigne(), // Ajout du champ ligne
            ];
        }, $produits)); ?>;

        const idLigne = <?php echo json_encode($idLigne); ?>; // Ligne récupérée via le formulaire
        const saveButton = document.getElementById('saveButton');
        const tableBody = document.querySelector('#sortableTable');

        // Traductions
        const translations = {
            invalidSebango: "<?= $t['invalidSebango'] ?>",
            invalidQuantity: "<?= $t['invalidQuantity'] ?>",
            fillAllFields: "<?= $t['fillAllFields'] ?>",
            selectValidDate: "<?= $t['selectValidDate'] ?>",
            addRowError: "<?= $t['addRowError'] ?>",
        };

        // Automatiser les champs Référence et Désignation
        tableBody.querySelectorAll('.sebango-input').forEach(input => {
            const row = input.closest('tr');
            const referenceInput = row.querySelector('.reference-input');
            const designationInput = row.querySelector('.designation-input');

            const produit = produits.find(p => p.sebango.toUpperCase() === input.value.toUpperCase());

            if (produit) {
                referenceInput.value = produit.reference;
                designationInput.value = produit.designation;
            } else {
                referenceInput.value = '';
                designationInput.value = '';
            }
        });

        // Supprimer une ligne
        tableBody.addEventListener('click', (event) => {
            if (event.target.closest('.remove-row')) {
                event.target.closest('tr').remove();
            }
        });

        // Ajouter la fonctionnalité de tri avec SortableJS
        new Sortable(tableBody, {
            animation: 150,
            handle: '.handle',
            ghostClass: 'sortable-ghost',
            onStart: (evt) => {
                evt.item.classList.add('table-warning');
            },
            onEnd: (evt) => {
                evt.item.classList.remove('table-warning');
            }
        });

        // Ajouter une nouvelle ligne
        const addRowButton = document.getElementById('addRow');
        addRowButton.addEventListener('click', () => {
            const lastRow = tableBody.querySelector('tr:last-child');
            let allFilled = true;

            if (lastRow) {
                const sebangoInput = lastRow.querySelector('.sebango-input');
                const referenceInput = lastRow.querySelector('.reference-input');
                const designationInput = lastRow.querySelector('.designation-input');
                const quantiteInput = lastRow.querySelector('input[name="quantite[]"]');

                allFilled = sebangoInput.value.trim().length === 4 &&
                    referenceInput.value.trim() !== '' &&
                    designationInput.value.trim() !== '' &&
                    quantiteInput.value.trim() !== '' &&
                    parseInt(quantiteInput.value, 10) > 0;

                if (!allFilled) {
                    if (!referenceInput.value.trim() || !designationInput.value.trim()) {
                        alert(translations.invalidSebango);
                    } else if (parseInt(quantiteInput.value, 10) <= 0) {
                        alert(translations.invalidQuantity);
                    } else {
                        alert(translations.fillAllFields);
                    }
                    return;
                }
            }

            // Ajouter une nouvelle ligne
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
            <td>
                <input type="text" class="form-control sebango-input" name="sebango[]"
                       placeholder="<?= $t['example'] ?> A350" pattern=".{4}" title="<?= $t['sebangoTitle'] ?>" required>
            </td>
            <td>
                <input type="text" class="form-control reference-input" name="reference[]" placeholder="<?= $t['reference'] ?>" readonly>
            </td>
            <td>
                <input type="text" class="form-control designation-input" name="designation[]" placeholder="<?= $t['designation'] ?>" readonly>
            </td>
            <td>
                <input type="number" class="form-control" name="quantite[]" placeholder="<?= $t['example'] ?> 561" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
            <td class="text-center handle">
                <i class="bi bi-arrows-move"></i>
            </td>
        `;
            tableBody.appendChild(newRow);
        });

        // Gestion automatique des colonnes Référence et Désignation
        tableBody.addEventListener('input', (event) => {
            if (event.target.classList.contains('sebango-input')) {
                const sebangoValue = event.target.value.trim().toUpperCase();
                const referenceInput = event.target.closest('tr').querySelector('.reference-input');
                const designationInput = event.target.closest('tr').querySelector('.designation-input');

                const produit = produits.find(p =>
                    p.sebango.toUpperCase() === sebangoValue && p.ligne == idLigne // Vérifie aussi la ligne
                );

                if (produit) {
                    referenceInput.value = produit.reference;
                    designationInput.value = produit.designation;
                } else {
                    referenceInput.value = '';
                    designationInput.value = '';
                }
            }
        });

        // Validation avant d'enregistrer
        saveButton.addEventListener('click', (event) => {
            const rows = tableBody.querySelectorAll('tr');
            let valid = true;

            rows.forEach(row => {
                const referenceInput = row.querySelector('.reference-input');
                const designationInput = row.querySelector('.designation-input');
                const quantiteInput = row.querySelector('input[name="quantite[]"]');

                if (
                    referenceInput.value.trim() === '' ||
                    designationInput.value.trim() === '' ||
                    quantiteInput.value.trim() === '' ||
                    parseInt(quantiteInput.value, 10) <= 0
                ) {
                    valid = false;

                    if (!referenceInput.value.trim() || !designationInput.value.trim()) {
                        alert(translations.invalidSebango);
                    } else if (parseInt(quantiteInput.value, 10) <= 0) {
                        alert(translations.invalidQuantity);
                    } else {
                        alert(translations.fillAllFields);
                    }
                }
            });

            if (!valid) {
                event.preventDefault();
            }
        });
    });

</script>

<style>
    .sortable-ghost {
        background-color: #f8d7da !important;
        border: 2px dashed #f5c2c7 !important;
    }
    .handle {
        cursor: move;
    }
</style>