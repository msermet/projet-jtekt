<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'pattern_mois')]
class PatternMois
{
    // Identifiant unique du pattern mois
    #[ORM\Id]
    #[ORM\Column(name: 'id_pattern_mois', type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    // Mois du pattern
    #[ORM\Column(name: 'mois', type: 'integer')]
    private int $mois;

    // Code sebango du produit
    #[ORM\Column(name: 'sebango', type: 'string', length: 4)]
    private string $sebango;

    // Quantité pour ce mois
    #[ORM\Column(name: 'quantite', type: 'integer')]
    private int $quantite;

    // Année du pattern
    #[ORM\Column(name: 'annee', type: 'integer')]
    private int $annee;

    // Produit associé au pattern mois
    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: 'sebango', referencedColumnName: 'sebango')]
    private ?Produit $produit = null;

    // Getters et Setters

    // Récupère le produit associé
    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    // Définit le produit associé
    public function setProduit(?Produit $produit): void
    {
        $this->produit = $produit;
    }

    // Récupère l'identifiant du pattern mois
    public function getId(): int
    {
        return $this->id;
    }

    // Définit l'identifiant du pattern mois
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Récupère le mois du pattern
    public function getMois(): int
    {
        return $this->mois;
    }

    // Définit le mois du pattern
    public function setMois(int $mois): void
    {
        $this->mois = $mois;
    }

    // Récupère l'année du pattern
    public function getAnnee(): int
    {
        return $this->annee;
    }

    // Définit l'année du pattern
    public function setAnnee(int $annee): void
    {
        $this->annee = $annee;
    }

    // Récupère le code sebango du produit
    public function getSebango(): string
    {
        return $this->sebango;
    }

    // Définit le code sebango du produit
    public function setSebango(string $sebango): void
    {
        $this->sebango = $sebango;
    }

    // Récupère la quantité pour ce mois
    public function getQuantite(): int
    {
        return $this->quantite;
    }

    // Définit la quantité pour ce mois
    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }
}