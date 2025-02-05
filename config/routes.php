<?php

return [
    '/' => ['AccueilController', 'index'],
    '/creationcompte' => ['UserController', 'creationcompte'],
    '/connexion' => ['UserController', 'connexion'],
    '/deconnexion' => ['UserController', 'deconnexion'],
    '/ligne/ajouterproduit' => ['ProduitController', 'ajouter'],
    '/ligne' => ['LigneController', 'choix'],
    '/ligne/mois' => ['PatternMoisController', 'ajouter'],
    '/ligne/jour' => ['PatternJourController', 'ajouter'],
    '/ligne/edit' => ['UpdatePatternController', 'choisir'],
    '/ligne/edit/mois' => ['PatternMoisController', 'modifier'],
    '/ligne/edit/jour' => ['PatternJourController', 'modifier'],
    '/creationligne' => ['LigneController', 'creer'],
    '/editusers' => ['UserController', 'editer'],
    '/ligne/supprimer' => ['LigneController', 'supprimer'],
];