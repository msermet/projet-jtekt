<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Usine;
use App\UserStory\CreateLine;
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


    public function creer(): void
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
                header("Location: /error");
                exit;
            }
        } else {
            header("Location: /connexion?erreur=connexion");
            exit;
        }

        // Récupère toutes les usines depuis la base de données
        $usines = $this->entityManager->getRepository(Usine::class)->findAll();

        // Vérifie si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Crée une nouvelle ligne
                $createLine = new CreateLine($this->entityManager);
                $createLine->execute(
                    $_POST['usine'],
                    $_POST['nom'],
                );

                // Récupère l'ID de l'usine
                $idUsine = $_POST['usine'];

                // Redirige vers la page de modification de la ligne avec les paramètres de la date et un message de succès
                $this->redirect("/creationligne?usine=$idUsine&ajout=succeed");
                exit();

            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                // Gère les erreurs de connexion à la base de données
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                // Gère les autres exceptions
                $error = $e->getMessage();
            }
        }

        // Rend le template 'View_CreateAccount' avec les données des usines et les éventuelles erreurs
        $this->render('View_CreateLine', [
            'error' => $error ?? null,
            'usines' => $usines ?? [],
            'userLogged' => $userLogged ?? null,
        ]);
    }
}