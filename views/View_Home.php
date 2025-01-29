<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($t)) {
    $translations = include 'lang.php';
    $lang = $_SESSION['lang'] ?? 'fr';
    $t = $translations[$lang];
}
?>

<div id="home">
    <h1><span class="badge text-bg-light"><?= $t['title'] ?></span></h1>
    <?php if (isset($_SESSION['id_user'])): ?>
        <p class="fw-semibold fs-5 badge text-bg-light"><?= $t['logged'] ?> <span class="fw-bold prenom text-primary"><?= $_SESSION['prenom'] ?></span></p>
    <?php endif; ?>
</div>
