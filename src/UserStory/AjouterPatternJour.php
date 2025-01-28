<?php

namespace App\UserStory;

use App\Entity\PatternJour;
use App\Entity\Produit;
use Doctrine\ORM\EntityManager;

class AjouterPatternJour
{
    private EntityManager $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(int $ligne, int $jour, int $mois, int $annee, array $sebangoArray, array $besoinArray, array $relicatArray,): void
    {
        if (count($sebangoArray) !== count($besoinArray) || count($sebangoArray) !== count($relicatArray)) {
            throw new \Exception("Le nombre de valeurs enregistrées ne sont pas correctes.");
        }

        if ($mois < 1 || $mois > 12) {
            throw new \Exception("Le mois doit être compris entre 1 et 12.");
        }

        $anneeActuelle = new \DateTime;
        if ($annee < $anneeActuelle->format('Y')) {
            throw new \Exception("L'année doit être supérieure ou égale à l'année actuelle.");
        }

        $dateActuelle = new \DateTime;
        $date = new \DateTime();
        $date->setDate($annee, $mois, $jour);
        $formattedDate = $date->format('Y-m-d');
        if ($dateActuelle->format('Y-m-d') > $formattedDate) {
            throw new \Exception("La date doit être supérieure à la date actuelle.");
        }

        foreach ($sebangoArray as $index => $sebango) {
            if (!isset($sebango) || strlen($sebango) !== 4) {
                throw new \Exception("Le Sebango à l'index $index doit contenir exactement 4 caractères.");
            }

            $besoin = $besoinArray[$index];

            if (!isset($besoinArray[$index]) || !is_numeric($besoin) || $besoin <= 0) {
                throw new \Exception("Le besoin à l'index $index doit être un nombre strictement positif.");
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
            $patternJour->setAnnee($annee);
            $patternJour->setSebango($sebango);
            $patternJour->setBesoin($besoin);
            $patternJour->setRelicat($relicatArray[$index]);

            $this->entityManager->persist($patternJour);
        }

        $this->entityManager->flush();
    }
}
