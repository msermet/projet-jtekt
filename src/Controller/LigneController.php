<?php

namespace App\Controller;

use App\Entity\User;
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

        // Redirige vers une page d'erreur si l'utilisateur est déjà connecté
        if (isset($_SESSION['id'])) {
            // Récupère l'utilisateur connecté pour l'afficher dans la vue
            $idUser = $_SESSION['id'];
            $userLogged = $this->entityManager->getRepository(User::class)->find($idUser);
            if (!$userLogged->isAdmin()) {
                $userLogged = null;
            }
        }  else {
            header("Location: /connexion?erreur=connexion");
            exit;
        }


        // Récupère toutes les usines depuis la base de données
        $usines = $this->entityManager->getRepository(Usine::class)->findAll();

        // Rend le template 'View_Ligne' avec les données des usines
        $this->render('View_Ligne', [
            'usines' => $usines,
            'userLogged' => $userLogged ?? null,
        ]);
    }
}