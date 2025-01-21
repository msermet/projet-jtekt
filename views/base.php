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
    <title>Lycée Gaudper - Sanctions</title>
    <link href="/assets/img/Gaudper.png" rel="icon">
    <style>
        #header {
            background-color: lightgrey;
        }
        #footer {
            background-color: lightgrey;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
        }

        .connexion:hover {
            color: lightcyan;
        }
        .inscription:hover {
            color: lightcyan;
        }
        .prenom {
            color: midnightblue;
        }
    </style>
</head>

<body class="d-flex flex-column h-100">
<header id="header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <img id="logo" src="" width="3%" alt="">
            <a class="navbar-brand text-dark ms-2 fw-semibold" href="/">test</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['id_user'])): ?>
                        <li class="nav-item">
                            <a class="fw-semibold btn btn-outline-secondary me-4" href="/ajouterproduit">Ajouter un produit</a>
                        </li>
                        <li class="nav-item">
                            <a class="fw-semibold btn btn-secondary" href="/deconnexion">Déconnexion</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="connexion nav-link fw-semibold btn btn-outline-secondary" href="/connexion">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="inscription nav-link fw-semibold btn btn-secondary" href="/creationcompte">Créer un compte</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container flex-grow-1 my-4">
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
</html>
