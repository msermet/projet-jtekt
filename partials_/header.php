<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <div class="container">
            <a class="navbar-brand text-light" href="/accueil">test</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['id_user'])): ?>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="/logout">Se déconnecter</a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link disabled text-light"><?php echo $_SESSION['pseudo']; ?></span>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link text-light fw-semibold" href="/connexion">Se connecter</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light fw-semibold" href="/creation-compte">Créer un compte</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>


