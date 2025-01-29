<?php

namespace App\Controller;

use App\Entity\Ligne;
use App\Entity\PatternJour;
use App\Entity\Produit;
use App\Entity\Usine;
use App\UserStory\AjouterPatternJour;
use App\UserStory\EditPatternJour;
use Doctrine\ORM\EntityManager;

class PatternJourController extends AbstractController
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
                $ajoutPatternJour = new AjouterPatternJour($this->entityManager);

                $ajoutPatternJour->execute(
                    $_POST['ligne'],
                    $_POST['jour'],
                    $_POST['mois'],
                    $_POST['annee'],
                    $_POST['sebango'],
                    $_POST['besoin'],
                    $_POST['relicat']
                );

                $idLigne = $_POST['ligne'];
                $idUsine = $this->entityManager->getRepository(Ligne::class)->find($idLigne)->getUsine()->getId();

                $jour = $_POST['jour'];
                $mois = $_POST['mois'];
                $annee = $_POST['annee'];

                $this->redirect("/ligne/jour?usine=$idUsine&ligne=$idLigne&annee=$annee&mois=$mois&jour=$jour&ajout=succeed");
                return;
            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $this->render('View_PatternJour', [
            'error' => $error ?? null,
            'usines' => $usines,
            'produits' => $produits
        ]);
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
        $patternJour = $this->entityManager->getRepository(PatternJour::class)->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $editPatternJour = new EditPatternJour($this->entityManager);

                $editPatternJour->execute(
                    $_POST['ligne'],
                    $_POST['jour'],
                    $_POST['mois'],
                    $_POST['sebango'] ?? [],
                    $_POST['besoin'] ?? [],
                    $_POST['relicat'] ?? [],
                    $_POST['annee']
                );

                $idLigne = $_POST['ligne'];
                $idUsine = $this->entityManager->getRepository(Ligne::class)->find($idLigne)->getUsine()->getId();

                $jour = $_POST['jour'];
                $mois = $_POST['mois'];
                $annee = $_POST['annee'];

                $this->redirect("/ligne/edit/jour?usine=$idUsine&ligne=$idLigne&annee=$annee&mois=$mois&jour=$jour&ajout=succeed");
                return;
            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $this->render('View_EditPatternJour', [
            'error' => $error ?? null,
            'usines' => $usines,
            'produits' => $produits,
            'patternJour' => $patternJour
        ]);
    }
}
