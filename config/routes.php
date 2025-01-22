<?php

return [
    '/' => ['AccueilController', 'index'],
    '/creationcompte' => ['AuthentificationController', 'creationcompte'],
    '/connexion' => ['AuthentificationController', 'connexion'],
    '/deconnexion' => ['AuthentificationController', 'deconnexion'],
    '/ajouterproduit' => ['ProduitController', 'ajouter'],
    '/pattern' => ['PatternController', 'choix'],
    '/pattern/mois' => ['PatternMoisController', 'ajouter']
];