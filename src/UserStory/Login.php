<?php

namespace App\UserStory;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class Login
{
    // Gestionnaire d'entités pour interagir avec la base de données
    private EntityManager $entityManager;

    /**
     * Constructeur de la classe Login
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        // Injecter l'EntityManager pour interagir avec la base de données
        $this->entityManager = $entityManager;
    }

    /**
     * Cette méthode permet à un utilisateur de se connecter.
     * @param string $email Adresse email de l'utilisateur
     * @param string $password Mot de passe de l'utilisateur
     * @return User L'utilisateur connecté
     * @throws \Exception
     */
    public function execute(string $email, string $password): User
    {
        // Vérifier que les champs ne sont pas vides
        if (empty($email) || empty($password)) {
            throw new \Exception('Tous les champs sont obligatoires.');
        }

        // Récupérer l'utilisateur à partir de l'email
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        // Vérifier que l'utilisateur existe
        if (!$user) {
            throw new \Exception("Aucun compte trouvé avec cet e-mail.");
        }

        // Comparer le mot de passe donné avec le mot de passe hashé en base
        if (!password_verify($password, $user->getPassword())) {
            throw new \Exception("Les informations de connexion sont incorrectes.");
        }

        // Connexion réussie : démarrer une session PHP
        session_start();
        $_SESSION['id_user'] = $user->getId();
        $_SESSION['prenom'] = $user->getPrenom();

        // Retourner l'utilisateur connecté
        return $user;
    }
}