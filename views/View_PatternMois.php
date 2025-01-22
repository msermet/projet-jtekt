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
                                <input type="text" class="form-control" name="sebango[]" placeholder="ex : A350" required>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="reference[]" placeholder="Référence" required>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="designation[]" placeholder="Désignation" required>
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
                    <button type="submit" class="btn btn-primary" id="saveButton" style="display: block;">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS & Icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>

<!-- Script pour la gestion du tableau -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const addRowButton = document.getElementById('addRow');
        const tableBody = document.querySelector('#patternTable tbody');

        // Ajouter une nouvelle ligne au tableau
        addRowButton.addEventListener('click', () => {
            // Vérifier si la dernière ligne a des données remplies
            const lastRowInputs = tableBody.querySelectorAll('tr:last-child input');
            const allFilled = Array.from(lastRowInputs).every(input => input.value.trim() !== '');

            if (!allFilled) {
                alert('Veuillez remplir les données de la dernière ligne avant d’ajouter une nouvelle ligne.');
                return;
            }

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                    <td>
                        <input type="text" class="form-control" name="sebango[]" placeholder="Sebango" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="reference[]" placeholder="Référence" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="designation[]" placeholder="Désignation" required>
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

        // Supprimer une ligne du tableau
        tableBody.addEventListener('click', (event) => {
            if (event.target.closest('.remove-row')) {
                const row = event.target.closest('tr');
                row.remove();
            }
        });
    });
</script>