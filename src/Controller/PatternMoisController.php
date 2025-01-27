<?php

namespace App\Controller;

use App\Entity\Ligne;
use App\Entity\PatternMois;
use App\Entity\Produit;
use App\Entity\Usine;
use App\UserStory\AjouterPatternMois;
use Doctrine\ORM\EntityManager;

class PatternMoisController extends AbstractController
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function ajouter(): void
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ajoutPatternMois = new AjouterPatternMois($this->entityManager);

                $ajoutPatternMois->execute(
                    $_POST['ligne'],
                    $_POST['mois'],
                    $_POST['sebango'],
                    $_POST['quantite'],
                    $_POST['annee']
                );

                $idLigne = $_POST['ligne'];
                $idUsine = $this->entityManager->getRepository(Ligne::class)->find($idLigne)->getUsine()->getId();

                $mois = $_POST['mois'];
                $annee = $_POST['annee'];

                $this->redirect("/ligne/mois?usine=$idUsine&ligne=$idLigne&annee=$annee&mois=$mois&ajout=succeed");
                return;
            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $this->render('View_PatternMois', [
            'error' => $error ?? null,
            'usines' => $usines,
            'produits' => $produits
        ]);
    }
}
