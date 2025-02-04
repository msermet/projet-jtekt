<?php

return [
    '/' => ['AccueilController', 'index'],
    '/creationcompte' => ['AuthentificationController', 'creationcompte'],
    '/connexion' => ['AuthentificationController', 'connexion'],
    '/deconnexion' => ['AuthentificationController', 'deconnexion'],
    '/ligne/ajouterproduit' => ['ProduitController', 'ajouter'],
    '/ligne' => ['LigneController', 'choix'],
    '/ligne/mois' => ['PatternMoisController', 'ajouter'],
    '/ligne/jour' => ['PatternJourController', 'ajouter'],
    '/ligne/edit' => ['UpdatePatternController', 'choisir'],
    '/ligne/edit/mois' => ['PatternMoisController', 'modifier'],
    '/ligne/edit/jour' => ['PatternJourController', 'modifier'],
    '/creationligne' => ['LigneController', 'creer']
];