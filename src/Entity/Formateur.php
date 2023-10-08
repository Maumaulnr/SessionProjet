<?php

namespace App\Entity;

use App\Repository\FormateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// Cette classe est une entité Doctrine ORM associée au référentiel (repository) FormateurRepository.
// Elle implémente les interfaces UserInterface et PasswordAuthenticatedUserInterface.
#[ORM\Entity(repositoryClass: FormateurRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
class Formateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Ces annotations définissent la propriété $id comme clé primaire auto-incrémentée en base de données.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Cette annotation définit la propriété $email comme une colonne dans la base de données avec une longueur maximale de 180 caractères et une contrainte d'unicité.
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    // Cette annotation définit la propriété $roles comme une colonne en base de données.
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    // Cette annotation définit la propriété $password comme une colonne en base de données. Elle stocke le mot de passe haché de l'utilisateur.
    #[ORM\Column]
    private ?string $password = null;

    // Cette annotation définit la propriété $nom comme une colonne en base de données avec une longueur maximale de 50 caractères.
    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    // Cette annotation définit la propriété $prenom comme une colonne en base de données avec une longueur maximale de 50 caractères.
    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    // Cette annotation définit la propriété $isVerified comme une colonne en base de données de type boolean.
    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    // Cette méthode permet de récupérer l'ID de l'utilisateur.
    public function getId(): ?int
    {
        return $this->id;
    }

    // Cette méthode permet de récupérer l'adresse email de l'utilisateur.
    public function getEmail(): ?string
    {
        return $this->email;
    }

    // Cette méthode permet de définir l'adresse email de l'utilisateur.
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    // Cette méthode retourne une représentation visuelle de l'utilisateur, en l'occurrence, son adresse email.
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    // Cette méthode retourne les rôles de l'utilisateur.
    public function getRoles(): array
    {
        // On copie les rôles actuels de l'utilisateur.
        $roles = $this->roles;
        // Garantit que chaque utilisateur a au moins le rôle ROLE_USER.
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    // Cette méthode permet de définir les rôles de l'utilisateur.
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    // Cette méthode retourne le mot de passe haché de l'utilisateur.
    public function getPassword(): string
    {
        return $this->password;
    }

    // Cette méthode permet de définir le mot de passe haché de l'utilisateur.
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    // Cette méthode permet d'effacer des informations temporaires sensibles de l'utilisateur, si elles existent.
    public function eraseCredentials(): void
    {
        // Si vous stockez des données temporaires sensibles sur l'utilisateur, vous pouvez les effacer ici.
        // $this->plainPassword = null;
    }

    // Cette méthode permet de récupérer le nom de l'utilisateur.
    public function getNom(): ?string
    {
        return $this->nom;
    }

    // Cette méthode permet de définir le nom de l'utilisateur.
    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    // Cette méthode permet de récupérer le prénom de l'utilisateur.
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    // Cette méthode permet de définir le prénom de l'utilisateur.
    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    // Cette méthode permet de vérifier si l'utilisateur est vérifié (par exemple, par e-mail).
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    // Cette méthode permet de définir si l'utilisateur est vérifié.
    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    
}
