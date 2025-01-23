<?php

$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;

?>

<div class="container p-5">
    <h1 class="text-center mb-4 text-light">Ajouter des données au Pattern Mois</h1>
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
    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="/enregistrer-pattern">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="patternTable">
                        <thead class="table-dark">
                        <tr>
                            <th>Sebango</th>
                            <th>Référence</th>
                            <th>Désignation</th>
                            <th>Quantité</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" class="form-control sebango-input" name="sebango[]"
                                       placeholder="ex : A350" pattern=".{4}" title="Sebango doit contenir exactement 4 caractères" required>
                            </td>
                            <td>
                                <input type="text" class="form-control reference-input" name="reference[]" placeholder="Référence" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control designation-input" name="designation[]" placeholder="Désignation" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="quantite[]" placeholder="ex : 561" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-success" id="addRow">
                        <i class="bi bi-plus"></i> Ajouter une ligne
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveButton">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const produits = <?php echo json_encode(array_map(function ($produit) {
            return [
                'sebango' => $produit->getSebango(),
                'reference' => $produit->getArticle(),
                'designation' => $produit->getDesignation(),
            ];
        }, $produits)); ?>;

        const addRowButton = document.getElementById('addRow');
        const saveButton = document.getElementById('saveButton');
        const tableBody = document.querySelector('#patternTable tbody');

        // Ajouter une nouvelle ligne
        addRowButton.addEventListener('click', () => {
            const sebangos = Array.from(document.querySelectorAll('.sebango-input')).map(input => input.value.trim().toUpperCase());

            const lastRow = tableBody.querySelector('tr:last-child');
            const sebangoInput = lastRow.querySelector('.sebango-input');
            const referenceInput = lastRow.querySelector('.reference-input');
            const designationInput = lastRow.querySelector('.designation-input');
            const quantiteInput = lastRow.querySelector('input[name="quantite[]"]');

            const allFilled = sebangoInput.value.trim().length === 4 &&
                referenceInput.value.trim() !== '' &&
                designationInput.value.trim() !== '' &&
                quantiteInput.value.trim() !== '';

            if (!allFilled) {
                if (!referenceInput.value.trim() === '' || designationInput.value.trim() === '') {
                    alert("Le Sebango saisi est incorrect ou n'existe pas. Veuillez vérifier.");
                } else {
                    alert("Tous les champs doivent être remplis avant d'ajouter une nouvelle ligne.");
                }
                return;
            }

            if (sebangos.filter(sebango => sebango === sebangoInput.value.trim().toUpperCase()).length > 1) {
                alert("Le Sebango saisi existe déjà dans le tableau. Veuillez en saisir un autre.");
                return;
            }

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <input type="text" class="form-control sebango-input" name="sebango[]"
                           placeholder="Sebango" pattern=".{4}" title="Sebango doit contenir exactement 4 caractères" required>
                </td>
                <td>
                    <input type="text" class="form-control reference-input" name="reference[]" placeholder="Référence" readonly>
                </td>
                <td>
                    <input type="text" class="form-control designation-input" name="designation[]" placeholder="Désignation" readonly>
                </td>
                <td>
                    <input type="number" class="form-control" name="quantite[]" placeholder="Quantité" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="bi bi-trash"></i>
                    </button>
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

                const produit = produits.find(p => p.sebango.toUpperCase() === sebangoValue);

                if (produit) {
                    referenceInput.value = produit.reference;
                    designationInput.value = produit.designation;
                } else {
                    referenceInput.value = '';
                    designationInput.value = '';
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
            const sebangos = Array.from(document.querySelectorAll('.sebango-input')).map(input => input.value.trim().toUpperCase());
            const rows = tableBody.querySelectorAll('tr');
            let valid = true;

            // Vérifier les duplications de Sebango
            if (new Set(sebangos).size !== sebangos.length) {
                valid = false;
                alert("Chaque Sebango dans le tableau doit être unique.");
                event.preventDefault();
                return;
            }

            rows.forEach(row => {
                const referenceInput = row.querySelector('.reference-input');
                const designationInput = row.querySelector('.designation-input');
                const quantiteInput = row.querySelector('input[name="quantite[]"]');

                if (referenceInput.value.trim() === '' || designationInput.value.trim() === '' || quantiteInput.value.trim() === '') {
                    valid = false;
                }
            });

            if (!valid) {
                event.preventDefault();
                alert("Tous les champs doivent être remplis correctement avant l'enregistrement.");
            }
        });
    });
</script>

