<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'lignes')]
class Ligne
{
    // Identifiant unique de la ligne
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_ligne', type: 'integer')]
    private int $id;

    // Nom de la ligne
    #[ORM\Column(name: 'nom', type: 'string', length: 255)]
    private string $nom;

    // Usine associée à la ligne
    #[ORM\ManyToOne(targetEntity: Usine::class, inversedBy: 'lignes')]
    #[ORM\JoinColumn(name: 'id_usine', referencedColumnName: 'id_usine', onDelete: 'CASCADE')]
    private Usine $usine;

    // Récupère l'identifiant de la ligne
    public function getId(): int
    {
        return $this->id;
    }

    // Définit l'identifiant de la ligne
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Récupère le nom de la ligne
    public function getNom(): string
    {
        return $this->nom;
    }

    // Définit le nom de la ligne
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    // Récupère l'usine associée à la ligne
    public function getUsine(): Usine
    {
        return $this->usine;
    }

    // Définit l'usine associée à la ligne
    public function setUsine(Usine $usine): void
    {
        $this->usine = $usine;
    }
}