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

    // Nom de l'utilisateur
    #[ORM\Column(name: 'nom_user', type: 'string', length: 50)]
    private string $nom;

    // Prénom de l'utilisateur
    #[ORM\Column(name: 'prenom_user', type: 'string', length: 50)]
    private string $prenom;

    // Adresse email de l'utilisateur
    #[ORM\Column(name: 'email_user', type: 'string', length: 100, unique: true)]
    private string $email;

    // Mot de passe de l'utilisateur
    #[ORM\Column(name: 'password_user', type: 'string')]
    private string $password;

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

    // Récupère le nom de l'utilisateur
    public function getNom(): string
    {
        return $this->nom;
    }

    // Définit le nom de l'utilisateur
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    // Récupère le prénom de l'utilisateur
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    // Définit le prénom de l'utilisateur
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
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