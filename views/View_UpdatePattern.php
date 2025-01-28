<?php

$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;

?>

<main class="container p-5"">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-light mb-3">Modify Pattern Selection</h1>
        <h2 class="fw-semibold text-secondary">
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
        </h2>
    </div>

    <div class="row justify-content-center gy-4 pt-4">
        <!-- Pattern Mois -->
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 pattern-card" id="pattern-mois"  style="cursor: pointer;">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="bi bi-calendar-month fs-1 text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold">Edit Month Pattern</h5>
                    <p class="text-muted">Modify the monthly pattern for this line.</p>
                </div>
            </div>
        </div>

        <!-- Pattern Jour -->
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 pattern-card" id="pattern-jour" style="cursor: pointer;">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="bi bi-calendar-day fs-1 text-success"></i>
                    </div>
                    <h5 class="card-title fw-bold">Edit Day Pattern</h5>
                    <p class="text-muted">Modify the daily pattern for this line.</p>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center pt-5">
            <a href="/ligne?usine=<?= $idUsine ?>&ligne=<?= $idLigne ?>" class="btn btn-link text-light">
                <i class="bi bi-arrow-left"></i> Back to the previous page
            </a>
        </div>
    </div>

    <!-- Modal pour sélectionner la date -->
    <div class="modal fade" id="patternModal" tabindex="-1" aria-labelledby="patternModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patternModalLabel">Select a date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="patternDate" class="form-label">Choose a date or month:</label>
                    <input type="month" id="patternDate" class="form-control" placeholder="Select a period">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="validatePattern">Validate</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('patternModal'));
        const patternDate = document.getElementById('patternDate');
        const validateButton = document.getElementById('validatePattern');

        let selectedPattern = null;

        // Listener pour le pattern mois
        document.getElementById('pattern-mois').addEventListener('click', function () {
            selectedPattern = 'mois';
            document.getElementById('patternModalLabel').textContent = 'Select a month for the pattern:';
            patternDate.type = 'month';
            modal.show();
        });

        // Listener pour le pattern jour
        document.getElementById('pattern-jour').addEventListener('click', function () {
            selectedPattern = 'jour';
            document.getElementById('patternModalLabel').textContent = 'Select a day for the pattern:';
            patternDate.type = 'date';
            modal.show();
        });

        // Validation lors du clic sur "Validate"
        validateButton.addEventListener('click', function () {
            const dateValue = patternDate.value;
            if (!dateValue) {
                alert('Please select a valid date.');
                return;
            }

            const today = new Date(); // Date actuelle
            const currentYear = today.getFullYear();
            const currentMonth = today.getMonth() + 1; // Mois actuel (0-indexé)
            const selectedDate = new Date(dateValue); // Date sélectionnée

            if (selectedPattern === 'mois') {
                const [year, month] = dateValue.split('-');
                const selectedMonthDate = new Date(year, month - 1); // Mois sélectionné

                // Vérifie si le mois sélectionné est avant le mois actuel
                if (
                    parseInt(year) < currentYear ||
                    (parseInt(year) === currentYear && parseInt(month) < currentMonth)
                ) {
                    alert('The selected month must be today or later.');
                    return;
                }
            } else if (selectedPattern === 'jour') {
                // Vérifie si la date sélectionnée est avant aujourd'hui
                if (selectedDate < today.setHours(0, 0, 0, 0)) {
                    alert('The selected date must be today or later.');
                    return;
                }
            }

            // Redirection en fonction du pattern sélectionné
            const [year, month, day] = dateValue.split('-');
            let baseUrl;
            if (selectedPattern === 'mois') {
                baseUrl = '/ligne/edit/mois';
                window.location.href = `${baseUrl}?usine=${<?= json_encode($idUsine) ?>}&ligne=${<?= json_encode($idLigne) ?>}&annee=${year}&mois=${month}`;
            } else if (selectedPattern === 'jour') {
                baseUrl = '/ligne/edit/jour';
                window.location.href = `${baseUrl}?usine=${<?= json_encode($idUsine) ?>}&ligne=${<?= json_encode($idLigne) ?>}&annee=${year}&mois=${month}&jour=${day}`;
            }
        });
    });
</script>
