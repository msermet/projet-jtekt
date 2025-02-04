<?php

namespace App\Controller;

use App\Entity\User;
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

        // Démarre une session si aucune session n'est déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Récupère toutes les usines depuis la base de données
        $usines = $this->entityManager->getRepository(Usine::class)->findAll();

        // Vérification si un utilisateur est connecté
        if (isset($_SESSION['id'])) {
            // Récupère l'utilisateur connecté pour l'afficher dans la vue
            $idUser = $_SESSION['id'];
            $userLogged = $this->entityManager->getRepository(User::class)->find($idUser);
        }

        // Rend le template 'View_Home' avec les données des usines
        $this->render('View_Home', [
            'usines' => $usines,
            'userLogged' => $userLogged ?? null,
        ]);
    }
}