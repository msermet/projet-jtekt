<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'produits')]
class Produit
{
    // Nouvelle clé primaire auto-incrémentée
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_produit', type: 'integer')]
    private int $idProduit;

    // Ancienne valeur, conservée mais non clé primaire
    #[ORM\Column(name: 'sebango', type: 'string', length: 4)]
    private string $sebango;

    // Code article du produit
    #[ORM\Column(name: 'article', type: 'string', length: 10)]
    private string $article;

    // Désignation du produit
    #[ORM\Column(name: 'designation', type: 'string', length: 50)]
    private string $designation;

    // Identifiant de la ligne de production associée au produit
    #[ORM\Column(name: 'ligne', type: 'integer')]
    private int $ligne;

    // --- Getters et Setters ---

    // Récupère l'identifiant auto-incrémenté (id_produit)
    public function getIdProduit(): int
    {
        return $this->idProduit;
    }

    // Définit l'identifiant auto-incrémenté (rarement utilisé manuellement)
    public function setIdProduit(int $idProduit): void
    {
        $this->idProduit = $idProduit;
    }

    // Récupère le code sebango
    public function getSebango(): string
    {
        return $this->sebango;
    }

    // Définit le code sebango
    public function setSebango(string $sebango): void
    {
        $this->sebango = $sebango;
    }

    // Récupère le code article du produit
    public function getArticle(): string
    {
        return $this->article;
    }

    // Définit le code article du produit
    public function setArticle(string $article): void
    {
        $this->article = $article;
    }

    // Récupère la désignation du produit
    public function getDesignation(): string
    {
        return $this->designation;
    }

    // Définit la désignation du produit
    public function setDesignation(string $designation): void
    {
        $this->designation = $designation;
    }

    // Récupère l'identifiant de la ligne de production associée au produit
    public function getLigne(): int
    {
        return $this->ligne;
    }

    // Définit l'identifiant de la ligne de production associée au produit
    public function setLigne(int $ligne): void
    {
        $this->ligne = $ligne;
    }
}
