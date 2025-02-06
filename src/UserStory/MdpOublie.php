<?php

namespace App\UserStory;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class MdpOublie
{
    // Gestionnaire d'entités pour interagir avec la base de données
    private EntityManager $entityManager;

    /**
     * Constructeur de la classe MdpOublie
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        // Injecter l'EntityManager pour interagir avec la base de données
        $this->entityManager = $entityManager;
    }

    /**
     * Cette méthode permet de vérifier l'utilisateur pour la réinitialisation du mot de passe.
     * @param string $identifiant Identifiant de l'utilisateur
     * @param string $email Adresse email de l'utilisateur
     * @return User L'utilisateur trouvé
     * @throws \Exception
     */
    public function execute(string $identifiant, string $email): User
    {
        // Vérifier que les champs ne sont pas vides
        if (empty($identifiant) || empty($email)) {
            throw new \Exception('Tous les champs sont obligatoires.');
        }

        // Récupérer l'utilisateur à partir de l'identifiant et de l'email
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'identifiant' => $identifiant,
            'email' => $email,
        ]);

        // Vérifier que l'utilisateur existe
        if (!$user) {
            throw new \Exception("Aucun compte trouvé avec cet identifiant et cet email.");
        }

        $user->setMdpOublie(true);

        // Persiste l'instance en utilisant l'entityManager
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Retourner l'utilisateur trouvé
        return $user;
    }
}