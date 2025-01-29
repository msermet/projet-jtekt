<?php

namespace App\UserStory;

use App\Entity\PatternMois;
use App\Entity\Produit;
use Doctrine\ORM\EntityManager;

class EditPatternMois
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(int $ligne, int $mois, array $sebangoArray, array $quantiteArray, int $annee): void
    {
        if (count($sebangoArray) !== count($quantiteArray)) {
            throw new \Exception("Les tableaux Sebango et Quantité doivent avoir la même taille.");
        }

        if ($mois < 1 || $mois > 12) {
            throw new \Exception("Le mois doit être compris entre 1 et 12.");
        }

        $anneeActuelle = new \DateTime;
        if ($annee < $anneeActuelle->format('Y')) {
            throw new \Exception("L'année doit être supérieure ou égale à l'année actuelle.");
        }

        // Supprimer les anciens enregistrements pour la ligne, le mois et l'année spécifiés
        $this->entityManager->createQuery(
            'DELETE FROM App\\Entity\\PatternMois pm WHERE pm.annee = :annee AND pm.mois = :mois AND pm.produit IN (
                SELECT p FROM App\\Entity\\Produit p WHERE p.ligne = :ligne
            )'
        )
            ->setParameter('annee', $annee)
            ->setParameter('mois', $mois)
            ->setParameter('ligne', $ligne)
            ->execute();

        foreach ($sebangoArray as $index => $sebango) {
            $quantite = $quantiteArray[$index];

            if (!isset($sebango) || strlen($sebango) !== 4) {
                throw new \Exception("Le Sebango à l'index $index doit contenir exactement 4 caractères.");
            }

            if (!isset($quantite) || !is_numeric($quantite) || $quantite <= 0) {
                throw new \Exception("La Quantité à l'index $index doit être un nombre strictement positif.");
            }

            $existingProduit = $this->entityManager->getRepository(Produit::class)->findOneBy(['sebango' => $sebango]);

            if ($existingProduit === null) {
                throw new \Exception("Le Sebango '$sebango' à l'index $index n'existe pas dans les produits.");
            }

            if ($existingProduit->getLigne() !== $ligne) {
                throw new \Exception("Le Sebango '$sebango' à l'index $index n'est pas autorisé pour la ligne spécifiée.");
            }

            $patternMois = new PatternMois();
            $patternMois->setMois($mois);
            $patternMois->setSebango($sebango);
            $patternMois->setQuantite($quantite);
            $patternMois->setAnnee($annee);
            $patternMois->setProduit($existingProduit);

            $this->entityManager->persist($patternMois);
        }

        $this->entityManager->flush();
    }
}