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

    // Quantité pour ce mois
    #[ORM\Column(name: 'quantite', type: 'integer')]
    private int $quantite;

    // Année du pattern
    #[ORM\Column(name: 'annee', type: 'integer')]
    private int $annee;

    // Produit associé au pattern mois
    // La clé étrangère est désormais "id_produit" qui référence "id_produit" dans la table produits.
    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: 'id_produit', referencedColumnName: 'id_produit', nullable: false)]
    private ?Produit $produit = null;

    // Getters et Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getMois(): int
    {
        return $this->mois;
    }

    public function setMois(int $mois): void
    {
        $this->mois = $mois;
    }

    public function getAnnee(): int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): void
    {
        $this->annee = $annee;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): void
    {
        $this->produit = $produit;
    }
}
