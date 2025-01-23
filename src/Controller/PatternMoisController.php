<?php

namespace App\Controller;

use App\Entity\PatternMois;
use App\Entity\Produit;
use App\Entity\Usine;
use Doctrine\ORM\EntityManager;

class PatternMoisController extends AbstractController
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function ajouter(): void
    {

        $usines = $this->entityManager->getRepository(Usine::class)->findAll();
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();

        $this->render('View_PatternMois', [
            'usines' => $usines,
            'produits' => $produits
        ]);
    }
}
