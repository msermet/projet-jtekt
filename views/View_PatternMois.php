<?php

// Récupération des paramètres de l'URL
$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;
$annee = $_GET['annee'] ?? null;
$mois = $_GET['mois'] ?? null;

// Chargement des traductions si non déjà chargées
if (!isset($t)) {
    $translations = include 'lang.php';
    $lang = $_SESSION['lang'] ?? 'fr';
    $t = $translations[$lang];
}

// Message de succès d'ajout
$ajoutReussi = '';
if (isset($_GET['ajout']) && $_GET['ajout'] === 'succeed') {
    $ajoutReussi = $t['saveSuccess'];
}
?>

<div class="container p-5">
    <!-- Titre de la page -->
    <h1 class="text-center mb-4 text-light"><?= $t['addMonthlyPattern'] ?></h1>
    <h3 class="fw-bold text-light">
        <?php
        // Affichage du nom de l'usine et de la ligne
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
        // Redirection si l'usine ou la ligne est introuvable
        if (!$nomUsine) {
            header("Location: /usine-introuvable");
            exit;
        } elseif (!$nomLigne) {
            header("Location: /ligne-introuvable");
            exit;
        }
        ?>
    </h3>
    <h4 class="text-light pb-2 fst-italic"><?= $mois . "/" . $annee; ?></h4>

    <!-- Bouton pour coller les données -->
    <div class="d-flex justify-content-between mb-3">
        <h4><span class="text-light text-decoration-underline"><?= $t['importTable'] ?></span>
            <button class="btn btn-warning ms-2 mb-1" id="pasteTable"><i class="bi bi-clipboard"></i> <?= $t['paste'] ?></button>
        </h4>
    </div>

    <?php if (isset($error)): ?>
        <!-- Affichage des messages d'erreur -->
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error); ?>
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
    <div class="card shadow">
        <div class="card-body">
            <!-- Formulaire pour ajouter un modèle mensuel -->
            <form method="POST" action="">
                <input type="hidden" name="ligne" value="<?= htmlspecialchars($idLigne); ?>">
                <input type="hidden" name="annee" value="<?= htmlspecialchars($annee); ?>">
                <input type="hidden" name="mois" value="<?= htmlspecialchars($mois); ?>">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="patternTable">
                        <thead class="table-dark">
                        <tr>
                            <th>Sebango <span class="text-danger">*</span></th>
                            <th><?= $t['reference'] ?></th>
                            <th><?= $t['designation'] ?></th>
                            <th><?= $t['quantity'] ?> <span class="text-danger">*</span></th>
                            <th><?= $t['action'] ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" class="form-control sebango-input" name="sebango[]"
                                       placeholder="<?= $t['example'] ?> A350" pattern=".{4}" title="<?= $t['sebangoValidation'] ?>" required>
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
                        </tr>
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
                    <i class="bi bi-arrow-left"></i> <?= $t['backToPrevious'] ?>
                </a>
            </form>
        </div>
    </div>
</div>

