<?php

namespace App\Controller;

use App\UserStory\LoginUser;

class LoginController
{
    private LoginUser $login;

    /**
     * @param LoginUser $login
     */
    public function __construct(LoginUser $login)
    {
        $this->login = $login;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';

                $user = $this->login->execute($email, $password);
                header('Location: /accueil');
                exit;
            } catch (\Exception $e) {
                $error = $e->getMessage();
                require_once __DIR__ . '/../../views/View_Login.php';
            }
        } else {
            require_once __DIR__ . '/../../views/View_Login.php';
        }
    }
}
