<?php

$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;

?>

<main class="container p-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-light">Choisissez un Pattern</h1>

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
                    <h5 class="card-title">Pattern Mois</h5>
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
                    <h5 class="card-title">Pattern Jour</h5>
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
                    <h5 class="card-title">Modifier Pattern</h5>
                </div>
            </a>
        </div>
    </div>

    <!-- Modale pour sélectionner le mois ou le jour -->
    <div class="modal fade" id="patternModal" tabindex="-1" aria-labelledby="patternModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patternModalLabel">Sélectionnez une date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="patternDate" class="form-label">Choisir :</label>
                    <input type="month" id="patternDate" class="form-control" placeholder="Sélectionnez une période">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="validatePattern">Valider</button>
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
            document.getElementById('patternModalLabel').textContent = 'Sélectionnez un mois pour le pattern :';
            patternDate.type = 'month';
            modal.show();
        });

        document.getElementById('pattern-jour').addEventListener('click', function () {
            selectedPattern = 'jour';
            document.getElementById('patternModalLabel').textContent = 'Sélectionnez un jour pour le pattern :';
            patternDate.type = 'date';
            modal.show();
        });

        validateButton.addEventListener('click', function () {
            const dateValue = patternDate.value;
            if (!dateValue) {
                alert('Veuillez sélectionner une date valide.');
                return;
            }

            const today = new Date();
            let selectedDate;

            if (selectedPattern === 'mois') {
                selectedDate = new Date(`${dateValue}-01`);
            } else {
                selectedDate = new Date(dateValue);
            }

            if (selectedDate < today) {
                alert("La date ou le mois sélectionné est antérieur à aujourd'hui.");
                return;
            }

            const baseUrl = selectedPattern === 'mois' ? '/pattern/mois' : '/pattern/jour';
            const ligneId = <?= json_encode($idLigne) ?>;
            const usineId = <?= json_encode($idUsine) ?>;

            window.location.href = `${baseUrl}?usine=${usineId}&ligne=${ligneId}&date=${dateValue}`;
        });
    });

</script>

