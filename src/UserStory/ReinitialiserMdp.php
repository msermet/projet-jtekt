<?php

namespace App\UserStory;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class ReinitialiserMdp
{
    // Gestionnaire d'entités pour interagir avec la base de données
    private EntityManager $entityManager;

    /**
     * Constructeur de la classe ReinitialiserMdp
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        // L'entityManager est injecté par dépendance
        $this->entityManager = $entityManager;
    }

    /**
     * Exécute la réinitialisation du mot de passe de l'utilisateur
     * @param int $id Identifiant de l'utilisateur
     * @param string $newPassword Nouveau mot de passe de l'utilisateur
     * @param string $confirmPassword Confirmation du nouveau mot de passe
     * @throws \Exception
     */
    public function execute(int $id, string $newPassword, string $confirmPassword): void
    {
        // Récupère l'utilisateur par son identifiant
        $user = $this->entityManager->getRepository(User::class)->find($id);

        // Vérifie que l'utilisateur existe
        if (!$user) {
            throw new \Exception("Utilisateur non trouvé.");
        }

        // Vérifie que le champ mdpOublie est à true
        if (!$user->isMdpOublie()) {
            throw new \Exception("Vous ne pouvez pas modifier le mot de passe d'un autre utilisateur.");
        }

        // Vérifie que les deux mots de passe sont identiques
        if ($newPassword !== $confirmPassword) {
            throw new \Exception("Le mot de passe doit être identique.");
        }

        // Vérifie si le mot de passe est sécurisé
        if (strlen($newPassword) < 8) {
            throw new \Exception("Votre mot de passe doit contenir au minimum 8 caractères.");
        }

        // Hash le nouveau mot de passe
        $mdpHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Met à jour le mot de passe de l'utilisateur
        $user->setPassword($mdpHash);

        // Remet le champ mdpOublie à false
        $user->setMdpOublie(false);

        // Persiste les modifications en utilisant l'entityManager
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}