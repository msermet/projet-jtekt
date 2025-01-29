<?php

$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;
$annee = $_GET['annee'] ?? null;
$mois = $_GET['mois'] ?? null;
$jour = $_GET['jour'] ?? null;

if (!isset($t)) {
    $translations = include 'lang.php';
    $lang = $_SESSION['lang'] ?? 'fr';
    $t = $translations[$lang];
}

$ajoutReussi = '';
if (isset($_GET['ajout']) && $_GET['ajout'] === 'succeed') {
    $ajoutReussi = $t['saveSuccess'];
}

?>

<div class="container p-5">
    <h1 class="text-center mb-4 text-light">Add Data to Daily Pattern</h1>
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
    <h4 class="text-light pb-2 fst-italic"><?php echo $jour."/".$mois."/".$annee; ?></h4>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($ajoutReussi)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($ajoutReussi); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="ligne" value="<?php echo htmlspecialchars($idLigne); ?>">
                <input type="hidden" name="annee" value="<?php echo htmlspecialchars($annee); ?>">
                <input type="hidden" name="mois" value="<?php echo htmlspecialchars($mois); ?>">
                <input type="hidden" name="jour" value="<?php echo htmlspecialchars($jour); ?>">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="patternTable">
                        <thead class="table-dark">
                        <tr>
                            <th>Sebango <span class="text-danger">*</span></th>
                            <th>Reference</th>
                            <th>Designation</th>
                            <th>Need <span class="text-danger">*</span></th>
                            <th>Relicat <span class="text-danger">*</span></th>
                            <th>Remaining to Produce</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" class="form-control sebango-input" name="sebango[]"
                                       placeholder="ex : A350" pattern=".{4}" title="Sebango must contain exactly 4 characters" required>
                            </td>
                            <td>
                                <input type="text" class="form-control reference-input" name="reference[]" placeholder="Reference" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control designation-input" name="designation[]" placeholder="Designation" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="besoin[]" placeholder="ex : 600" required>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="relicat[]" placeholder="ex : 27" required>
                            </td>
                            <td>
                                <input type="number" class="form-control resteAProduire-input" name="resteAProduire[]" placeholder="Need - Relicat" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <p class="text-muted mt-2"><span class="text-danger">*</span> Required fields</p>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-success" id="addRow">
                        <i class="bi bi-plus"></i> Add a line
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveButton">Save</button>

                </div>
                <a href="/ligne?usine=<?= $idUsine ?>&ligne=<?= $idLigne ?>" class="btn btn-link text-muted mt-3">
                    <i class="bi bi-arrow-left"></i> Back to the previous page
                </a>
            </form>
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

        // Ajouter une nouvelle ligne
        addRowButton.addEventListener('click', () => {
            const lastRow = tableBody.querySelector('tr:last-child');
            let allFilled = true;

            if (lastRow) {
                const sebangoInput = lastRow.querySelector('.sebango-input');
                const referenceInput = lastRow.querySelector('.reference-input');
                const designationInput = lastRow.querySelector('.designation-input');
                const besoinInput = lastRow.querySelector('input[name="besoin[]"]');
                const relicatInput = lastRow.querySelector('input[name="relicat[]"]');
                const resteAProduireInput = lastRow.querySelector('.resteAProduire-input');

                allFilled = sebangoInput.value.trim().length === 4 &&
                    referenceInput.value.trim() !== '' &&
                    designationInput.value.trim() !== '' &&
                    besoinInput.value.trim() !== '' &&
                    relicatInput.value.trim() !== '' &&
                    resteAProduireInput.value.trim() !== '' &&
                    parseInt(besoinInput.value, 10) > 0 &&
                    parseInt(relicatInput.value, 10) >= 0;

                if (!allFilled) {
                    if (!referenceInput.value.trim() || !designationInput.value.trim()) {
                        alert(translations['errorSebango']);
                    } else if (parseInt(besoinInput.value, 10) <= 0) {
                        alert(translations['errorNeedPositive']);
                    } else if (parseInt(relicatInput.value, 10) < 0) {
                        alert(translations['errorRelicatNegative']);
                    } else if (!resteAProduireInput.value.trim()) {
                        alert(translations['errorRemainingEmpty']);
                    } else {
                        alert(translations['errorFillAllFields']);
                    }
                    return;
                }
            }

            // Ajouter une nouvelle ligne
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <input type="text" class="form-control sebango-input" name="sebango[]"
                           placeholder="ex : A350" pattern=".{4}" title="${translations['sebangoValidation']}" required>
                </td>
                <td>
                    <input type="text" class="form-control reference-input" name="reference[]" placeholder="${translations['reference']}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control designation-input" name="designation[]" placeholder="${translations['designation']}" readonly>
                </td>
                <td>
                    <input type="number" class="form-control" name="besoin[]" placeholder="ex : 600" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="relicat[]" placeholder="ex : 27" required>
                </td>
                <td>
                    <input type="number" class="form-control resteAProduire-input" name="resteAProduire[]" placeholder="${translations['remaining']}" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            tableBody.appendChild(newRow);
        });

        // Gestion automatique des colonnes Référence, Désignation et calcul de Reste à Produire
        tableBody.addEventListener('input', (event) => {
            if (event.target.classList.contains('sebango-input')) {
                const sebangoValue = event.target.value.trim().toUpperCase();
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

            if (event.target.name === 'besoin[]' || event.target.name === 'relicat[]') {
                const row = event.target.closest('tr');
                const besoinInput = row.querySelector('input[name="besoin[]"]');
                const relicatInput = row.querySelector('input[name="relicat[]"]');
                const resteAProduireInput = row.querySelector('.resteAProduire-input');

                const besoin = parseInt(besoinInput.value, 10) || 0;
                const relicat = parseInt(relicatInput.value, 10) || 0;

                if (besoin < relicat) {
                    alert(translations['errorNeedLessThanRelicat']);
                    resteAProduireInput.value = '';
                } else {
                    resteAProduireInput.value = besoin - relicat;
                }
            }
        });

        // Supprimer une ligne
        tableBody.addEventListener('click', (event) => {
            if (event.target.closest('.remove-row')) {
                event.target.closest('tr').remove();
            }
        });

        // Validation avant d'enregistrer
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
                const besoinInput = row.querySelector('input[name="besoin[]"]');
                const relicatInput = row.querySelector('input[name="relicat[]"]');
                const resteAProduireInput = row.querySelector('.resteAProduire-input');

                if (
                    referenceInput.value.trim() === '' ||
                    designationInput.value.trim() === '' ||
                    besoinInput.value.trim() === '' ||
                    relicatInput.value.trim() === '' ||
                    resteAProduireInput.value.trim() === '' ||
                    parseInt(besoinInput.value, 10) <= 0 ||
                    parseInt(relicatInput.value, 10) < 0
                ) {
                    valid = false;

                    if (!referenceInput.value.trim() || !designationInput.value.trim()) {
                        alert(translations['errorSebangoLine']);
                    } else if (parseInt(besoinInput.value, 10) <= 0) {
                        alert(translations['errorNeedPositive']);
                    } else if (parseInt(relicatInput.value, 10) < 0) {
                        alert(translations['errorRelicatNegative']);
                    } else if (!resteAProduireInput.value.trim()) {
                        alert(translations['errorRemainingEmpty']);
                    } else {
                        alert(translations['errorFillAllFields']);
                    }
                }
            });

            if (!valid) {
                event.preventDefault();
            }
        });
    });
</script>
