<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'pattern_jour')]
class PatternJour
{
    // Identifiant unique du pattern jour
    #[ORM\Id]
    #[ORM\Column(name: 'id_pattern_jour', type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    // Jour du pattern
    #[ORM\Column(name: 'jour', type: 'integer')]
    private int $jour;

    // Mois du pattern
    #[ORM\Column(name: 'mois', type: 'integer')]
    private int $mois;

    // Année du pattern
    #[ORM\Column(name: 'annee', type: 'integer')]
    private int $annee;

    // Besoin en quantité pour ce jour
    #[ORM\Column(name: 'besoin', type: 'integer')]
    private int $besoin;

    // Quantité de reliquat pour ce jour
    #[ORM\Column(name: 'relicat', type: 'integer')]
    private int $relicat;

    // Produit associé au pattern jour
    // La clé étrangère est désormais "id_produit" qui référence "id_produit" dans la table produits
    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: 'id_produit', referencedColumnName: 'id_produit', nullable: false)]
    private ?Produit $produit = null;

    // --- Getters et Setters ---

    // Identifiant du pattern jour
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Jour du pattern
    public function getJour(): int
    {
        return $this->jour;
    }

    public function setJour(int $jour): void
    {
        $this->jour = $jour;
    }

    // Mois du pattern
    public function getMois(): int
    {
        return $this->mois;
    }

    public function setMois(int $mois): void
    {
        $this->mois = $mois;
    }

    // Année du pattern
    public function getAnnee(): int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): void
    {
        $this->annee = $annee;
    }

    // Besoin en quantité pour ce jour
    public function getBesoin(): int
    {
        return $this->besoin;
    }

    public function setBesoin(int $besoin): void
    {
        $this->besoin = $besoin;
    }

    // Quantité de reliquat pour ce jour
    public function getRelicat(): int
    {
        return $this->relicat;
    }

    public function setRelicat(int $relicat): void
    {
        $this->relicat = $relicat;
    }

    // Produit associé au pattern jour
    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): void
    {
        $this->produit = $produit;
    }
}
