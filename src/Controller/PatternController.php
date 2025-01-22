<?php

namespace App\Controller;

use App\Entity\Usine;
use Doctrine\ORM\EntityManager;

class PatternController extends AbstractController
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function choix(): void
    {

        $usines = $this->entityManager->getRepository(Usine::class)->findAll();
        $this->render('View_Pattern', [
            'usines' => $usines,
        ]);
    }
}
