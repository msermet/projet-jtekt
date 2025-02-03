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
            exit;
        } elseif (!$nomLigne) {
            header("Location: /ligne-introuvable");
            exit;
        }
        ?>
    </h3>

    <h4 class="text-light pb-2 fst-italic"><?php echo $jour . "/" . $mois . "/" . $annee; ?></h4>

    <!-- Bouton pour coller les données -->
    <div class="d-flex justify-content-between mb-3">
        <h4><span class="text-light text-decoration-underline">Importer un tableau depuis SAP :</span>
            <button class="btn btn-warning ms-2 mb-1" id="pasteTable"><i class="bi bi-clipboard"></i> Coller</button>
        </h4>
    </div>

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

<!-- Pop-up d'aperçu des données collées -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aperçu des données à importer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="previewTable">
                        <thead class="table-dark">
                        <tr>
                            <th>Division</th>
                            <th>Lieu</th>
                            <th>Ligne Prod</th>
                            <th>Date</th>
                            <th>Poste</th>
                            <th>N° Sebango</th>
                            <th>Article</th>
                            <th>Désignation article</th>
                            <th>Quantité</th>
                            <th>Unité</th>
                            <th>Cpt prod</th>
                            <th>Qté PL</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="importData">Importer</button>
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

        // Collage et importation des données
        const pasteTableButton = document.getElementById('pasteTable');
        const previewTableBody = document.querySelector('#previewTable tbody');
        const importModal = new bootstrap.Modal(document.getElementById('importModal'));
        const importButton = document.getElementById('importData');

        // Fonction pour ajouter une ligne au tableau de saisie
        function addRow(sebango, besoin) {
            const produit = produits.find(p => p.sebango.toUpperCase() === sebango.toUpperCase() && p.ligne == idLigne);
            const reference = produit ? produit.reference : '';
            const designation = produit ? produit.designation : '';

            console.log("Ajout d'une ligne:", { sebango, reference, designation, besoin });

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
                <input type="number" class="form-control" name="besoin[]" value="${besoin}" required>
            </td>
            <td>
                <input type="number" class="form-control" name="relicat[]" value="0" required>
            </td>
            <td>
                <input type="number" class="form-control resteAProduire-input" name="resteAProduire[]" value="${besoin}" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

            tableBody.appendChild(newRow);
        }

        // Coller les données et afficher le pop-up
        pasteTableButton.addEventListener('click', async () => {
            try {
                const text = await navigator.clipboard.readText();
                const rows = text.trim().split(/\r?\n/).map(row => row.split(/\t/));

                if (rows.length < 1 || rows[0].length !== 12) {
                    alert("Format incorrect. Assurez-vous de copier 12 colonnes.");
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
                alert("Impossible d'accéder au presse-papiers.");
            }
        });

        // Importer les données dans le tableau de saisie
        importButton.addEventListener('click', () => {

            // Supprimer la première ligne existante
            const firstRow = tableBody.querySelector('tr');
            if (firstRow) {
                tableBody.removeChild(firstRow);
            }

            const rows = previewTableBody.querySelectorAll('tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length < 9) return; // Vérification du format

                const sebango = cells[5].textContent.trim(); // N° Sebango
                const besoin = parseInt(cells[8].textContent.trim(), 10) || 0; // Quantité

                if (sebango) {
                    console.log("Données extraites:", { sebango, besoin }); // Debugging
                    addRow(sebango, besoin);
                }
            });

            importModal.hide();
        });



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
