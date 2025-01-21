<?php
$inscriptionMessage = '';
if (isset($_GET['inscription']) && $_GET['inscription'] === 'succeed') {
    $inscriptionMessage = 'Inscription réussie';
}

$connexionMessage = '';
if (isset($_GET['erreur']) && $_GET['erreur'] === 'connexion') {
    $connexionMessage = 'Vous devez être connecté pour accéder à cette page.';
}

?>

<main class="container my-auto">
    <div class="form-container pt-5">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Connexion</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($inscriptionMessage)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($inscriptionMessage); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($connexionMessage)): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($connexionMessage); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email : <span class="fw-bold">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="ex : dupondjean@gmail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe : <span class="fw-bold">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="ex : secret" required>
                    </div>

                    <div class="mb-3">
                        <p><span class="fw-bold">*</span> Champs obligatoires</p>
                    </div>

                    <div class="d-grid pt-3">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>
                <div class="pt-3">
                    <p><span class="me-1">Vous ne possédez pas de compte ?</span><a href="/creationcompte" class="link-underline-info text-black fw-semibold mt-5">Inscrivez vous !</a></p>
                </div>
            </div>
        </div>
    </div>
</main>