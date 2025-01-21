<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>test</title>
</head>

<body class="layout">
<aside id="sidebar" class="sidebar">
    <button id="toggleButton" class="toggle-button">
        <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
            <path fill-rule="evenodd" d="M3 12a1 1 0 011-1h16a1 1 0 110 2H4a1 1 0 01-1-1zM3 6a1 1 0 011-1h16a1 1 0 110 2H4a1 1 0 01-1-1zM3 18a1 1 0 011-1h16a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
        </svg>
    </button>
    <div class="logo-section">
        <a href="/" class="home-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
            </svg>
            <span class="link-text">Home</span>
        </a>
    </div>
    <nav class="nav-links">
        <?php if (isset($_SESSION['id_user'])): ?>
            <a href="/ajouterproduit" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
                <span class="link-text">Ajouter un produit</span>
            </a>
        <?php endif; ?>
    </nav>
    <div class="auth-section">
        <?php if (isset($_SESSION['id_user'])): ?>
            <a href="/deconnexion" class="login-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                    <path d="M10 17l-1.41-1.41L14.17 10H3v-2h11.17l-5.58-5.59L10 3l7 7z" />
                </svg>
                <span class="link-text">Se déconnecter</span>
            </a>
        <?php else: ?>
            <a href="/connexion" class="login-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                    <path d="M10 17l-1.41-1.41L14.17 10H3v-2h11.17l-5.58-5.59L10 3l7 7z" />
                </svg>
                <span class="link-text">Se connecter</span>
            </a>
        <?php endif; ?>
    </div>
</aside>

<main class="main-content">
    <?php if (isset($_SESSION['id_user'])): ?>
        <div class="text-end">
            <p class="fw-semibold fs-5">Connecté en tant que <span class="fw-bold prenom"><?= $_SESSION['prenom'] ?></span></p>
        </div>
    <?php endif; ?>
    <?= $content ?>
</main>

<script src="/assets/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
