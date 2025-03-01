<?php
namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\User;
use App\Entity\Usine;
use App\UserStory\CreateAccount;
use App\UserStory\EditUser;
use App\UserStory\Login;
use App\UserStory\MdpOublie;
use App\UserStory\ReinitialiserMdp;
use Doctrine\ORM\EntityManager;

class UserController extends AbstractController {
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
     * Réinitialise le champ mdpOublie pour tous les utilisateurs
     */
    private function resetMdpOublie(): void {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $user->setMdpOublie(false);
        }
        $this->entityManager->flush();
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
        $this->resetMdpOublie();

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

    /**
     * Méthode pour gérer la modification de compte
     */
    public function editer(): void
    {

        // Démarre une session si aucune session n'est déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Redirige vers une page d'erreur si l'utilisateur n'est pas connecté
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

        // Récupère tous les utilisateurs depuis la base de données
        $users = $this->entityManager->getRepository(User::class)->findAll();

        // Vérifie si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Récupère les données des utilisateurs
                $idArray = $_POST['id'] ?? [];
                $adminArray = array_map(fn($id) => isset($_POST['admin'][$id]) && $_POST['admin'][$id] == '1', $idArray);

                // Crée un nouveau compte utilisateur
                $editUser = new EditUser($this->entityManager);
                $editUser->execute($idArray, $adminArray);

                $this->redirect("/editusers?ajout=succeed");
                exit();

            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                // Gère les erreurs de connexion à la base de données
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                // Gère les autres exceptions
                $error = $e->getMessage();
            }
        }

        $this->render('View_EditUser', [
            'error' => $error ?? null,
            'usines' => $usines ?? [],
            'userLogged' => $userLogged ?? null,
            'users' => $users ?? null,
        ]);
    }

    public function oublieMdp(): void
    {

        // Démarre une session si aucune session n'est déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Redirige vers une page d'erreur si l'utilisateur n'est pas connecté
        if (isset($_SESSION['id'])) {
            header("Location: /error");
            exit;
        }

        $this->resetMdpOublie();

        // Vérifie si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Tente de connecter l'utilisateur
                $mdpOublie = new MdpOublie($this->entityManager);
                $mdpOublie->execute(
                    $_POST['identifiant'],
                    $_POST['email'],
                );

                $identifiant = $_POST['identifiant'];
                $email = $_POST['email'];

                $user = $this->entityManager->getRepository(User::class)->findOneBy([
                    'identifiant' => $identifiant,
                    'email' => $email,
                ]);

                // Redirige vers la page d'accueil après la connexion
                header('Location: /reinitialisationMdp?id=' . $user->getId());
                exit();
            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                // Gère les erreurs de connexion à la base de données
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                // Gère les autres exceptions
                $error = $e->getMessage();
            }
        }

        $this->render('View_MdpOublie', [
            'error' => $error ?? null,
        ]);
    }

    public function reinitialiserMdp(): void
    {
        // Démarre une session si aucune session n'est déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Redirige vers une page d'erreur si l'utilisateur n'est pas connecté
        if (isset($_SESSION['id'])) {
            header("Location: /error");
            exit;
        }

        $user = $this->entityManager->getRepository(User::class)->find($_GET['id']);

        if ($user->isMdpOublie() === false) {
            header("Location: /mdpOublie");
            exit;
        }

        // Vérifie si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Tente de connecter l'utilisateur
                $mdpOublie = new ReinitialiserMdp($this->entityManager);
                $mdpOublie->execute(
                    $_POST['id'],
                    $_POST['newPassword'],
                    $_POST['confirmPassword'],
                );

                // Redirige vers la page d'accueil après la connexion
                header('Location: /connexion?reset=succeed');
                exit();
            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                // Gère les erreurs de connexion à la base de données
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                // Gère les autres exceptions
                $error = $e->getMessage();
            }
        }

        $this->render('View_ReinitialisationMdp', [
            'error' => $error ?? null,
        ]);
    }
}