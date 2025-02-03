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

    // Code sebango du produit
    #[ORM\Column(name: 'sebango', type: 'string', length: 4)]
    private string $sebango;

    // Besoin en quantité pour ce jour
    #[ORM\Column(name: 'besoin', type: 'integer')]
    private int $besoin;

    // Quantité de reliquat pour ce jour
    #[ORM\Column(name: 'relicat', type: 'integer')]
    private int $relicat;

    // Produit associé au pattern jour
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

    // Récupère l'identifiant du pattern jour
    public function getId(): int
    {
        return $this->id;
    }

    // Définit l'identifiant du pattern jour
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Récupère le jour du pattern
    public function getJour(): int
    {
        return $this->jour;
    }

    // Définit le jour du pattern
    public function setJour(int $jour): void
    {
        $this->jour = $jour;
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

    // Récupère le besoin en quantité pour ce jour
    public function getBesoin(): int
    {
        return $this->besoin;
    }

    // Définit le besoin en quantité pour ce jour
    public function setBesoin(int $besoin): void
    {
        $this->besoin = $besoin;
    }

    // Récupère la quantité de reliquat pour ce jour
    public function getRelicat(): int
    {
        return $this->relicat;
    }

    // Définit la quantité de reliquat pour ce jour
    public function setRelicat(int $relicat): void
    {
        $this->relicat = $relicat;
    }
}