<!-- Pop-up d'aperçu des données collées -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $t['previewData'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="previewTable">
                        <thead class="table-dark">
                        <tr>
                            <th><?= $t['division'] ?></th>
                            <th><?= $t['location'] ?></th>
                            <th><?= $t['prodLine'] ?></th>
                            <th><?= $t['date'] ?></th>
                            <th><?= $t['sebangoNumber'] ?></th>
                            <th><?= $t['articleProd'] ?></th>
                            <th><?= $t['articleDesignation'] ?></th>
                            <th><?= $t['quantity'] ?></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $t['cancel'] ?></button>
                <button type="button" class="btn btn-success" id="importData"><?= $t['import'] ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const translations = <?= json_encode($t); ?>;
        const produits = <?= json_encode(array_map(function ($produit) {
            return [
                'sebango' => $produit->getSebango(),
                'reference' => $produit->getArticle(),
                'designation' => $produit->getDesignation(),
                'ligne' => $produit->getLigne(),
            ];
        }, $produits)); ?>;

        const idLigne = <?= json_encode($idLigne); ?>;

        const addRowButton = document.getElementById('addRow');
        const saveButton = document.getElementById('saveButton');
        const tableBody = document.querySelector('#patternTable tbody');

        const pasteTableButton = document.getElementById('pasteTable');
        const previewTableBody = document.querySelector('#previewTable tbody');
        const importModal = new bootstrap.Modal(document.getElementById('importModal'));
        const importButton = document.getElementById('importData');

        function addRow(sebango, quantite) {
            const produit = produits.find(p => p.sebango.toUpperCase() === sebango.toUpperCase() && p.ligne == idLigne);
            const reference = produit ? produit.reference : '';
            const designation = produit ? produit.designation : '';

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
            <td>
                <input type="text" class="form-control sebango-input" name="sebango[]" value="${sebango}"
                       pattern=".{4}" title="${translations['sebangoValidation']}" required>
            </td>
            <td>
                <input type="text" class="form-control reference-input" name="reference[]" value="${reference}" readonly>
            </td>
            <td>
                <input type="text" class="form-control designation-input" name="designation[]" value="${designation}" readonly>
            </td>
            <td>
                <input type="number" class="form-control" name="quantite[]" value="${quantite}" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
            tableBody.appendChild(newRow);
        }

        pasteTableButton.addEventListener('click', async () => {
            try {
                const text = await navigator.clipboard.readText();
                const rows = text.trim().split(/\r?\n/).map(row => row.split(/\t/));

                if (rows.length < 1 || rows[0].length !== 8) {
                    alert("<?= $t['errorPasteFormat8'] ?>");
                    return;
                }

                previewTableBody.innerHTML = '';
                rows.forEach(row => {
                    const newRow = document.createElement('tr');
                    row.forEach(cell => {
                        const newCell = document.createElement('td');
                        newCell.textContent = cell;
                        newRow.appendChild(newCell);
                    });
                    previewTableBody.appendChild(newRow);
                });

                importModal.show();
            } catch (error) {
                alert("<?= $t['errorClipboard'] ?>");
            }
        });

        importButton.addEventListener('click', () => {
            const firstRow = tableBody.querySelector('tr');
            if (firstRow) {
                tableBody.removeChild(firstRow);
            }

            const rows = previewTableBody.querySelectorAll('tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length < 8) return;

                const sebango = cells[4].textContent.trim();
                const quantite = parseInt(cells[7].textContent.trim(), 10) || 0;

                if (sebango) {
                    addRow(sebango, quantite);
                }
            });

            importModal.hide();
        });

        // Lors du chargement, forcer le texte en majuscules dans les inputs sebango
        tableBody.querySelectorAll('.sebango-input').forEach(input => {
            const row = input.closest('tr');
            const referenceInput = row.querySelector('.reference-input');
            const designationInput = row.querySelector('.designation-input');
            let val = input.value.trim().toUpperCase();
            input.value = val;
            const produit = produits.find(p =>
                p.sebango.toUpperCase() === val && p.ligne == idLigne
            );
            if (produit) {
                referenceInput.value = produit.reference;
                designationInput.value = produit.designation;
            }
            // Si aucun produit n'est trouvé, on laisse les champs tels quels pour permettre la correction.
        });

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
                        alert(translations['errorSebango']);
                    } else if (parseInt(quantiteInput.value, 10) <= 0) {
                        alert(translations['errorQuantityPositive']);
                    } else {
                        alert(translations['errorFillAllFields']);
                    }
                    return;
                }
            }

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <input type="text" class="form-control sebango-input" name="sebango[]"
                           placeholder="<?= $t['example'] ?> A350" pattern=".{4}" title="<?= $t['sebangoValidation'] ?>" required>
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
            `;
            tableBody.appendChild(newRow);
        });

        tableBody.addEventListener('input', (event) => {
            if (event.target.classList.contains('sebango-input')) {
                const sebangoValue = event.target.value.trim().toUpperCase();
                event.target.value = sebangoValue;
                const referenceInput = event.target.closest('tr').querySelector('.reference-input');
                const designationInput = event.target.closest('tr').querySelector('.designation-input');

                const produit = produits.find(p =>
                    p.sebango.toUpperCase() === sebangoValue && p.ligne == idLigne
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

        tableBody.addEventListener('click', (event) => {
            if (event.target.closest('.remove-row')) {
                event.target.closest('tr').remove();
            }
        });

        saveButton.addEventListener('click', (event) => {
            const rows = tableBody.querySelectorAll('tr');
            let valid = true;

            if (rows.length === 0) {
                event.preventDefault();
                alert(translations['errorAddLineBeforeSave']);
                return;
            }

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
                        alert(translations['errorSebangoLine']);
                    } else if (parseInt(quantiteInput.value, 10) <= 0) {
                        alert(translations['errorQuantityPositive']);
                    } else {
                        alert(translations['errorFillAllFields']);
                    }
                }
            });

            if (!valid) {
                event.preventDefault();
            }
        });

        tableBody.addEventListener('input', (event) => {
            if (event.target.classList.contains('sebango-input')) {
                event.target.value = event.target.value.toUpperCase();
            }
        });
    });
</script>