<?php

$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;
$annee = $_GET['annee'] ?? null;
$mois = $_GET['mois'] ?? null;

$ajoutReussi = '';
if (isset($_GET['ajout']) && $_GET['ajout'] === 'succeed') {
    $ajoutReussi = 'Save successful.';
}

// Filtrer les enregistrements de PatternMois
$filteredPatterns = array_filter($patternMois, function ($pattern) use ($idLigne, $annee, $mois) {
    return $pattern->getAnnee() == $annee && $pattern->getMois() == $mois;
});

?>
<div class="container p-5">
    <h1 class="text-center mb-4 text-light">Edit Monthly Pattern</h1>
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
    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="ligne" value="<?php echo htmlspecialchars($idLigne); ?>">
                <input type="hidden" name="annee" value="<?php echo htmlspecialchars($annee); ?>">
                <input type="hidden" name="mois" value="<?php echo htmlspecialchars($mois); ?>">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="patternTable">
                        <thead class="table-dark">
                        <tr>
                            <th>Sebango <span class="text-danger">*</span></th>
                            <th>Reference</th>
                            <th>Designation</th>
                            <th>Quantity <span class="text-danger">*</span></th>
                            <th>Delete</th>
                            <th>Move</th>
                        </tr>
                        </thead>
                        <tbody id="sortableTable">
                        <?php foreach ($filteredPatterns as $pattern): ?>
                            <tr>
                                <td>
                                    <input type="text" class="form-control sebango-input" name="sebango[]"
                                           value="<?php echo htmlspecialchars($pattern->getSebango()); ?>" readonly>
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
                                           value="<?php echo htmlspecialchars($pattern->getQuantite()); ?>" required>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const produits = <?php echo json_encode(array_map(function ($produit) {
            return [
                'sebango' => $produit->getSebango(),
                'reference' => $produit->getArticle(),
                'designation' => $produit->getDesignation(),
            ];
        }, $produits)); ?>;

        const tableBody = document.querySelector('#sortableTable');

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
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
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
                    <input type="number" class="form-control" name="quantite[]" placeholder="ex : 561" required>
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