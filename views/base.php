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
    <title>test</title>
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

        .sidebar {
            width: 250px;
            background-color: #1f1f1f;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 1rem;
            color: white;
            border-right: 1px solid #444;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: #ddd;
            text-decoration: none;
            margin: 0.5rem 0;
            padding: 0.5rem;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar a:hover {
            background-color: #333;
            color: #fff;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .content {
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        body {
            margin: 0;
            overflow-x: hidden;
        }

        .sidebar .bottom-links {
            margin-top: auto;
        }
    </style>
</head>

<body class="d-flex flex-column h-100">
<header id="header">
    <div class="sidebar">
        <div>
            <h5 class="fw-bold">Menu</h5>
            <a href="/" class="d-flex align-items-center">
                <i class="bi bi-house-door"></i>
                Home
            </a>
            <a href="/ajouterproduit" class="d-flex align-items-center">
                <i class="bi bi-plus-square"></i>
                Ajouter un produit
            </a>
        </div>
        <div class="bottom-links">
            <a href="/deconnexion" class="d-flex align-items-center">
                <i class="bi bi-box-arrow-right"></i>
                Déconnexion
            </a>
            <?php if (!isset($_SESSION['id_user'])): ?>
                <a href="/connexion" class="d-flex align-items-center">
                    <i class="bi bi-person"></i>
                    Connexion
                </a>
                <a href="/creationcompte" class="d-flex align-items-center">
                    <i class="bi bi-person-plus"></i>
                    Créer un compte
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="content container-fluid flex-grow-1 my-4">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"></script>
</body>

</html>
