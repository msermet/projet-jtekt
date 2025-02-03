<?php

// Démarre une nouvelle session ou reprend une session existante
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifie si la variable $t n'est pas définie
if (!isset($t)) {
    // Inclut le fichier de traduction
    $translations = include 'lang.php';
    // Définit la langue par défaut à 'fr' si elle n'est pas définie dans la session
    $lang = $_SESSION['lang'] ?? 'fr';
    // Récupère les traductions pour la langue sélectionnée
    $t = $translations[$lang];
}
?>

<div id="home">
    <!-- Affiche le titre principal de la page avec une badge -->
    <h1><span class="badge text-bg-light"><?= $t['title'] ?></span></h1>
    <!-- Vérifie si l'utilisateur est connecté -->
    <?php if (isset($_SESSION['id_user'])): ?>
        <!-- Affiche un message de bienvenue avec le prénom de l'utilisateur connecté -->
        <p class="fw-semibold fs-5 badge text-bg-light"><?= $t['logged'] ?> <span class="fw-bold prenom text-primary"><?= $_SESSION['prenom'] ?></span></p>
    <?php endif; ?>
</div>