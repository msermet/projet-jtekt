<?php

// Inclure l'autoloader de Composer pour charger automatiquement les classes
require_once __DIR__ . '/../vendor/autoload.php';

// Charger l'EntityManager à partir du fichier de configuration
$entityManager = require __DIR__ . '/../config/bootstrap.php';  // Config doctrine

// Récupérer les routes définies dans le fichier de configuration
$routes = require_once __DIR__ . '/../config/routes.php';

// Récupérer l'URL actuelle demandée par le client
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Vérifier si l'URL demandée correspond à une route définie
if (!isset($routes[$uri])) {
    // Si la route n'existe pas, appeler le contrôleur d'erreur pour afficher une page 404
    $errorController = new \App\Controller\ErrorController();
    $errorController->error404();
    exit;
}

// Récupérer le nom du contrôleur et l'action à exécuter pour la route demandée
[$controllerName, $action] = $routes[$uri];
$controllerClass = "App\\Controller\\{$controllerName}";

try {
    // Instancier le contrôleur correspondant et appeler l'action
    $controller = new $controllerClass($entityManager);
    $controller->$action();
} catch (\Exception $e) {
    // En cas d'exception, enregistrer l'erreur dans le log et afficher une page 404
    error_log($e->getMessage());
    $errorController = new \App\Controller\ErrorController();
    $errorController->error404();
}