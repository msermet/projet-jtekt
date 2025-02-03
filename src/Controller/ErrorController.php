<?php

namespace App\Controller;

class ErrorController extends AbstractController
{
    /**
     * Méthode pour gérer les erreurs 404 (page non trouvée).
     */
    public function error404(): void
    {
        // Appelle la méthode renderError de la classe parente pour afficher une page d'erreur 404
        $this->renderError(404);
    }
}