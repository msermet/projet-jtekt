<?php

namespace App\UserStory;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class CreateAccount
{
    // Gestionnaire d'entités pour interagir avec la base de données
    private EntityManager $entityManager;

    /**
     * Constructeur de la classe CreateAccount
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        // L'entityManager est injecté par dépendance
        $this->entityManager = $entityManager;
    }

    /**
     * Exécute la création d'un compte utilisateur
     * @param string $nom Nom de l'utilisateur
     * @param string $prenom Prénom de l'utilisateur
     * @param string $email Adresse email de l'utilisateur
     * @param string $password Mot de passe de l'utilisateur
     * @param string $passwordconf Confirmation du mot de passe
     * @return User L'utilisateur créé
     * @throws \Exception
     */
    public function execute(string $nom, string $prenom, string $email, string $password, string $passwordconf): User
    {
        // Vérifie que le nom est présent
        if (!isset($nom)) {
            throw new \Exception('Le nom est manquant.');
        }

        // Vérifie que le prénom est présent
        if (!isset($prenom)) {
            throw new \Exception('Le prénom est manquant.');
        }

        // Vérifie que l'email est présent
        if (!isset($email)) {
            throw new \Exception("L'email est manquant.");
        }

        // Vérifie que le mot de passe est présent
        if (!isset($password)) {
            throw new \Exception('Le mot de passe est manquant.');
        }

        // Vérifie que la confirmation du mot de passe est présente
        if (!isset($passwordconf)) {
            throw new \Exception('La confirmation du mot de passe est manquante.');
        }

        // Vérifie si l'email est valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("L'email n'est pas valide.");
        }

        // Vérifie l'unicité de l'email
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser !== null) {
            throw new \Exception("L'email est déjà utilisé.");
        }

        // Vérifie si le mot de passe est sécurisé
        if (strlen($password) < 8) {
            throw new \Exception("Votre mot de passe doit contenir au minimum 8 caractères.");
        }
        if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $password)) {
            throw new \Exception("Votre mot de passe doit contenir un chiffre, une minuscule, une majuscule et un caractère spécial.");
        }

        // Hash le mot de passe
        $mdpHash = password_hash($password, PASSWORD_DEFAULT);

        // Vérifie si la confirmation de mot de passe est correcte
        if (!password_verify($passwordconf, $mdpHash)) {
            throw new \Exception("Le mot de passe doit être identique.");
        }

        // Crée une instance de la classe User
        $user = new User();
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setPassword($mdpHash);

        // Persiste l'instance en utilisant l'entityManager
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Envoi du mail de confirmation
        echo "Un mail de confirmation";

        // Retourne l'utilisateur créé
        return $user;
    }
}