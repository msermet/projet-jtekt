<?php

namespace App\UserStory;

use App\Entity\Ligne;
use App\Entity\Usine;
use Doctrine\ORM\EntityManager;

class CreateLine
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

    public function execute(string $usine, string $nom): Ligne
    {
        // Vérifie que l'usine est présente
        if (!isset($usine)) {
            throw new \Exception("L'usine est manquante.");
        }

        // Vérifie que le nom est présent
        if (!isset($nom)) {
            throw new \Exception("Le nom est manquant.");
        }

        $usine = $this->entityManager->getRepository(Usine::class)->findOneBy(['id' => $usine]);

        // Vérifie l'unicité de la ligne
        $existingLine = $this->entityManager->getRepository(Ligne::class)->findOneBy(['nom' => $nom, 'usine' => $usine]);
        if ($existingLine !== null) {
            throw new \Exception("Le nom de la ligne est déjà utilisé pour cette usine.");
        }

        // Vérifie si le nom est sécurisé
        if (strlen($nom) > 50) {
            throw new \Exception("Le nom de la ligne ne doit pas dépasser 50 caractères.");
        }

        // Crée une instance de la classe Ligne
        $ligne = new Ligne();
        $ligne->setNom($nom);
        $ligne->setUsine($usine);

        // Persiste l'instance en utilisant l'entityManager
        $this->entityManager->persist($ligne);
        $this->entityManager->flush();

        // Retourne l'instance de Ligne créée
        return $ligne;
    }
}