<?php

namespace App\Controller;
class AccueilController extends AbstractController
{
    public function index(): void
    {
        $this->render('View_Home');
    }

}
