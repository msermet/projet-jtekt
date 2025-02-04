<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\Ligne;
use App\Entity\User;
use App\Entity\Usine;
use App\UserStory\AjouterProduit;
use Doctrine\ORM\EntityManager;

class ProduitController extends AbstractController
{
    // Propriété pour stocker l'instance de l'EntityManager
    private EntityManager $entityManager;

    /**
     * Constructeur pour initialiser l'EntityManager
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * Méthode pour ajouter un produit
     */
    public function ajouter(): void
    {
        // Démarre une session si aucune session n'est déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Redirige vers une page d'erreur si l'utilisateur est déjà connecté
        if (isset($_SESSION['id'])) {
            // Récupère l'utilisateur connecté pour l'afficher dans la vue
            $idUser = $_SESSION['id'];
            $userLogged = $this->entityManager->getRepository(User::class)->find($idUser);
        } else {
            header("Location: /connexion?erreur=connexion");
            exit;
        }

        // Récupère toutes les usines depuis la base de données
        $usines = $this->entityManager->getRepository(Usine::class)->findAll();

        // Vérifie si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Crée un nouveau produit
                $createAccount = new AjouterProduit($this->entityManager);
                $createAccount->execute(
                    $_POST['sebango'],
                    $_POST['article'],
                    $_POST['designation'],
                    $_POST['ligne']
                );

                // Récupère l'ID de la ligne et de l'usine associée
                $idLigne = $_POST['ligne'];
                $idUsine = $this->entityManager->getRepository(Ligne::class)->find($idLigne)->getUsine()->getId();

                // Redirige vers la page de la ligne avec un message de succès
                $this->redirect("/ligne/ajouterproduit?usine=$idUsine&ligne=$idLigne&ajout=succeed");
                return;
            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                // Gère les erreurs de connexion à la base de données
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                // Gère les autres exceptions
                $error = $e->getMessage();
            }
        }

        // Rend le template 'View_Produit' avec les données des usines et les éventuelles erreurs
        $this->render('View_Produit', [
            'error' => $error ?? null,
            'usines' => $usines ?? [],
            'userLogged' => $userLogged ?? null,
        ]);
    }
}