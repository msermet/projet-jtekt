<?php

namespace App\UserStory;

use Doctrine\ORM\EntityManager;
use App\Entity\User;

class EditUser
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(array $IDArray, array $adminArray): void
    {
        // Vérifie que les tableaux ont la même taille
        if (count($IDArray) !== count($adminArray)) {
            throw new \Exception("Les tableaux ID et Administrateur doivent avoir la même taille.");
        }

        // Récupère tous les utilisateurs actuels
        $allUsers = $this->entityManager->getRepository(User::class)->findAll();

        // Supprime les utilisateurs dont l'ID n'est pas dans $IDArray
        foreach ($allUsers as $user) {
            if (!in_array($user->getId(), $IDArray)) {
                $this->entityManager->remove($user);
            }
        }

        // Met à jour le statut administrateur des utilisateurs restants
        foreach ($IDArray as $index => $id) {
            $userToUpdate = $this->entityManager->getRepository(User::class)->find($id);
            if ($userToUpdate) {
                $userToUpdate->setAdmin($adminArray[$index]);
                $this->entityManager->persist($userToUpdate);
            }
        }

        // Sauvegarde toutes les modifications dans la base de données
        $this->entityManager->flush();
    }
}