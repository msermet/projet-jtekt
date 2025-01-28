<?php

namespace App\Controller;

use App\Entity\Ligne;
use App\Entity\PatternMois;
use App\Entity\Produit;
use App\Entity\Usine;
use App\UserStory\AjouterPatternMois;
use Doctrine\ORM\EntityManager;

class EditPatternMoisController extends AbstractController
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function modifier(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['prenom'])) {
            header("Location: /connexion?erreur=connexion");
            exit;
        }

        $usines = $this->entityManager->getRepository(Usine::class)->findAll();
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();
        $patternMois = $this->entityManager->getRepository(PatternMois::class)->findAll();


        $this->render('View_EditPatternMois', [
            'error' => $error ?? null,
            'usines' => $usines,
            'produits' => $produits,
            'patternMois' => $patternMois
        ]);
    }
}
