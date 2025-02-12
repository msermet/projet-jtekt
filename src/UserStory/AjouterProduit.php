<?php

namespace App\UserStory;

use App\Entity\Ligne;
use App\Entity\Produit;
use Doctrine\ORM\EntityManager;

class AjouterProduit
{
    // Gestionnaire d'entités pour interagir avec la base de données
    private EntityManager $entityManager;

    /**
     * Constructeur de la classe AjouterProduit
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Exécute l'ajout d'un produit
     * @param string $sebango Code sebango du produit
     * @param string $article Code article du produit
     * @param string $designation Désignation du produit
     * @param int $ligne Identifiant de la ligne de production
     * @return Produit Le produit ajouté
     * @throws \Exception
     */
    public function execute(string $sebango, string $article, string $designation, int $ligne): Produit
    {
        // Vérifie que le code sebango est fourni
        if (!isset($sebango)) {
            throw new \Exception('Le sebango est manquant.');
        }

        // Vérifie que le code sebango contient exactement 4 caractères
        if (strlen($sebango) !== 4) {
            throw new \Exception('Le sebango doit contenir 4 caractères.');
        }

        // Vérifie que le code article est fourni
        if (!isset($article)) {
            throw new \Exception("L'article est manquant.");
        }

        // Vérifie que le code article contient exactement 10 caractères
        if (strlen($article) !== 10) {
            throw new \Exception("L'article doit contenir 10 caractères.");
        }

        // Vérifie que la désignation est fournie
        if (!isset($designation)) {
            throw new \Exception("La désignation est manquante.");
        }

        // Vérifie que la désignation ne dépasse pas 50 caractères
        if (strlen($designation) > 50) {
            throw new \Exception("La désignation ne doit pas dépasser 50 caractères.");
        }

        // Vérifie que l'identifiant de la ligne de production est fourni
        if (!isset($ligne)) {
            throw new \Exception("La ligne est manquante.");
        }

        // Vérifie que l'identifiant de la ligne de production est un nombre positif
        if (!is_numeric($ligne) || intval($ligne) <= 0) {
            throw new \Exception("La ligne est invalide ou manquante.");
        }

        // Vérifie que la ligne de production existe
        $existeLigne = $this->entityManager->getRepository(Ligne::class)->findOneBy(['id' => $ligne]);
        if ($existeLigne == null) {
            throw new \Exception("La ligne n'existe pas, veuillez en choisir une autre.");
        }

        // Recherche le produit existant par son code sebango et sa ligne
        $existingSebango = $this->entityManager->getRepository(Produit::class)->findOneBy([
            'sebango' => $sebango,
            'ligne'   => $ligne,
        ]);




        if ($existingSebango !== null) {
            throw new \Exception("Le sebango existe déjà pour cette ligne, veuillez en choisir un autre.");
        }

        // Crée un nouveau produit
        $produit = new Produit();
        $produit->setSebango($sebango);
        $produit->setArticle($article);
        $produit->setDesignation($designation);
        $produit->setLigne($ligne);

        // Persiste le produit dans la base de données
        $this->entityManager->persist($produit);
        $this->entityManager->flush();

        // Retourne le produit ajouté
        return $produit;
    }
}