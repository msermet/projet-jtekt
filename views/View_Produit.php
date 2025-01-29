<?php

if (!isset($t)) {
    $translations = include 'lang.php';
    $lang = $_SESSION['lang'] ?? 'fr';
    $t = $translations[$lang];
}

$ajoutReussi = '';
if (isset($_GET['ajout']) && $_GET['ajout'] === 'succeed') {
    $ajoutReussi = 'Ajout du produit réussi.';
}

$idUsine = $_GET['usine'] ?? null;
$idLigne = $_GET['ligne'] ?? null;

?>

<main class="container my-auto">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="form-container pt-4 pb-4" style="max-width: 500px; width: 100%;">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <!-- Titre principal -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold"><?= $t['addProduct'] ?></h2>
                        <h3 class="fw-bold">
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

//                            if (!$nomUsine) {
//                                header("Location: /usine-introuvable");
//                            } elseif (!$nomLigne) {
//                                header("Location: /ligne-introuvable");
//                            }
                            ?>
                        </h3>
                        <p class="text-muted small"><?= $t['infProduct'] ?></p>
                    </div>

                    <!-- Messages d'erreur et de succès -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($ajoutReussi)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <?php echo htmlspecialchars($ajoutReussi); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire -->
                    <form method="POST" action="">
                        <input type="hidden" name="ligne" value="<?php echo htmlspecialchars($idLigne); ?>">
                        <!-- Sebango -->
                        <div class="mb-3">
                            <label for="sebango" class="form-label fw-bold">Sebango</label>
                            <div class="input-group">
                                <span class="input-group-text bg-danger text-white">
                                    <i class="bi bi-arrow-right-circle"></i>
                                </span>
                                <input type="text" name="sebango" id="sebango" class="form-control" placeholder="ex : A350" value="<?php echo isset($_POST['sebango']) ? htmlspecialchars($_POST['sebango']) : ''; ?>" required>
                            </div>
                            <small class="text-muted"><?= $t['sizeSebango'] ?></small>
                        </div>

                        <!-- Article -->
                        <div class="mb-3">
                            <label for="article" class="form-label fw-bold"><?= $t['articleProd'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white">
                                    <i class="bi bi-arrow-right-circle"></i>
                                </span>
                                <input type="text" name="article" id="article" class="form-control" placeholder="ex : 6900004792" value="<?php echo isset($_POST['article']) ? htmlspecialchars($_POST['article']) : ''; ?>" required>
                            </div>
                            <small class="text-muted"><?= $t['articleRef'] ?></small>
                        </div>

                        <!-- Désignation -->
                        <div class="mb-3">
                            <label for="designation" class="form-label fw-bold"><?= $t['designation'] ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-warning text-dark">
                                    <i class="bi bi-arrow-right-circle"></i>
                                </span>
                                <input type="text" name="designation" id="designation" class="form-control" placeholder="ex : DAE G P84 DPLP D041 PHEV" value="<?php echo isset($_POST['designation']) ? htmlspecialchars($_POST['designation']) : ''; ?>" required>
                            </div>
                            <small class="text-muted"><?= $t['designationProd'] ?></small>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between pt-3">
                            <a href="/ligne?usine=<?= $idUsine ?>&ligne=<?= $idLigne ?>" class="btn btn-link text-muted mt-3">
                                <i class="bi bi-arrow-left"></i> <?= $t['back'] ?>
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> <?= $t['add'] ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

