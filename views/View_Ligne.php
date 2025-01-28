<?php

$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;

?>

<main class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-light mb-3">Pattern Management</h1>
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

    <div class="row gy-4 ps-5 pe-5">
        <!-- Add Pattern -->
        <div class="col-md-12">
            <div class="card shadow-lg border-0 mb-4" style="background-color: #1c1c1c; color: #f8f9fa;border-radius: 1rem;">
                <div class="card-body">
                    <h5 class="card-title text-center fw-bold">Add Pattern</h5>
                    <div class="row">
                        <!-- Month Pattern -->
                        <div class="col-md-6">
                            <div class="card shadow-sm pattern-card" id="pattern-mois" style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <div class="icon mb-3">
                                        <i class="bi bi-calendar-month fs-1 text-primary"></i>
                                    </div>
                                    <h6 class="card-title fw-bold">Month Pattern</h6>
                                    <p class="text-muted">Define patterns for the whole month.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Day Pattern -->
                        <div class="col-md-6">
                            <div class="card shadow-sm pattern-card" id="pattern-jour" style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <div class="icon mb-3">
                                        <i class="bi bi-calendar-day fs-1 text-success"></i>
                                    </div>
                                    <h6 class="card-title fw-bold">Day Pattern</h6>
                                    <p class="text-muted">Define patterns for specific days.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Pattern -->
        <div class="col-md-12">
            <div class="card shadow-lg border-0 mb-4" style="background-color: #1c1c1c; color: #f8f9fa;border-radius: 1rem;">
                <div class="card-body">

                    <h5 class="card-title text-center fw-bold"><i class="bi bi-pencil-square fs-1 text-warning"></i></h5>
                    <h5 class="card-title text-center fw-bold">Edit Pattern</h5>
                    <div class="row pt-3">
                        <!-- Month Pattern -->
                        <div class="col-md-6">
                            <div class="card shadow-sm pattern-card" id="edit-pattern-mois" style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <div class="icon mb-3">
                                        <i class="bi bi-calendar-month fs-1 text-warning"></i>
                                    </div>
                                    <h6 class="card-title fw-bold">Month Pattern</h6>
                                    <p class="text-muted">Modify existing monthly patterns.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Day Pattern -->
                        <div class="col-md-6">
                            <div class="card shadow-sm pattern-card" id="edit-pattern-jour" style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <div class="icon mb-3">
                                        <i class="bi bi-calendar-day fs-1 text-info"></i>
                                    </div>
                                    <h6 class="card-title fw-bold">Day Pattern</h6>
                                    <p class="text-muted">Modify existing daily patterns.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add a Product -->
        <div class="col-md-12">
            <a href="/ligne/ajouterproduit?usine=<?= $idUsine ?>&ligne=<?= $idLigne ?>" class="card shadow-lg border-0 text-decoration-none" style="border-radius: 1rem; background-color: #1c1c1c; color: #f8f9fa;">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="bi bi-plus-circle fs-1 text-danger"></i>
                    </div>
                    <h5 class="card-title fw-bold">Add a Product</h5>
                    <p class="text-muted">Include new products in your patterns.</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="patternModal" tabindex="-1" aria-labelledby="patternModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patternModalLabel">Select a date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="patternDate" class="form-label">Choose a date or month:</label>
                    <input type="month" id="patternDate" class="form-control">
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
        let actionType = null;

        function openModal(type, action) {
            selectedPattern = type;
            actionType = action;
            document.getElementById('patternModalLabel').textContent =
                type === 'mois' ? Select a month to ${action} a pattern: : Select a day to ${action} a pattern:;
            patternDate.type = type === 'mois' ? 'month' : 'date';
            modal.show();
        }

        document.getElementById('pattern-mois').addEventListener('click', () => openModal('mois', 'add'));
        document.getElementById('pattern-jour').addEventListener('click', () => openModal('jour', 'add'));
        document.getElementById('edit-pattern-mois').addEventListener('click', () => openModal('mois', 'edit'));
        document.getElementById('edit-pattern-jour').addEventListener('click', () => openModal('jour', 'edit'));

        validateButton.addEventListener('click', function () {
            const dateValue = patternDate.value;

            if (!dateValue) {
                alert('Please select a valid date.');
                return;
            }

            const today = new Date();
            const selectedDate = new Date(dateValue + (patternDate.type === 'month' ? '-01' : ''));

            // Comparaison des mois et années pour le type "month"
            if (patternDate.type === 'month') {
                const currentYear = today.getFullYear();
                const currentMonth = today.getMonth() + 1; // Les mois sont indexés de 0 à 11
                const [selectedYear, selectedMonth] = dateValue.split('-').map(Number);

                if (selectedYear < currentYear || (selectedYear === currentYear && selectedMonth < currentMonth)) {
                    alert('The selected month must be the current month or later.');
                    return;
                }
            }

            // Comparaison pour le type "date"
            if (patternDate.type === 'date' && selectedDate < new Date(today.getFullYear(), today.getMonth(), today.getDate())) {
                alert('The selected date must be today or later.');
                return;
            }

            const [year, month, day] = dateValue.split('-');
            const baseUrl = actionType === 'add'
                ? (selectedPattern === 'mois' ? '/ligne/mois' : '/ligne/jour')
                : (selectedPattern === 'mois' ? '/ligne/edit/mois' : '/ligne/edit/jour');
            const params = new URLSearchParams({
                usine: <?= json_encode($idUsine) ?>,
                ligne: <?= json_encode($idLigne) ?>,
                annee: year,
                mois: month,
                ...(selectedPattern === 'jour' && { jour: day }),
            });

            window.location.href = ${baseUrl}?${params.toString()};
        });
    });
</script>