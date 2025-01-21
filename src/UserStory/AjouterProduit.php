<?php

namespace App\UserStory;

use App\Entity\Produit;
use Doctrine\ORM\EntityManager;

class AjouterProduit
{
    private EntityManager $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(string $sebango,string $article, string $designation) : Produit
    {
        if (!isset($sebango)) {
            throw new \Exception('Le sebango est manquant.');
        }

        if (strlen($sebango) !== 4) {
            throw new \Exception('Le sebango doit contenir 4 caractères.');
        }

        if (!isset($article)) {
            throw new \Exception("L'article est manquant.");
        }

        if (strlen($article) !== 10) {
            throw new \Exception("L'article doit contenir 10 caractères.");
        }

        if (!isset($designation)) {
            throw new \Exception("La désignation est manquante.");
        }

        if (strlen($designation) > 50) {
            throw new \Exception("La désignation ne doit pas dépasser 50 caractères.");
        }

        $existingSebango = $this->entityManager->getRepository(Produit::class)->findOneBy(['sebango' => $sebango]);
        if ($existingSebango !== null) {
            throw new \Exception("Le sebango existe déjà, veuillez en choisir un autre.");
        }

        $produit = new Produit();
        $produit->setSebango($sebango);
        $produit->setArticle($article);
        $produit->setDesignation($designation);

        $this->entityManager->persist($produit);
        $this->entityManager->flush();

        return $produit;
    }
}