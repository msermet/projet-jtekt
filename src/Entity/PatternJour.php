<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'pattern_jour')]
class PatternJour
{
    #[ORM\Id]
    #[ORM\Column(name: 'id_pattern_jour', type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(name: 'jour', type: 'integer')]
    private int $jour;

    #[ORM\Column(name: 'mois', type: 'integer')]
    private int $mois;

    #[ORM\Column(name: 'annee', type: 'integer')]
    private int $annee;

    #[ORM\Column(name: 'sebango', type: 'string', length: 4)]
    private string $sebango;

    #[ORM\Column(name: 'besoin', type: 'integer')]
    private int $besoin;

    #[ORM\Column(name: 'relicat', type: 'integer')]
    private int $relicat;

    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: 'sebango', referencedColumnName: 'sebango')]
    private ?Produit $produit = null;


    // Getters et Setters

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): void
    {
        $this->produit = $produit;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getJour(): int
    {
        return $this->jour;
    }

    public function setJour(int $jour): void
    {
        $this->jour = $jour;
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

    public function getSebango(): string
    {
        return $this->sebango;
    }

    public function setSebango(string $sebango): void
    {
        $this->sebango = $sebango;
    }

    public function getBesoin(): int
    {
        return $this->besoin;
    }

    public function setBesoin(int $besoin): void
    {
        $this->besoin = $besoin;
    }

    public function getRelicat(): int
    {
        return $this->relicat;
    }

    public function setRelicat(int $relicat): void
    {
        $this->relicat = $relicat;
    }

}
