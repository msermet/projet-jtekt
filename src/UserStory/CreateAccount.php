<?php

namespace App\UserStory;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class CreateAccount
{
    private EntityManager $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        // l'entityManager est injecté par dépendance (à savoir)
        $this->entityManager = $entityManager;
    }

    // Cette méthode permettra d'exécuter la user story
    public function execute(string $nom,string $prenom, string $email,string $password, string $passwordconf) : User
    {
        // Vérifier que les données sont présentes
        // Si tel n'est pas le cas, lancer une exception
        if (!isset($nom)) {
            throw new \Exception('Le nom est manquant.');
        }
        if (!isset($prenom)) {
            throw new \Exception('Le prénom est manquant.');
        }
        if (!isset($email)) {
            throw new \Exception("L'email est manquant.");
        }
        if (!isset($password)) {
            throw new \Exception('Le mot de passe est manquant.');
        }
        if (!isset($passwordconf)) {
            throw new \Exception('La confirmation du mot de passe est manquante.');
        }

        // Vérifier si l'email est valide
        // Si tel n'est pas le cas, lancer une exception
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("L'email n'est pas valide.");
        }

        // Vérifier l'unicité de l'email
        // Si tel n'est pas le cas, lancer une exception
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser !== null) {
            throw new \Exception("L'email est déjà utilisé.");
        }

        // Vérifier si le mot de passe est sécurisé
        // Si tel n'est pas le cas, lancer une exception
        if (strlen($password)<8) {
            throw new \Exception("Votre mot de passe doit contenir au minimum 8 caractères.");
        }
        if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $password)) {
            throw new \Exception("Votre mot de passe doit contenir un chiffre, une minuscule, une majuscule et un caractère spécial.");
        }


        // Insérer les données dans la base de données
        // 1. Hash le mot de passe
        $mdpHash = password_hash($password,PASSWORD_DEFAULT);

        // Vérifier si la confirmation de mot de passe est correcte
        // Si tel n'est pas le cas, lancer une exception
        // Comparer le mot de passe donné avec le mot de passe hashé en base
        if (!password_verify($passwordconf, $mdpHash)) {
            throw new \Exception("Le mot de passe doit être identique.");
        }

        // 2. Créer une instance de la classe User
        $user = new User(); // setters
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setPassword($mdpHash);

        // 3. Persist l'instance en utilisant l'entityManager
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // 4. Envoi du mail de confirmation
        echo "Un mail de confirmation";
        return $user;
    }
}