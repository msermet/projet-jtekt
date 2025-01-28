<?php

$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;

?>

<main class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-light mb-3">Line Management</h1>
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
                exit;
            } elseif (!$nomLigne) {
                header("Location: /ligne-introuvable");
                exit;
            }
            ?>
        </h2>
    </div>

    <div class="row gy-4">
        <!-- Add Pattern -->
        <div class="col-md-12">
            <div class="card shadow-lg border-0 mb-4 toggle-card" data-toggle="addPatternOptions" style="background-color: #1c1c1c; color: #f8f9fa; border-radius: 1rem;">
                <div class="card-body">
                    <div class="card-title text-center fw-bold">
                        <i class="bi bi-plus-circle fs-1 text-primary"></i>
                        <h5>Add Pattern</h5>
                    </div>
                </div>
                <div class="collapse" id="addPatternOptions">
                    <div class="row mt-3 pb-3 ps-3 pe-3">
                        <!-- Month Pattern -->
                        <div class="col-md-6">
                            <div class="card shadow-sm pattern-card add-pattern" id="add-pattern-mois" style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-month fs-1 text-primary"></i>
                                    <h6 class="card-title fw-bold mt-2">Month Pattern</h6>
                                    <p class="text-muted">Define patterns for the whole month.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Day Pattern -->
                        <div class="col-md-6">
                            <div class="card shadow-sm pattern-card add-pattern" id="add-pattern-jour" style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-day fs-1 text-primary"></i>
                                    <h6 class="card-title fw-bold mt-2">Day Pattern</h6>
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
            <div class="card shadow-lg border-0 mb-4 toggle-card" data-toggle="editPatternOptions" style="background-color: #1c1c1c; color: #f8f9fa; border-radius: 1rem;">
                <div class="card-body">
                    <div class="card-title text-center fw-bold">
                        <i class="bi bi-pencil-square fs-1 text-warning"></i>
                        <h5>Edit Pattern</h5>
                    </div>
                </div>
                <div class="collapse" id="editPatternOptions">
                    <div class="row mt-3 pb-3 ps-3 pe-3">
                        <!-- Month Pattern -->
                        <div class="col-md-6">
                            <div class="card shadow-sm pattern-card edit-pattern" id="edit-pattern-mois" style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-month fs-1 text-warning"></i>
                                    <h6 class="card-title fw-bold mt-2">Month Pattern</h6>
                                    <p class="text-muted">Modify existing monthly patterns.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Day Pattern -->
                        <div class="col-md-6">
                            <div class="card shadow-sm pattern-card edit-pattern" id="edit-pattern-jour" style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-day fs-1 text-warning"></i>
                                    <h6 class="card-title fw-bold mt-2">Day Pattern</h6>
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
            <a href="/ligne/ajouterproduit?usine=<?= $idUsine ?>&ligne=<?= $idLigne ?>" class="card shadow-lg border-0 text-decoration-none" style="background-color: #1c1c1c; color: #f8f9fa; border-radius: 1rem;">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-1 text-danger"></i>
                    <h5 class="card-title fw-bold">Add a Product</h5>
                </div>
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
        let actionType = null;

        // Gestion des cartes principales
        document.querySelectorAll('.toggle-card').forEach(card => {
            card.addEventListener('click', function () {
                const toggleId = this.getAttribute('data-toggle');
                const target = document.getElementById(toggleId);

                document.querySelectorAll('.collapse').forEach(collapse => {
                    if (collapse !== target) collapse.classList.remove('show');
                });

                target.classList.toggle('show');
            });
        });

        // Gestion des sous-cartes
        document.querySelectorAll('.pattern-card').forEach(card => {
            card.addEventListener('click', function (event) {
                event.stopPropagation();
                selectedPattern = this.id.includes('mois') ? 'mois' : 'jour';
                actionType = this.classList.contains('add-pattern') ? 'add' : 'edit';
                document.getElementById('patternModalLabel').textContent =
                    selectedPattern === 'mois'
                        ? `Select a month to ${actionType} a pattern:`
                        : `Select a day to ${actionType} a pattern:`;
                patternDate.type = selectedPattern === 'mois' ? 'month' : 'date';
                modal.show();
            });
        });

        // Validation du modal
        validateButton.addEventListener('click', function () {
            const dateValue = patternDate.value;
            if (!dateValue) {
                alert('Please select a valid date.');
                return;
            }

            const today = new Date();
            const selectedDate = new Date(dateValue + (patternDate.type === 'month' ? '-01' : ''));

            if (selectedPattern === 'mois' && selectedDate < new Date(today.getFullYear(), today.getMonth(), 1)) {
                alert('The selected month must be today or later.');
                return;
            }

            if (selectedPattern === 'jour' && selectedDate.setHours(0, 0, 0, 0) < today.setHours(0, 0, 0, 0)) {
                alert('The selected date must be today or later.');
                return;
            }

            const [year, month, day] = dateValue.split('-');
            const baseUrl =
                actionType === 'add'
                    ? (selectedPattern === 'mois' ? '/ligne/mois' : '/ligne/jour')
                    : (selectedPattern === 'mois' ? '/ligne/edit/mois' : '/ligne/edit/jour');
            const params = new URLSearchParams({
                usine: <?= json_encode($idUsine) ?>,
                ligne: <?= json_encode($idLigne) ?>,
                annee: year,
                mois: month,
                ...(selectedPattern === 'jour' && { jour: day }),
            });

            window.location.href = `${baseUrl}?${params}`;
        });
    });
</script>
