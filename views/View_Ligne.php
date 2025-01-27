<?php

$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;

?>

<main class="container p-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-light">
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
        </h1>
    </div>

    <div class="row justify-content-center">
        <!-- Pattern Mois -->
        <div class="col-md-6">
            <div class="card shadow-sm text-decoration-none pattern-card" id="pattern-mois">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="bi bi-calendar-month fs-1 text-primary"></i>
                    </div>
                    <h5 class="card-title">Month Pattern</h5>
                </div>
            </div>
        </div>

        <!-- Pattern Jour -->
        <div class="col-md-6">
            <div class="card shadow-sm text-decoration-none pattern-card" id="pattern-jour">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="bi bi-calendar-day fs-1 text-success"></i>
                    </div>
                    <h5 class="card-title">Day Pattern</h5>
                </div>
            </div>
        </div>

        <!-- Modifier Pattern -->
        <div class="col-md-12 mt-4">
            <a href="#" class="card shadow-sm text-decoration-none pattern-card">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="bi bi-pencil-square fs-1 text-warning"></i>
                    </div>
                    <h5 class="card-title">Edit Pattern</h5>
                </div>
            </a>
        </div>

        <!-- Add a product -->
        <div class="col-md-4 mt-4"></div>
        <div class="col-md-4 mt-4">
            <a href="/ligne/ajouterproduit?usine=<?= $idUsine ?>&ligne=<?= $idLigne ?>" class="card shadow-sm text-decoration-none pattern-card">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="bi bi-plus-circle fs-1 text-danger"></i>
                    </div>
                    <h5 class="card-title">Add a product</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4 mt-4"></div>
    </div>

    <!-- Modale pour sélectionner le mois ou le jour -->
    <div class="modal fade" id="patternModal" tabindex="-1" aria-labelledby="patternModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patternModalLabel">Select a date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="patternDate" class="form-label">Choose :</label>
                    <input type="month" id="patternDate" class="form-control" placeholder="Sélectionnez une période">
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

        document.getElementById('pattern-mois').addEventListener('click', function () {
            selectedPattern = 'mois';
            document.getElementById('patternModalLabel').textContent = 'Select a month for the pattern:';
            patternDate.type = 'month';
            modal.show();
        });

        document.getElementById('pattern-jour').addEventListener('click', function () {
            selectedPattern = 'jour';
            document.getElementById('patternModalLabel').textContent = 'Select a day for the pattern:';
            patternDate.type = 'date';
            modal.show();
        });

        validateButton.addEventListener('click', function () {
            const dateValue = patternDate.value;
            if (!dateValue) {
                alert('Please select a valid date.');
                return;
            }

            const today = new Date();
            const currentYear = today.getFullYear();
            const currentMonth = today.getMonth() + 1; // Mois actuel (0-indexé)

            let selectedDate;
            let year, month, day;

            if (selectedPattern === 'mois') {
                [year, month] = dateValue.split('-');
                selectedDate = new Date(`${year}-${month}-01`);
            } else {
                const parts = dateValue.split('-');
                year = parts[0];
                month = parts[1];
                day = parts[2];
                selectedDate = new Date(`${year}-${month}-${day}`);
            }

            if (
                selectedDate < new Date(today.getFullYear(), today.getMonth(), today.getDate()) &&
                !(selectedPattern === 'mois' && parseInt(year) === currentYear && parseInt(month) === currentMonth)
            ) {
                alert("The selected date or month is earlier than today.");
                return;
            }

            let baseUrl;
            if (selectedPattern === 'mois') {
                baseUrl = '/ligne/mois';
                window.location.href = `${baseUrl}?usine=${<?= json_encode($idUsine) ?>}&ligne=${<?= json_encode($idLigne) ?>}&annee=${year}&mois=${month}`;
            } else if (selectedPattern === 'jour') {
                baseUrl = '/ligne/jour';
                window.location.href = `${baseUrl}?usine=${<?= json_encode($idUsine) ?>}&ligne=${<?= json_encode($idLigne) ?>}&annee=${year}&mois=${month}&jour=${day}`;
            }
        });
    });
</script>
