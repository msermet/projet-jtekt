<?php

namespace App\UserStory;

use App\Entity\PatternMois;
use App\Entity\Produit;
use Doctrine\ORM\EntityManager;

class AjouterPatternMois
{
    private EntityManager $entityManager;

    /**
     * @param EntityManager $entityManager
     */
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

        foreach ($sebangoArray as $index => $sebango) {
            $quantite = $quantiteArray[$index];

            if (!isset($sebango) || strlen($sebango) !== 4) {
                throw new \Exception("Le Sebango à l'index $index doit contenir exactement 4 caractères.");
            }

            if (!isset($quantite) || !is_numeric($quantite) || $quantite <= 0) {
                throw new \Exception("La Quantité à l'index $index doit être un nombre strictement positif.");
            }

            $existingSebango = $this->entityManager->getRepository(Produit::class)->findOneBy(['sebango' => $sebango]);
            if ($existingSebango === null) {
                throw new \Exception("Le Sebango '$sebango' à l'index $index n'existe pas dans les produits.");
            }

            $patternMois = new PatternMois();
            $patternMois->setLigne($ligne);
            $patternMois->setMois($mois);
            $patternMois->setSebango($sebango);
            $patternMois->setQuantite($quantite);
            $patternMois->setAnnee($annee);

            $this->entityManager->persist($patternMois);
        }
        $this->entityManager->flush();
    }
}
