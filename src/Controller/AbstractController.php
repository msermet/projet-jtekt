<?php

namespace App\Controller;

abstract class AbstractController
{
    /**
     * Méthode pour rendre un template avec des données.
     *
     * @param string $template Le nom du template à rendre.
     * @param array $data Les données à passer au template.
     */
    protected function render(string $template, array $data = []): void
    {
        // Extrait les variables du tableau $data pour les rendre disponibles dans le template
        extract($data);

        // Démarre la mise en mémoire tampon de sortie
        ob_start();
        // Inclut le fichier de template spécifié
        require __DIR__ . '/../../views/' . $template . '.php';
        // Récupère le contenu du tampon de sortie et l'efface
        $content = ob_get_clean();

        // Inclut le fichier de base qui utilise le contenu du template
        require __DIR__ . '/../../views/base.php';
    }

    /**
     * Méthode pour rediriger vers une URL spécifiée.
     *
     * @param string $url L'URL vers laquelle rediriger.
     */
    protected function redirect(string $url): void
    {
        // Envoie un en-tête HTTP pour rediriger vers l'URL spécifiée
        header("Location: $url");
        // Termine l'exécution du script
        exit;
    }

    /**
     * Méthode pour rendre une page d'erreur.
     *
     * @param int $code Le code d'erreur HTTP (par défaut 404).
     * @param string|null $message Le message d'erreur (optionnel).
     */
    public function renderError(int $code = 404, string $message = null): void
    {
        // Définit le code de réponse HTTP
        http_response_code($code);

        // Si le code d'erreur est 404, rend le template d'erreur 404
        if ($code === 404) {
            $this->render('error/404');
            // Termine l'exécution du script
            exit;
        }
    }
}