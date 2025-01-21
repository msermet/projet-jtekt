<?php
namespace App\Controller;

use App\UserStory\CreateAccount;
use Doctrine\ORM\EntityManager;

class CreateAccountController {
    private EntityManager $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }



    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $createAccount = new CreateAccount($this->entityManager);
                $createAccount->execute(
                    $_POST['pseudo'],
                    $_POST['email'],
                    $_POST['password']
                );

                // Redirection vers la page de connexion après l'inscription
                header('Location: /connexion');
                exit();
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }
        require_once __DIR__ .'/../../views/View_CreateAccount.php';
    }




}
