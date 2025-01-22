<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'lignes')]
class Ligne extends Usine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_ligne', type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'nom', type: 'string', length: 255)]
    private string $nom;

    #[ORM\ManyToOne(targetEntity: Usine::class)]
    #[ORM\JoinColumn(name: 'id_usine', referencedColumnName: 'id_usine', onDelete: 'CASCADE')]
    private Usine $usine;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getUsine(): Usine
    {
        return $this->usine;
    }

    public function setUsine(Usine $usine): void
    {
        $this->usine = $usine;
    }


}
