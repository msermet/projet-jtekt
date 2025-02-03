<?php

namespace App\Controller;

use App\Entity\Usine;
use Doctrine\ORM\EntityManager;

class AccueilController extends AbstractController
{
    // Propriété pour stocker l'instance de l'EntityManager
    private EntityManager $entityManager;

    // Constructeur pour initialiser l'EntityManager
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    // Méthode principale pour afficher la page d'accueil
    public function index(): void
    {
        // Récupère toutes les usines depuis la base de données
        $usines = $this->entityManager->getRepository(Usine::class)->findAll();

        // Rend le template 'View_Home' avec les données des usines
        $this->render('View_Home', [
            'usines' => $usines,
        ]);
    }
}