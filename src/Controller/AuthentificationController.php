<?php
namespace App\Controller;

use App\Controller\AbstractController;
use App\UserStory\CreateAccount;
use App\UserStory\Login;
use Doctrine\ORM\EntityManager;

class AuthentificationController extends AbstractController {
    private EntityManager $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }



    public function creationcompte(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['prenom'])) {
            header("Location: /error");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $createAccount = new CreateAccount($this->entityManager);
                $createAccount->execute(
                    $_POST['nom'],
                    $_POST['prenom'],
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['passwordconf'],
                );

                // Passer le message dans l'URL
                header('Location: /connexion?inscription=succeed');
                exit();
            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }
        $this->render('View_CreateAccount', [
            'error' => $error ?? null,
        ]);
    }

    public function connexion(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['prenom'])) {
            header("Location: /error");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $createAccount = new Login($this->entityManager);
                $createAccount->execute(
                    $_POST['email'],
                    $_POST['password'],
                );

                // Redirection vers la page de connexion après l'inscription
                header('Location: /');
                exit();
            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }
        $this->render('View_Login', [
            'error' => $error ?? null,
        ]);
    }

    public function deconnexion(): void
    {
        session_start();
        $_SESSION = [];
        session_destroy();
        $this->render('View_Home');
    }

}
