<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'pattern_mois')]
class PatternMois
{
    #[ORM\Id]
    #[ORM\Column(name: 'id_pattern_mois', type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(name: 'mois', type: 'integer')]
    private int $mois;

    #[ORM\Column(name: 'sebango', type: 'string', length: 4)]
    private string $sebango;

    #[ORM\Column(name: 'quantite', type: 'integer')]
    private int $quantite;

    #[ORM\Column(name: 'annee', type: 'integer')]
    private int $annee;

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

    public function getSebango(): string
    {
        return $this->sebango;
    }

    public function setSebango(string $sebango): void
    {
        $this->sebango = $sebango;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }
}
