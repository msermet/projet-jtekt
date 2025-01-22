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

    #[ORM\Column(name: 'ligne', type: 'integer')]
    private int $ligne;

    #[ORM\Column(name: 'mois', type: 'datetime')]
    private \DateTime $mois;

    #[ORM\Column(name: 'sebango', type: 'string', length: 4)]
    private string $sebango;

    #[ORM\Column(name: 'quantite', type: 'integer')]
    private int $quantite;

    // Getters et Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLigne(): int
    {
        return $this->ligne;
    }

    public function setLigne(int $ligne): void
    {
        $this->ligne = $ligne;
    }

    public function getMois(): \DateTime
    {
        return $this->mois;
    }

    public function setMois(\DateTime $mois): void
    {
        $this->mois = $mois;
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
