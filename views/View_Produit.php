<?php

$ajoutReussi = '';
if (isset($_GET['ajout']) && $_GET['ajout'] === 'succeed') {
    $ajoutReussi = 'Ajout du produit réussi.';
}

?>

<main class="container my-auto">
    <div class="form-container pt-5 pb-5">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Ajouter un produit</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($ajoutReussi)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($ajoutReussi); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="sebango" class="form-label">Sebango : <span class="fw-bold">*</span></label>
                        <input type="text" name="sebango" id="sebango" class="form-control" placeholder="ex : A350" value="<?php echo isset($_POST['sebango']) ? htmlspecialchars($_POST['sebango']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="article" class="form-label">Article : <span class="fw-bold">*</span></label>
                        <input type="text" name="article" id="article" class="form-control" placeholder="ex : 6900004792" value="<?php echo isset($_POST['article']) ? htmlspecialchars($_POST['article']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="designation" class="form-label">Désignation : <span class="fw-bold">*</span></label>
                        <input type="text" name="designation" id="designation" class="form-control" placeholder="ex : DAE G P84 DPLP D041 PHEV" value="<?php echo isset($_POST['designation']) ? htmlspecialchars($_POST['designation']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <p><span class="fw-bold">*</span> Champs obligatoires</p>
                    </div>

                    <div class="d-grid pt-3">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>