<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'produits')]
class Produit
{
    #[ORM\Id]
    #[ORM\Column(name: 'sebango', type: 'string', length: 4)]
    private string $sebango;

    #[ORM\Column(name: 'article', type: 'string', length: 10)]
    private string $article;

    #[ORM\Column(name: 'designation', type: 'string', length: 50)]
    private string $designation;

    #[ORM\Column(name: 'ligne', type: 'integer')]
    private int $ligne;

    public function getSebango(): string
    {
        return $this->sebango;
    }

    public function setSebango(string $sebango): void
    {
        $this->sebango = $sebango;
    }

    public function getArticle(): string
    {
        return $this->article;
    }

    public function setArticle(string $article): void
    {
        $this->article = $article;
    }

    public function getDesignation(): string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): void
    {
        $this->designation = $designation;
    }

    public function getLigne(): int
    {
        return $this->ligne;
    }

    public function setLigne(string $ligne): void
    {
        $this->ligne = $ligne;
    }
}
