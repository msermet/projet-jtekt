<?php

namespace App\UserStory;

use App\Entity\PatternMois;
use App\Entity\Produit;
use Doctrine\ORM\EntityManager;

class EditPatternMois
{
    // Gestionnaire d'entités pour interagir avec la base de données
    private EntityManager $entityManager;

    /**
     * Constructeur de la classe EditPatternMois
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        // Initialisation de l'entityManager
        $this->entityManager = $entityManager;
    }

    /**
     * Exécute la modification d'un pattern mois
     * @param int $ligne Ligne de production
     * @param int $mois Mois du pattern
     * @param array $sebangoArray Tableau des codes sebango
     * @param array $quantiteArray Tableau des quantités
     * @param int $annee Année du pattern
     * @throws \Exception
     */
    public function execute(int $ligne, int $mois, array $sebangoArray, array $quantiteArray, int $annee): void
    {
        // Vérifie que les tableaux ont la même taille
        if (count($sebangoArray) !== count($quantiteArray)) {
            throw new \Exception("Les tableaux Sebango et Quantité doivent avoir la même taille.");
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

        // Supprime les anciens enregistrements pour la ligne, le mois et l'année spécifiés
        $this->entityManager->createQuery(
            'DELETE FROM App\\Entity\\PatternMois pm WHERE pm.annee = :annee AND pm.mois = :mois AND pm.produit IN (
                SELECT p FROM App\\Entity\\Produit p WHERE p.ligne = :ligne
            )'
        )
            ->setParameter('annee', $annee)
            ->setParameter('mois', $mois)
            ->setParameter('ligne', $ligne)
            ->execute();

        // Parcourt chaque code sebango
        foreach ($sebangoArray as $index => $sebango) {
            $quantite = $quantiteArray[$index];

            // Vérifie que le code sebango est valide
            if (!isset($sebango) || strlen($sebango) !== 4) {
                throw new \Exception("Le Sebango à l'index $index doit contenir exactement 4 caractères.");
            }

            // Vérifie que la quantité est valide
            if (!isset($quantite) || !is_numeric($quantite) || $quantite <= 0) {
                throw new \Exception("La Quantité à l'index $index doit être un nombre strictement positif.");
            }

            // Recherche le produit existant par son code sebango et sa ligne
            $existingProduit = $this->entityManager->getRepository(Produit::class)->findOneBy([
                'sebango' => $sebango,
                'ligne'   => $ligne,
            ]);

            // Vérifie que le produit existe
            if ($existingProduit === null) {
                throw new \Exception("Le Sebango '$sebango' à l'index $index n'existe pas dans les produits.");
            }

            // Vérifie que le produit est autorisé pour la ligne spécifiée
            if ($existingProduit->getLigne() !== $ligne) {
                throw new \Exception("Le Sebango '$sebango' à l'index $index n'est pas autorisé pour la ligne spécifiée.");
            }

            // Crée un nouveau pattern mois
            $patternMois = new PatternMois();
            $patternMois->setMois($mois);
            $patternMois->setQuantite($quantite);
            $patternMois->setAnnee($annee);
            $patternMois->setProduit($existingProduit);

            // Persiste le pattern mois dans la base de données
            $this->entityManager->persist($patternMois);
        }

        // Sauvegarde toutes les modifications dans la base de données
        $this->entityManager->flush();
    }
}