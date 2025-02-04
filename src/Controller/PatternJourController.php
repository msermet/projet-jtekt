<?php

namespace App\Controller;

use App\Entity\Ligne;
use App\Entity\PatternJour;
use App\Entity\Produit;
use App\Entity\User;
use App\Entity\Usine;
use App\UserStory\AjouterPatternJour;
use App\UserStory\EditPatternJour;
use Doctrine\ORM\EntityManager;

class PatternJourController extends AbstractController
{
    // Propriété pour stocker l'instance de l'EntityManager
    private EntityManager $entityManager;

    // Constructeur pour initialiser l'EntityManager
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    // Méthode pour ajouter un pattern jour
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

        // Récupère toutes les usines et produits depuis la base de données
        $usines = $this->entityManager->getRepository(Usine::class)->findAll();
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();

        // Vérifie si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Crée un nouveau pattern jour
                $ajoutPatternJour = new AjouterPatternJour($this->entityManager);

                // Exécute l'ajout du pattern jour avec les données du formulaire
                $ajoutPatternJour->execute(
                    $_POST['ligne'],
                    $_POST['jour'],
                    $_POST['mois'],
                    $_POST['annee'],
                    $_POST['sebango'],
                    $_POST['besoin'],
                    $_POST['relicat']
                );

                // Récupère l'ID de la ligne et de l'usine associée
                $idLigne = $_POST['ligne'];
                $idUsine = $this->entityManager->getRepository(Ligne::class)->find($idLigne)->getUsine()->getId();

                // Récupère la date du pattern jour
                $jour = $_POST['jour'];
                $mois = $_POST['mois'];
                $annee = $_POST['annee'];

                // Redirige vers la page de la ligne avec les paramètres de la date et un message de succès
                $this->redirect("/ligne/jour?usine=$idUsine&ligne=$idLigne&annee=$annee&mois=$mois&jour=$jour&ajout=succeed");
                return;
            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                // Gère les erreurs de connexion à la base de données
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                // Gère les autres exceptions
                $error = $e->getMessage();
            }
        }

        // Rend le template 'View_PatternJour' avec les données des usines, produits et les éventuelles erreurs
        $this->render('View_PatternJour', [
            'error' => $error ?? null,
            'usines' => $usines,
            'produits' => $produits,
            'userLogged' => $userLogged ?? null,
        ]);
    }

    // Méthode pour modifier un pattern jour
    public function modifier(): void
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

        // Récupère toutes les usines, produits et patterns jour depuis la base de données
        $usines = $this->entityManager->getRepository(Usine::class)->findAll();
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();
        $patternJour = $this->entityManager->getRepository(PatternJour::class)->findAll();

        // Vérifie si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Modifie un pattern jour existant
                $editPatternJour = new EditPatternJour($this->entityManager);

                // Exécute la modification du pattern jour avec les données du formulaire
                $editPatternJour->execute(
                    $_POST['ligne'],
                    $_POST['jour'],
                    $_POST['mois'],
                    $_POST['sebango'] ?? [],
                    $_POST['besoin'] ?? [],
                    $_POST['relicat'] ?? [],
                    $_POST['annee']
                );

                // Récupère l'ID de la ligne et de l'usine associée
                $idLigne = $_POST['ligne'];
                $idUsine = $this->entityManager->getRepository(Ligne::class)->find($idLigne)->getUsine()->getId();

                // Récupère la date du pattern jour
                $jour = $_POST['jour'];
                $mois = $_POST['mois'];
                $annee = $_POST['annee'];

                // Redirige vers la page de modification de la ligne avec les paramètres de la date et un message de succès
                $this->redirect("/ligne/edit/jour?usine=$idUsine&ligne=$idLigne&annee=$annee&mois=$mois&jour=$jour&ajout=succeed");
                return;
            } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
                // Gère les erreurs de connexion à la base de données
                $error = "Le serveur de base de données est actuellement indisponible. Veuillez réessayer plus tard.";
            } catch (\Exception $e) {
                // Gère les autres exceptions
                $error = $e->getMessage();
            }
        }

        // Rend le template 'View_EditPatternJour' avec les données des usines, produits, patterns jour et les éventuelles erreurs
        $this->render('View_EditPatternJour', [
            'error' => $error ?? null,
            'usines' => $usines,
            'produits' => $produits,
            'patternJour' => $patternJour,
            'userLogged' => $userLogged ?? null,
        ]);
    }
}