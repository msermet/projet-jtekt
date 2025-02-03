<?php

namespace App\Controller;

use App\Entity\Usine;
use Doctrine\ORM\EntityManager;

class LigneController extends AbstractController
{
    // Propriété pour stocker l'instance de l'EntityManager
    private EntityManager $entityManager;

    // Constructeur pour initialiser l'EntityManager
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    // Méthode pour gérer le choix de la ligne
    public function choix(): void
    {
        // Démarre une session si aucune session n'est déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['prenom'])) {
            header("Location: /connexion?erreur=connexion");
            exit;
        }

        // Récupère toutes les usines depuis la base de données
        $usines = $this->entityManager->getRepository(Usine::class)->findAll();

        // Rend le template 'View_Ligne' avec les données des usines
        $this->render('View_Ligne', [
            'usines' => $usines,
        ]);
    }
}