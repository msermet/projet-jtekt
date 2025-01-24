<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\Ligne;
use App\Entity\Usine;
use App\UserStory\AjouterProduit;
use Doctrine\ORM\EntityManager;

class ProduitController extends AbstractController
{
    private EntityManager $entityManager;

    /**
     * @param EntityManager $entityManager
     */
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $createAccount = new AjouterProduit($this->entityManager);
                $createAccount->execute(
                    $_POST['sebango'],
                    $_POST['article'],
                    $_POST['designation'],
                    $_POST['ligne']
                );

                $idLigne = $_POST['ligne'];
                $idUsine = $this->entityManager->getRepository(Ligne::class)->find($idLigne)->getUsine()->getId();
                $this->redirect("/pattern/ajouterproduit?usine=$idUsine&ligne=$idLigne&ajout=succeed");
                return;
            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }
        $this->render('View_Produit', [
            'error' => $error ?? null,
            'usines' => $usines ?? []
        ]);
    }
}