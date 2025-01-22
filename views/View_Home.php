<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<div id="home">
    <h1><span class="badge text-bg-light">Application du futur</span></h1>
    <?php if (isset($_SESSION['id_user'])): ?>
        <p class="fw-semibold fs-5 badge text-bg-light">Connecté en tant que <span class="fw-bold prenom text-primary"><?= $_SESSION['prenom'] ?></span></p>
    <?php endif; ?>
</div>
