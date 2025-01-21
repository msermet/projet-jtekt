<?php

// Configuration de l'EntityManager
// Ce fichier permet de configurer l'EntityManager qui est l'objet (classe EntityManager) qui va gérer les actions
// à réaliser sur le base de données :
// - définir l'emplacement des entités
// - définir la configuration des entités
// - définir les informations de connexion avec la base de données
// - créer la connexion avec la base de données

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__.'/../vendor/autoload.php';
// Définir l'emplacement des entités
$path = [__DIR__.'/../src/Entity'];
$isDevMode = true;

// Définir la configuration des entités
$configuration = ORMSetup::createAttributeMetadataConfiguration($path,$isDevMode);

// Définir les éléments de connexion à la base de données
$configurationBD = [
    'driver' => 'pdo_pgsql',
    'user' => 'postgres',
    'password' => 'postgres',
    'dbname' => 'postgres',
    'host' => '10.55.4.218',
    'port' => 5433
];

// Créer de la connexion à la base de données
$connexionBD = DriverManager::getConnection($configurationBD,$configuration);

// Créer l'EntityManager
$entityManager = new EntityManager($connexionBD,$configuration);
return $entityManager;