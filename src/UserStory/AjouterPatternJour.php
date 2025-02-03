<?php

namespace App\UserStory;

use App\Entity\PatternJour;
use App\Entity\Produit;
use Doctrine\ORM\EntityManager;

class AjouterPatternJour
{
    // Gestionnaire d'entités pour interagir avec la base de données
    private EntityManager $entityManager;

    /**
     * Constructeur de la classe AjouterPatternJour
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Exécute l'ajout d'un pattern jour
     * @param int $ligne Ligne de production
     * @param int $jour Jour du pattern
     * @param int $mois Mois du pattern
     * @param int $annee Année du pattern
     * @param array $sebangoArray Tableau des codes sebango
     * @param array $besoinArray Tableau des besoins
     * @param array $relicatArray Tableau des relicats
     * @throws \Exception
     */
    public function execute(int $ligne, int $jour, int $mois, int $annee, array $sebangoArray, array $besoinArray, array $relicatArray): void
    {
        // Vérifie que les tableaux ont la même taille
        if (count($sebangoArray) !== count($besoinArray) || count($sebangoArray) !== count($relicatArray)) {
            throw new \Exception("Le nombre de valeurs enregistrées ne sont pas correctes.");
        }

        // Vérifie que le mois est valide
        if ($mois < 1 || $mois > 12) {
            throw new \Exception("Le mois doit être compris entre 1 et 12.");
        }

        // Vérifie que l'année est valide
        $anneeActuelle = new \DateTime;
        if ($annee < $anneeActuelle->format('Y')) {
            throw new \Exception("L'année doit être supérieure ou égale à l'année actuelle.");
        }

        // Vérifie que la date est valide
        $dateActuelle = new \DateTime;
        $date = new \DateTime();
        $date->setDate($annee, $mois, $jour);
        $formattedDate = $date->format('Y-m-d');
        if ($dateActuelle->format('Y-m-d') > $formattedDate) {
            throw new \Exception("La date doit être supérieure à la date actuelle.");
        }

        // Parcourt chaque code sebango
        foreach ($sebangoArray as $index => $sebango) {
            // Vérifie que le code sebango est valide
            if (!isset($sebango) || strlen($sebango) !== 4) {
                throw new \Exception("Le Sebango à l'index $index doit contenir exactement 4 caractères.");
            }

            $besoin = $besoinArray[$index];

            // Vérifie que le besoin est valide
            if (!isset($besoinArray[$index]) || !is_numeric($besoin) || $besoin <= 0) {
                throw new \Exception("Le besoin à l'index $index doit être un nombre strictement positif.");
            }

            // Vérifie que le besoin et le relicat ne sont pas identiques
            if ($besoin == $relicatArray[$index]) {
                throw new \Exception("Le besoin et le relicat à l'index $index ne doivent pas être identiques.");
            }

            // Recherche le produit existant par son code sebango
            $existingProduit = $this->entityManager->getRepository(Produit::class)->findOneBy(['sebango' => $sebango]);

            // Vérifie que le produit existe
            if ($existingProduit === null) {
                throw new \Exception("Le Sebango '$sebango' à l'index $index n'existe pas dans les produits.");
            }

            // Vérifie que le produit est autorisé pour la ligne spécifiée
            if ($existingProduit->getLigne() !== $ligne) {
                throw new \Exception("Le Sebango '$sebango' à l'index $index n'est pas autorisé pour la ligne spécifiée.");
            }

            // Crée un nouveau pattern jour
            $patternJour = new PatternJour();
            $patternJour->setJour($jour);
            $patternJour->setMois($mois);
            $patternJour->setAnnee($annee);
            $patternJour->setSebango($sebango);
            $patternJour->setBesoin($besoin);
            $patternJour->setRelicat($relicatArray[$index]);
            $patternJour->setProduit($existingProduit);

            // Persiste le pattern jour dans la base de données
            $this->entityManager->persist($patternJour);
        }

        // Sauvegarde toutes les modifications dans la base de données
        $this->entityManager->flush();
    }
}