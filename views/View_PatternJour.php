<?php

// Récupération des paramètres de l'URL
$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;
$annee = $_GET['annee'] ?? null;
$mois = $_GET['mois'] ?? null;
$jour = $_GET['jour'] ?? null;

// Chargement des traductions si non définies
if (!isset($t)) {
    $translations = include 'lang.php';
    $lang = $_SESSION['lang'] ?? 'fr';
    $t = $translations[$lang];
}

// Message de succès pour l'ajout
$ajoutReussi = '';
if (isset($_GET['ajout']) && $_GET['ajout'] === 'succeed') {
    $ajoutReussi = $t['saveSuccess'];
}

?>

<div class="container p-5">
    <!-- Titre principal de la page -->
    <h1 class="text-center mb-4 text-light"><?= $t['addPatternJour'] ?></h1>
    <h3 class="fw-bold text-light">
        <?php
        // Affichage du nom de l'usine
        $nomUsine = null;
        foreach ($usines as $usine) {
            if ($usine->getId() == $idUsine) {
                $nomUsine = $usine->getNom();
                echo htmlspecialchars($nomUsine);
                break;
            }
        }

        // Affichage du nom de la ligne
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

    <!-- Affichage de la date -->
    <h4 class="text-light pb-2 fst-italic"><?php echo $jour . "/" . $mois . "/" . $annee; ?></h4>

    <!-- Bouton pour coller les données -->
    <div class="d-flex justify-content-between mb-3">
        <h4><span class="text-light text-decoration-underline"><?= $t['importTable'] ?></span>
            <button class="btn btn-warning ms-2 mb-1" id="pasteTable"><i class="bi bi-clipboard"></i> <?= $t['paste'] ?></button>
        </h4>
    </div>

    <!-- Affichage des messages d'erreur et de succès -->
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
            <!-- Formulaire pour ajouter des données -->
            <form method="POST" action="">
                <!-- Champs cachés pour les paramètres -->
                <input type="hidden" name="ligne" value="<?php echo htmlspecialchars($idLigne); ?>">
                <input type="hidden" name="annee" value="<?php echo htmlspecialchars($annee); ?>">
                <input type="hidden" name="mois" value="<?php echo htmlspecialchars($mois); ?>">
                <input type="hidden" name="jour" value="<?php echo htmlspecialchars($jour); ?>">
                <div class="table-responsive">
                    <!-- Tableau pour saisir les données -->
                    <table class="table table-bordered align-middle" id="patternTable">
                        <thead class="table-dark">
                        <tr>
                            <th>Sebango <span class="text-danger">*</span></th>
                            <th><?= $t['reference'] ?></th>
                            <th><?= $t['designation'] ?></th>
                            <th><?= $t['need'] ?> <span class="text-danger">*</span></th>
                            <th><?= $t['relicat'] ?> <span class="text-danger">*</span></th>
                            <th><?= $t['remainingToProduce'] ?></th>
                            <th><?= $t['action'] ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <!-- Champ de saisie pour le Sebango -->
                                <input type="text" class="form-control sebango-input" name="sebango[]"
                                       placeholder="<?= $t['example'] ?> A350" pattern=".{4}" title="Sebango must contain exactly 4 characters" required>
                            </td>
                            <td>
                                <!-- Champ de saisie pour la référence, en lecture seule -->
                                <input type="text" class="form-control reference-input" name="reference[]" placeholder="<?= $t['reference'] ?>" readonly>
                            </td>
                            <td>
                                <!-- Champ de saisie pour la désignation, en lecture seule -->
                                <input type="text" class="form-control designation-input" name="designation[]" placeholder="<?= $t['designation'] ?>" readonly>
                            </td>
                            <td>
                                <!-- Champ de saisie pour le besoin -->
                                <input type="number" class="form-control" name="besoin[]" placeholder="<?= $t['example'] ?> 600" required>
                            </td>
                            <td>
                                <!-- Champ de saisie pour le reliquat -->
                                <input type="number" class="form-control" name="relicat[]" placeholder="<?= $t['example'] ?> 27" required>
                            </td>
                            <td>
                                <!-- Champ de saisie pour le reste à produire, en lecture seule -->
                                <input type="number" class="form-control resteAProduire-input" name="resteAProduire[]" placeholder="<?= $t['designation'] ?> - <?= $t['relicat'] ?>" readonly>
                            </td>
                            <td class="text-center">
                                <!-- Bouton pour supprimer une ligne -->
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
                    <!-- Bouton pour ajouter une nouvelle ligne -->
                    <button type="button" class="btn btn-success" id="addRow">
                        <i class="bi bi-plus"></i> <?= $t['addLine'] ?>
                    </button>
                    <!-- Bouton pour enregistrer les données -->
                    <button type="submit" class="btn btn-primary" id="saveButton"><?= $t['save'] ?></button>
                </div>
                <!-- Lien pour revenir à la page précédente -->
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
                    <!-- Tableau d'aperçu des données collées -->
                    <table class="table table-bordered" id="previewTable">
                        <thead class="table-dark">
                        <tr>
                            <th><?= $t['division'] ?></th>
                            <th><?= $t['location'] ?></th>
                            <th><?= $t['prodLine'] ?></th>
                            <th><?= $t['date'] ?></th>
                            <th><?= $t['shift'] ?></th>
                            <th><?= $t['sebangoNumber'] ?></th>
                            <th><?= $t['articleProd'] ?></th>
                            <th><?= $t['articleDesignation'] ?></th>
                            <th><?= $t['quantity'] ?></th>
                            <th><?= $t['unit'] ?></th>
                            <th><?= $t['prodCounter'] ?></th>
                            <th><?= $t['qtyPL'] ?></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Boutons du pop-up -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $t['cancel'] ?></button>
                <button type="button" class="btn btn-success" id="importData"><?= $t['import'] ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Chargement des traductions et des produits depuis PHP
        const translations = <?= json_encode($t); ?>;
        const produits = <?= json_encode(array_map(function ($produit) {
            return [
                'sebango' => $produit->getSebango(),
                'reference' => $produit->getArticle(),
                'designation' => $produit->getDesignation(),
                'ligne' => $produit->getLigne(),
            ];
        }, $produits)); ?>;

        // ID de la ligne actuelle
        const idLigne = <?= json_encode($idLigne); ?>;

        // Références aux éléments du DOM
        const addRowButton = document.getElementById('addRow');
        const saveButton = document.getElementById('saveButton');
        const tableBody = document.querySelector('#patternTable tbody');

        // Références pour le collage et l'importation des données
        const pasteTableButton = document.getElementById('pasteTable');
        const previewTableBody = document.querySelector('#previewTable tbody');
        const importModal = new bootstrap.Modal(document.getElementById('importModal'));
        const importButton = document.getElementById('importData');

        // Fonction pour ajouter une ligne au tableau de saisie
        function addRow(sebango, besoin) {
            // Recherche du produit correspondant au Sebango
            const produit = produits.find(p => p.sebango.toUpperCase() === sebango.toUpperCase() && p.ligne == idLigne);
            const reference = produit ? produit.reference : '';
            const designation = produit ? produit.designation : '';

            // Création d'une nouvelle ligne de tableau
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

            // Ajout de la nouvelle ligne au tableau
            tableBody.appendChild(newRow);
        }

        // Gestion du clic sur le bouton de collage des données
        pasteTableButton.addEventListener('click', async () => {
            try {
                // Lecture des données depuis le presse-papiers
                const text = await navigator.clipboard.readText();
                const rows = text.trim().split(/\r?\n/).map(row => row.split(/\t/));

                // Vérification du format des données collées
                if (rows.length < 1 || rows[0].length !== 12) {
                    alert("<?= $t['errorPasteFormat'] ?>");
                    return;
                }

                // Affichage des données collées dans le tableau d'aperçu
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

                // Affichage du modal d'importation
                importModal.show();
            } catch (error) {
                alert("<?= $t['errorClipboard'] ?>");
            }
        });

        // Gestion du clic sur le bouton d'importation des données
        importButton.addEventListener('click', () => {
            // Suppression de la première ligne existante
            const firstRow = tableBody.querySelector('tr');
            if (firstRow) {
                tableBody.removeChild(firstRow);
            }

            // Ajout des données collées au tableau de saisie
            const rows = previewTableBody.querySelectorAll('tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length < 9) return; // Vérification du format

                const sebango = cells[5].textContent.trim(); // N° Sebango
                const besoin = parseInt(cells[8].textContent.trim(), 10) || 0; // Quantité

                if (sebango) {
                    addRow(sebango, besoin);
                }
            });

            // Fermeture du modal d'importation
            importModal.hide();
        });

        // Gestion du clic sur le bouton pour ajouter une nouvelle ligne
        addRowButton.addEventListener('click', () => {
            const lastRow = tableBody.querySelector('tr:last-child');
            let allFilled = true;

            // Vérification que la dernière ligne est remplie correctement
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

            // Création d'une nouvelle ligne de tableau
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <input type="text" class="form-control sebango-input" name="sebango[]"
                           placeholder="<?= $t['example'] ?> A350" pattern=".{4}" title="${translations['sebangoValidation']}" required>
                </td>
                <td>
                    <input type="text" class="form-control reference-input" name="reference[]" placeholder="${translations['reference']}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control designation-input" name="designation[]" placeholder="${translations['designation']}" readonly>
                </td>
                <td>
                    <input type="number" class="form-control" name="besoin[]" placeholder="<?= $t['example'] ?> 600" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="relicat[]" placeholder="<?= $t['example'] ?> 27" required>
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
            // Ajout de la nouvelle ligne au tableau
            tableBody.appendChild(newRow);
        });

        // Gestion automatique des colonnes Référence, Désignation et calcul de Reste à Produire
        tableBody.addEventListener('input', (event) => {
            if (event.target.classList.contains('sebango-input')) {
                const sebangoValue = event.target.value.trim().toUpperCase();
                event.target.value = sebangoValue;
                const referenceInput = event.target.closest('tr').querySelector('.reference-input');
                const designationInput = event.target.closest('tr').querySelector('.designation-input');

                // Recherche du produit correspondant au Sebango
                const produit = produits.find(p =>
                    p.sebango.toUpperCase() === sebangoValue && p.ligne == idLigne
                );

                // Mise à jour des champs Référence et Désignation
                if (produit) {
                    referenceInput.value = produit.reference;
                    designationInput.value = produit.designation;
                } else {
                    referenceInput.value = '';
                    designationInput.value = '';
                }
            }

            // Calcul du Reste à Produire
            if (event.target.name === 'besoin[]' || event.target.name === 'relicat[]') {
                const row = event.target.closest('tr');
                const besoinInput = row.querySelector('input[name="besoin[]"]');
                const relicatInput = row.querySelector('input[name="relicat[]"]');
                const resteAProduireInput = row.querySelector('.resteAProduire-input');

                const besoin = parseInt(besoinInput.value, 10) || 0;
                const relicat = parseInt(relicatInput.value, 10) || 0;

                // Vérification que le besoin est supérieur ou égal au reliquat
                if (besoin < relicat) {
                    alert(translations['errorNeedLessThanRelicat']);
                    resteAProduireInput.value = '';
                } else {
                    resteAProduireInput.value = besoin - relicat;
                }
            }
        });

        // Gestion du clic sur le bouton pour supprimer une ligne
        tableBody.addEventListener('click', (event) => {
            if (event.target.closest('.remove-row')) {
                event.target.closest('tr').remove();
            }
        });

        // Validation avant d'enregistrer
        saveButton.addEventListener('click', (event) => {
            const rows = tableBody.querySelectorAll('tr');
            let valid = true;

            // Vérification qu'il y a au moins une ligne dans le tableau
            if (rows.length === 0) {
                event.preventDefault();
                alert(translations['errorAddLineBeforeSave']);
                return;
            }

            // Vérification que toutes les lignes sont correctement remplies
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

            // Empêcher l'envoi du formulaire si des erreurs sont détectées
            if (!valid) {
                event.preventDefault();
            }
        });
    });
</script>