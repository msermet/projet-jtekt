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
            <a href="/pattern/mois/usine=<?= $idUsine ?>&ligne=<?= $idLigne ?>" class="card shadow-sm text-decoration-none pattern-card">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="bi bi-calendar-month fs-1 text-primary"></i>
                    </div>
                    <h5 class="card-title">Pattern Mois</h5>
                    <p class="card-text text-muted">Configurez un pattern mensuel adapté à vos besoins.</p>
                </div>
            </a>
        </div>

        <!-- Pattern Jour -->
        <div class="col-md-6">
            <a href="/pattern/jour/usine=<?= $idUsine ?>&ligne=<?= $idLigne ?>" class="card shadow-sm text-decoration-none pattern-card">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="bi bi-calendar-day fs-1 text-success"></i>
                    </div>
                    <h5 class="card-title">Pattern Jour</h5>
                    <p class="card-text text-muted">Créez un pattern quotidien pour une gestion précise.</p>
                </div>
            </a>
        </div>

        <!-- Modifier Pattern -->
        <div class="col-md-12 mt-4">
            <a href="#" class="card shadow-sm text-decoration-none pattern-card">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="bi bi-pencil-square fs-1 text-warning"></i>
                    </div>
                    <h5 class="card-title">Modifier Pattern</h5>
                    <p class="card-text text-muted">Ajustez ou personnalisez un pattern existant.</p>
                </div>
            </a>
        </div>
    </div>

</main>
