<?php

namespace App\Controller;
use App\Entity\Usine;
use Doctrine\ORM\EntityManager;

class AccueilController extends AbstractController
{
    private EntityManager $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function index(): void
    {
        $usines = $this->entityManager->getRepository(Usine::class)->findAll();

        $this->render('View_Home', [
            'usines' => $usines ?? []
        ]);
    }

}
