<?php
// Vérifie si une session PHP est déjà démarrée, sinon démarre une nouvelle session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définition des langues disponibles avec leur nom et drapeau respectifs
$languages = [
    'fr' => ['name' => 'Français', 'flag' => 'assets/img/fr.svg'],
    'en' => ['name' => 'English', 'flag' => 'assets/img/en.svg'],
    'es' => ['name' => 'Español', 'flag' => 'assets/img/es.svg'],
    'cs' => ['name' => 'Čeština', 'flag' => 'assets/img/cs.svg'],
    'ja' => ['name' => '日本語', 'flag' => 'assets/img/ja.svg'],
    'de' => ['name' => 'Deutsch', 'flag' => 'assets/img/de.svg'],
    'ro' => ['name' => 'Română', 'flag' => 'assets/img/ro.svg']
];

// Gestion du changement de langue
if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $languages)) {
    // Met à jour la langue dans la session
    $_SESSION['lang'] = $_GET['lang'];
    // Reconstruit l'URL sans le paramètre de langue
    $params = $_GET;
    unset($params['lang']);
    $queryString = http_build_query($params);
    // Redirige vers la même page sans le paramètre de langue
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . ($queryString ? '?' . $queryString : ''));
    exit;
}

// Définit la langue par défaut si aucune langue n'est sélectionnée
$lang = $_SESSION['lang'] ?? 'fr';

// Charge les traductions pour la langue sélectionnée
$translations = include 'lang.php';
$t = $translations[$lang];
?>

<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Liens vers les fichiers CSS externes -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title><?= $t['title'] ?></title>
</head>

<body class="layout">
<aside id="sidebar" class="sidebar">
    <div class="language-selector">
        <div class="language-select-wrapper">
            <!-- Sélecteur de langue -->
            <select id="language" class="form-select">
                <?php foreach ($languages as $code => $info): ?>
                    <option value="<?= $code ?>" <?= $code === $lang ? 'selected' : '' ?>
                            data-flag="<?= $info['flag'] ?>">
                        <?= $info['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <!-- Affiche le drapeau de la langue sélectionnée -->
            <img id="selectedFlag" src="<?= $languages[$lang]['flag'] ?>" alt="<?= $languages[$lang]['name'] ?>" class="selected-flag">
        </div>
    </div>

    <!-- Section du logo et du lien vers la page d'accueil -->
    <div class="logo-section">
        <a href="/" class="home-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
            </svg>
            <span class="link-text"><?= $t['home'] ?></span>
        </a>
    </div>

    <!-- Navigation principale -->
    <nav class="nav-links">
        <?php if (isset($_SESSION['id'])): ?>
            <?php if (isset($usines) && !empty($usines)): ?>
                <!-- Lien vers les usines et leurs lignes -->
                <a href="#" class="nav-link usines-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-buildings" viewBox="0 0 16 16">
                        <path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z"/>
                        <path d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z"/>
                    </svg>
                    <span class="link-text"><?= $t['plants'] ?> <i class="bi bi-caret-down-fill"></i></span>
                </a>
            <?php endif; ?>
            <!-- Conteneur des usines et lignes -->
            <div class="usines-container hidden">
                <?php if (!empty($usines)): ?>
                    <?php foreach ($usines as $usine): ?>
                        <div class="dropdown">
                            <button class="dropdown-toggle"><?= htmlspecialchars($usine->getNom()) ?></button>
                            <ul class="dropdown-menu">
                                <?php if (!empty($usine->getLignes())): ?>
                                    <?php foreach ($usine->getLignes() as $ligne): ?>
                                        <li>
                                            <a href="/ligne?usine=<?= htmlspecialchars($usine->getId()) ?>&ligne=<?= htmlspecialchars($ligne->getId()) ?>">
                                                <?= htmlspecialchars($ligne->getNom()) ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li><span class="submenu-link">Aucune ligne</span></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <!-- Ce menu s'affiche seulement si l'utilisateur est admin -->
            <?php if (isset($userLogged) && !empty($userLogged)): ?>
                <?php if ($userLogged->isAdmin()): ?>
                <!-- Lien pour créer des utilisateurs -->
                <a href="/creationcompte" class="nav-link usines-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
                        <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                        <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
                    </svg>
                    <span class="link-text"><?= $t['creerCompte'] ?></span>
                </a>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </nav>

    <!-- Section d'authentification -->
    <div class="auth-section">
        <?php if (isset($_SESSION['id'])): ?>
            <!-- Lien de déconnexion -->
            <a href="/deconnexion" class="login-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5M10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5"/>
                </svg>
                <span class="link-text"><?= $t['logout'] ?></span>
            </a>
        <?php else: ?>
            <!-- Lien de connexion -->
            <a href="/connexion" class="login-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                    <path d="M10 17l-1.41-1.41L14.17 10H3v-2h11.17l-5.58-5.59L10 3l7 7z" />
                </svg>
                <span class="link-text"><?= $t['login'] ?></span>
            </a>
        <?php endif; ?>
    </div>
</aside>

<!-- Contenu principal de la page -->
<main class="main-content">
    <?= $content ?>
</main>

<!-- Liens vers les fichiers JavaScript externes -->
<script src="/assets/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>