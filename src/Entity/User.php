<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    // Identifiant unique de l'utilisateur
    #[ORM\Id]
    #[ORM\Column(name: 'id_user', type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    // Identifiant de l'utilisateur
    #[ORM\Column(name: 'identifiant_user', type: 'string', length: 50)]
    private string $identifiant;

    // Adresse email de l'utilisateur
    #[ORM\Column(name: 'email_user', type: 'string', length: 100, unique: true)]
    private string $email;

    // Mot de passe de l'utilisateur
    #[ORM\Column(name: 'password_user', type: 'string')]
    private string $password;

    // Statut administrateur (1 = admin, 0 = utilisateur normal)
    #[ORM\Column(name: 'admin', type: 'boolean', options: ['default' => false])]
    private bool $admin = false;

    // Propriété pour le champ mdpOublie
    #[ORM\Column(name: 'mdpOublie', type: 'boolean', options: ['default' => false])]
    private bool $mdpOublie = false;

    // Getter pour mdpOublie
    public function isMdpOublie(): bool
    {
        return $this->mdpOublie;
    }

    // Setter pour mdpOublie
    public function setMdpOublie(bool $mdpOublie): void
    {
        $this->mdpOublie = $mdpOublie;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }

    // Récupère l'identifiant de l'utilisateur
    public function getId(): int
    {
        return $this->id;
    }

    // Définit l'identifiant de l'utilisateur
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Récupère l'identifiant de l'utilisateur
    public function getIdentifiant(): string
    {
        return $this->identifiant;
    }

    // Définit l'identifiant de l'utilisateur
    public function setIdentifiant(string $identifiant): void
    {
        $this->identifiant = $identifiant;
    }

    // Récupère l'adresse email de l'utilisateur
    public function getEmail(): string
    {
        return $this->email;
    }

    // Définit l'adresse email de l'utilisateur
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    // Récupère le mot de passe de l'utilisateur
    public function getPassword(): string
    {
        return $this->password;
    }

    // Définit le mot de passe de l'utilisateur
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}