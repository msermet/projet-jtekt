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
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>test</title>
    <link href="/assets/img/Gaudper.png" rel="icon">
    <style>
        #footer {
            background-color: lightgrey;
        }
        .prenom {
            color: midnightblue;
        }
    </style>
</head>

<body>
<!--<header id="header">-->
<!--    <nav class="navbar navbar-expand-lg navbar-light">-->
<!--        <div class="container">-->
<!--            <img id="logo" src="" width="3%" alt="">-->
<!--            <a class="navbar-brand text-dark ms-2 fw-semibold" href="/">test</a>-->
<!--            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">-->
<!--                <span class="navbar-toggler-icon"></span>-->
<!--            </button>-->
<!--            <div class="collapse navbar-collapse" id="navbarNav">-->
<!--                <ul class="navbar-nav ms-auto">-->
<!--                    --><?php //if (isset($_SESSION['id_user'])): ?>
<!--                        <li class="nav-item">-->
<!--                            <a class="fw-semibold btn btn-outline-secondary me-4" href="/ajouterproduit">Ajouter un produit</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="fw-semibold btn btn-secondary" href="/deconnexion">Déconnexion</a>-->
<!--                        </li>-->
<!--                    --><?php //else: ?>
<!--                        <li class="nav-item">-->
<!--                            <a class="connexion nav-link fw-semibold btn btn-outline-secondary" href="/connexion">Connexion</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="inscription nav-link fw-semibold btn btn-secondary" href="/creationcompte">Créer un compte</a>-->
<!--                        </li>-->
<!--                    --><?php //endif; ?>
<!--                </ul>-->
<!--            </div>-->
<!--        </div>-->
<!--    </nav>-->
<!--</header>-->

<div class="layout">
    <div class="sidebar" id="sidebar">
        <button class="toggle-button" id="toggleButton">
            <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </button>

        <!-- Logo/Home section -->
        <div class="logo-section">
            <a href="/" class="home-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span class="link-text">Accueil</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="nav-links">
            <a href="/dashboard" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="3" height="7" x="4" y="14" rx="1"/>
                    <rect width="3" height="12" x="12" y="9" rx="1"/>
                    <rect width="3" height="17" x="20" y="4" rx="1"/>
                </svg>
                <span class="link-text">Tableau de bord</span>
            </a>
            <a href="/users" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span class="link-text">Utilisateurs</span>
            </a>
            <a href="/documents" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                <span class="link-text">Documents</span>
            </a>
            <a href="/settings" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                <span class="link-text">Paramètres</span>
            </a>
        </nav>

        <!-- Authentication section -->
        <div class="auth-section">
            <button class="login-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" x2="3" y1="12" y2="12"/>
                </svg>
                <span class="link-text">Connexion</span>
            </button>
        </div>
    </div>

<main class="main-content">
    <?php if (isset($_SESSION['id_user'])): ?>
        <div class="text-end">
            <p class="fw-semibold fs-5">Connecté en tant que <span class="fw-bold prenom"><?= $_SESSION['prenom'] ?></span></p>
        </div>
    <?php endif; ?>
    <?= $content ?>
</main>

<footer id="footer" class="footer mt-auto py-3 d-flex flex-column align-items-center border-top">
    <div class="text-center">
        <span class="text-dark">&copy; test</span>
    </div>
</footer>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="assets/js/script.js"></script>

</html>
