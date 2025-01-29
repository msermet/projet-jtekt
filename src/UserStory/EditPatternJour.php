<?php

namespace App\UserStory;

use App\Entity\PatternJour;
use App\Entity\Produit;
use Doctrine\ORM\EntityManager;

class EditPatternJour
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(int $ligne, int $jour, int $mois, array $sebangoArray, array $besoinArray, array $relicatArray, int $annee): void
    {
        if (count($sebangoArray) !== count($besoinArray) || count($sebangoArray) !== count($relicatArray)) {
            throw new \Exception("Les tableaux Sebango, Besoin et Relicat doivent avoir la même taille.");
        }

        if ($mois < 1 || $mois > 12 || $jour < 1 || $jour > 31) {
            throw new \Exception("Le jour doit être compris entre 1 et 31 et le mois entre 1 et 12.");
        }

        $anneeActuelle = new \DateTime;
        if ($annee < $anneeActuelle->format('Y')) {
            throw new \Exception("L'année doit être supérieure ou égale à l'année actuelle.");
        }

        // Supprimer les anciens enregistrements pour la ligne, le jour, le mois et l'année spécifiés
        $this->entityManager->createQuery(
            'DELETE FROM App\\Entity\\PatternJour pj WHERE pj.annee = :annee AND pj.mois = :mois AND pj.jour = :jour AND pj.produit IN (
                SELECT p FROM App\\Entity\\Produit p WHERE p.ligne = :ligne
            )'
        )
            ->setParameter('annee', $annee)
            ->setParameter('mois', $mois)
            ->setParameter('jour', $jour)
            ->setParameter('ligne', $ligne)
            ->execute();

        foreach ($sebangoArray as $index => $sebango) {
            $besoin = $besoinArray[$index];
            $relicat = $relicatArray[$index];

            if (!isset($sebango) || strlen($sebango) !== 4) {
                throw new \Exception("Le Sebango à l'index $index doit contenir exactement 4 caractères.");
            }

            if (!isset($besoin) || !is_numeric($besoin) || $besoin <= 0) {
                throw new \Exception("Le Besoin à l'index $index doit être un nombre strictement positif.");
            }

            if ($besoin<$relicat) {
                throw new \Exception("Le Besoin à l'index $index doit être supérieur au relicat.");
            }

            if (!isset($relicat) || !is_numeric($relicat) || $relicat < 0) {
                throw new \Exception("Le Relicat à l'index $index doit être un nombre positif ou nul.");
            }

            $existingProduit = $this->entityManager->getRepository(Produit::class)->findOneBy(['sebango' => $sebango]);

            if ($existingProduit === null) {
                throw new \Exception("Le Sebango '$sebango' à l'index $index n'existe pas dans les produits.");
            }

            if ($existingProduit->getLigne() !== $ligne) {
                throw new \Exception("Le Sebango '$sebango' à l'index $index n'est pas autorisé pour la ligne spécifiée.");
            }

            $patternJour = new PatternJour();
            $patternJour->setJour($jour);
            $patternJour->setMois($mois);
            $patternJour->setSebango($sebango);
            $patternJour->setBesoin($besoin);
            $patternJour->setRelicat($relicat);
            $patternJour->setAnnee($annee);
            $patternJour->setProduit($existingProduit);

            $this->entityManager->persist($patternJour);
        }

        $this->entityManager->flush();
    }
}