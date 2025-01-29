<?php

$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;

if (!isset($t)) {
    $translations = include 'lang.php';
    $lang = $_SESSION['lang'] ?? 'fr';
    $t = $translations[$lang];
}

?>

<main class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-light mb-3"><?= $t['lineManagement'] ?></h1>
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
                        <h5><?= $t['addPattern'] ?></h5>
                    </div>
                </div>
                <div class="collapse" id="addPatternOptions">
                    <div class="row mt-3 pb-3 ps-3 pe-3">
                        <!-- Month Pattern -->
                        <div class="col-md-6">
                            <div class="card shadow-sm pattern-card add-pattern" id="add-pattern-mois" style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-month fs-1 text-primary"></i>
                                    <h6 class="card-title fw-bold mt-2"><?= $t['patternMois'] ?></h6>
                                </div>
                            </div>
                        </div>
                        <!-- Day Pattern -->
                        <div class="col-md-6">
                            <div class="card shadow-sm pattern-card add-pattern" id="add-pattern-jour" style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-day fs-1 text-primary"></i>
                                    <h6 class="card-title fw-bold mt-2"><?= $t['patternJour'] ?></h6>
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
                        <h5><?= $t['editPattern'] ?></h5>
                    </div>
                </div>
                <div class="collapse" id="editPatternOptions">
                    <div class="row mt-3 pb-3 ps-3 pe-3">
                        <!-- Month Pattern -->
                        <div class="col-md-6">
                            <div class="card shadow-sm pattern-card edit-pattern" id="edit-pattern-mois" style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-month fs-1 text-warning"></i>
                                    <h6 class="card-title fw-bold mt-2"><?= $t['patternMois'] ?></h6>
                                </div>
                            </div>
                        </div>
                        <!-- Day Pattern -->
                        <div class="col-md-6">
                            <div class="card shadow-sm pattern-card edit-pattern" id="edit-pattern-jour" style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-day fs-1 text-warning"></i>
                                    <h6 class="card-title fw-bold mt-2"><?= $t['patternJour'] ?></h6>
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
                    <h5 class="card-title fw-bold"><?= $t['addProduct'] ?></h5>
                </div>
            </a>
        </div>
    </div>

    <!-- Modal de sélection de la date -->
    <div class="modal fade" id="patternModal" tabindex="-1" aria-labelledby="patternModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patternModalLabel"><?= $t['selectDate'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= $t['close'] ?>"></button>
                </div>
                <div class="modal-body">
                    <label for="patternDate" class="form-label"><?= $t['chooseDate'] ?></label>
                    <input type="month" id="patternDate" class="form-control" placeholder="<?= $t['selectPeriod'] ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="validatePattern"><?= $t['valider'] ?></button>
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

        // Obtenir les traductions dynamiques depuis PHP
        const translations = {
            selectMonth: "<?= $t['selectMonth'] ?>",
            selectDay: "<?= $t['selectDay'] ?>",
            validDate: "<?= $t['validDate'] ?>",
            monthLater: "<?= $t['monthLater'] ?>",
            dayLater: "<?= $t['dayLater'] ?>",
            add: "<?= $t['addAction'] ?>",
            edit: "<?= $t['editAction'] ?>"
        };

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
                const isAdding = this.classList.contains('add-pattern');
                actionType = isAdding ? translations.add : translations.edit;

                document.getElementById('patternModalLabel').textContent =
                    selectedPattern === 'mois'
                        ? translations.selectMonth.replace('{action}', actionType)
                        : translations.selectDay.replace('{action}', actionType);

                patternDate.type = selectedPattern === 'mois' ? 'month' : 'date';
                modal.show();
            });
        });

        // Validation du modal
        validateButton.addEventListener('click', function () {
            const dateValue = patternDate.value;
            if (!dateValue) {
                alert(translations.validDate);
                return;
            }

            const today = new Date();
            const selectedDate = new Date(dateValue + (patternDate.type === 'month' ? '-01' : ''));

            if (selectedPattern === 'mois' && selectedDate < new Date(today.getFullYear(), today.getMonth(), 1)) {
                alert(translations.monthLater);
                return;
            }

            if (selectedPattern === 'jour' && selectedDate.setHours(0, 0, 0, 0) < today.setHours(0, 0, 0, 0)) {
                alert(translations.dayLater);
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
