<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'usines')]
class Usine
{
    // Identifiant unique de l'usine
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_usine', type: 'integer')]
    private int $id;

    // Nom de l'usine
    #[ORM\Column(name: 'nom', type: 'string', length: 255)]
    private string $nom;

    // Collection de lignes de production associées à l'usine
    #[ORM\OneToMany(mappedBy: 'usine', targetEntity: Ligne::class, cascade: ['persist', 'remove'])]
    private Collection $lignes;

    // Constructeur de la classe Usine
    public function __construct()
    {
        // Initialisation de la collection de lignes
        $this->lignes = new ArrayCollection();
    }

    // Récupère l'identifiant de l'usine
    public function getId(): int
    {
        return $this->id;
    }

    // Définit l'identifiant de l'usine
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Récupère le nom de l'usine
    public function getNom(): string
    {
        return $this->nom;
    }

    // Définit le nom de l'usine
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * Récupère la collection de lignes de production associées à l'usine
     * @return Collection|Ligne[]
     */
    public function getLignes(): Collection
    {
        return $this->lignes;
    }

    // Ajoute une ligne de production à l'usine
    public function addLigne(Ligne $ligne): self
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes[] = $ligne;
            $ligne->setUsine($this);
        }

        return $this;
    }

    // Supprime une ligne de production de l'usine
    public function removeLigne(Ligne $ligne): self
    {
        if ($this->lignes->contains($ligne)) {
            $this->lignes->removeElement($ligne);
            // Met à jour la relation côté ligne de production
            if ($ligne->getUsine() === $this) {
                $ligne->setUsine(null);
            }
        }

        return $this;
    }
}