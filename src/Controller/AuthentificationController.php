<?php
namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\User;
use App\Entity\Usine;
use App\UserStory\CreateAccount;
use App\UserStory\Login;
use Doctrine\ORM\EntityManager;

class AuthentificationController extends AbstractController {
    // Propriété pour stocker l'instance de l'EntityManager
    private EntityManager $entityManager;

    /**
     * Constructeur pour initialiser l'EntityManager
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * Méthode pour gérer la création de compte
     */
    public function creationcompte(): void
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
                // Crée un nouveau compte utilisateur
                $createAccount = new CreateAccount($this->entityManager);
                $createAccount->execute(
                    $_POST['identifiant'],
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['passwordconf'],
                    $_POST['admin'],
                );

                $this->redirect("/creationcompte?ajout=succeed");
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
        $this->render('View_CreateAccount', [
            'error' => $error ?? null,
            'usines' => $usines ?? [],
            'userLogged' => $userLogged ?? null,
        ]);
    }

    /**
     * Méthode pour gérer la connexion
     */
    public function connexion(): void
    {
        // Démarre une session si aucune session n'est déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Redirige vers une page d'erreur si l'utilisateur est déjà connecté
        if (isset($_SESSION['id'])) {
            header("Location: /error");
            exit;
        }

        // Vérifie si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Tente de connecter l'utilisateur
                $createAccount = new Login($this->entityManager);
                $createAccount->execute(
                    $_POST['identifiant'],
                    $_POST['password'],
                );

                // Redirige vers la page d'accueil après la connexion
                header('Location: /');
                exit();
            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                // Gère les erreurs de connexion à la base de données
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                // Gère les autres exceptions
                $error = $e->getMessage();
            }
        }

        // Rend le template 'View_Login' avec les éventuelles erreurs
        $this->render('View_Login', [
            'error' => $error ?? null,
            'usines' => $usines ?? []
        ]);
    }

    /**
     * Méthode pour gérer la déconnexion
     */
    public function deconnexion(): void
    {
        // Démarre une session
        session_start();
        // Vide le tableau $_SESSION
        $_SESSION = [];
        // Détruit la session
        session_destroy();
        // Rend le template 'View_Home' après la déconnexion
        $this->render('View_Home', [
            'usines' => $usines ?? []
        ]);
    }
}