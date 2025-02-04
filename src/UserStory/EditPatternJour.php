<?php

namespace App\UserStory;

use App\Entity\PatternJour;
use App\Entity\Produit;
use Doctrine\ORM\EntityManager;

class EditPatternJour
{
    // Gestionnaire d'entités pour interagir avec la base de données
    private EntityManager $entityManager;

    /**
     * Constructeur de la classe EditPatternJour
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        // Initialisation de l'entityManager
        $this->entityManager = $entityManager;
    }

    /**
     * Exécute la modification d'un pattern jour
     * @param int $ligne Ligne de production
     * @param int $jour Jour du pattern
     * @param int $mois Mois du pattern
     * @param array $sebangoArray Tableau des codes sebango
     * @param array $besoinArray Tableau des besoins
     * @param array $relicatArray Tableau des relicats
     * @param int $annee Année du pattern
     * @throws \Exception
     */
    public function execute(int $ligne, int $jour, int $mois, array $sebangoArray, array $besoinArray, array $relicatArray, int $annee): void
    {
        // Vérifie que les tableaux ont la même taille
        if (count($sebangoArray) !== count($besoinArray) || count($sebangoArray) !== count($relicatArray)) {
            throw new \Exception("Les tableaux Sebango, Besoin et Relicat doivent avoir la même taille.");
        }

        // Vérifie que l'année est valide
        $anneeActuelle = new \DateTime;
        if ($annee < $anneeActuelle->format('Y')) {
            throw new \Exception("L'année doit être supérieure ou égale à l'année actuelle.");
        }

        if (!checkdate($mois, $jour, $annee)) {
            throw new \Exception("La date fournie n'est pas valide.");
        }

        // Supprime les anciens enregistrements pour la ligne, le jour, le mois et l'année spécifiés
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

        // Parcourt chaque code sebango
        foreach ($sebangoArray as $index => $sebango) {
            $besoin = $besoinArray[$index];
            $relicat = $relicatArray[$index];

            // Vérifie que le code sebango est valide
            if (!isset($sebango) || strlen($sebango) !== 4) {
                throw new \Exception("Le Sebango à l'index $index doit contenir exactement 4 caractères.");
            }

            // Vérifie que le besoin est valide
            if (!isset($besoin) || !is_numeric($besoin) || $besoin <= 0) {
                throw new \Exception("Le Besoin à l'index $index doit être un nombre strictement positif.");
            }

            // Vérifie que le besoin est supérieur au relicat
            if ($besoin < $relicat) {
                throw new \Exception("Le Besoin à l'index $index doit être supérieur au relicat.");
            }

            // Vérifie que le relicat est valide
            if (!isset($relicat) || !is_numeric($relicat) || $relicat < 0) {
                throw new \Exception("Le Relicat à l'index $index doit être un nombre positif ou nul.");
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
            $patternJour->setSebango($sebango);
            $patternJour->setBesoin($besoin);
            $patternJour->setRelicat($relicat);
            $patternJour->setAnnee($annee);
            $patternJour->setProduit($existingProduit);

            // Persiste le pattern jour dans la base de données
            $this->entityManager->persist($patternJour);
        }

        // Sauvegarde toutes les modifications dans la base de données
        $this->entityManager->flush();
    }
}