<?php

namespace App\Entity;

use App\Repository\StagiaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// Cette annotation indique que cette classe est une entité gérée par Doctrine ORM.
// Elle est généralement associée à une table dans une base de données.
#[ORM\Entity(repositoryClass: StagiaireRepository::class)]
class Stagiaire
{
    // Ces annotations indiquent que la propriété $id est la clé primaire de l'entité.
    // Elle est générée automatiquement et stockée dans une colonne de base de données.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Cette annotation indique que la propriété $genre est mappée sur une colonne de base de données de longueur maximale 20.
    #[ORM\Column(length: 20)]
    private ?string $genre = null;

    // Cette annotation indique que la propriété $nom est mappée sur une colonne de base de données de longueur maximale 50.
    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    // Cette annotation indique que la propriété $prenom est mappée sur une colonne de base de données de longueur maximale 50.
    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    // Cette annotation indique que la propriété $ville est mappée sur une colonne de base de données de longueur maximale 50.
    #[ORM\Column(length: 50)]
    private ?string $ville = null;

    // Cette annotation indique que la propriété $dateNaissance est mappée sur une colonne de base de données de type DATETIME_MUTABLE.
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    // Cette annotation indique que la propriété $email est mappée sur une colonne de base de données de longueur maximale 255.
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    // Cette annotation indique que la propriété $telephone est mappée sur une colonne de base de données de longueur maximale 50.
    #[ORM\Column(length: 50)]
    private ?string $telephone = null;

    // Cette annotation indique que la propriété $sessions est une relation Many-to-Many avec l'entité Session.
    #[ORM\ManyToMany(targetEntity: Session::class, inversedBy: 'stagiaires')]
    private Collection $sessions;

    // Le constructeur initialise la propriété $sessions en tant qu'instance d'ArrayCollection.
    public function __construct()
    {
        $this->sessions = new ArrayCollection();
    }

    // Cette méthode retourne la valeur de la propriété $id.
    public function getId(): ?int
    {
        return $this->id;
    }

    // Cette méthode permet de récupérer le genre.
    public function getGenre(): ?string
    {
        return $this->genre;
    }

    // Cette méthode permet de définir le genre.
    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    // Cette méthode permet de récupérer le nom du stagiaire.
    public function getNom(): ?string
    {
        return $this->nom;
    }

    // Cette méthode permet de définir le nom du stagiaire.
    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    // Cette méthode permet de récupérer le prénom du stagiaire
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    // Cette méthode permet de définir le prénom du stagiaire.
    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    // Cette méthode permet de récupérer la ville du stagiaire
    public function getVille(): ?string
    {
        return $this->ville;
    }

    // Cette méthode permet de définir la ville du stagiaire.
    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    // Cette méthode permet de récupérer la date de naissance du stagiaire
    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    // Cette méthode permet de définir la date de naissance du stagiaire.
    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    // Cette méthode permet de récupérer l'email du stagiaire
    public function getEmail(): ?string
    {
        return $this->email;
    }

    // Cette méthode permet de définir l'email du stagiaire.
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    // Cette méthode permet de récupérer le téléphone du stagiaire
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    // Cette méthode permet de définir le téléphone du stagiaire.
    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }


    /**
     * @return Collection<int, Session>
     */
    // Cette méthode retourne la collection de sessions associées à cet objet Stagiaire.
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    // Cette méthode permet d'ajouter une session à la collection de sessions associées à cet objet Stagiaire.
    public function addSession(Session $session): static
    {
        // Cette ligne vérifie si la session spécifiée n'est pas déjà présente dans la collection.
        if (!$this->sessions->contains($session)) {
            // Si la session n'est pas déjà présente, elle est ajoutée à la collection.
            $this->sessions->add($session);
        }

        // Enfin, la méthode retourne l'objet Stagiaire lui-même (via "$this") après l'ajout de la session.
        // Cela permet de chaîner d'autres opérations sur l'objet Stagiaire si nécessaire, grâce à la méthode fluide.
        return $this;
    }

    // Cette méthode permet de retirer une session de la collection de sessions associées à cet objet Stagiaire.
    public function removeSession(Session $session): static
    {
        // Cette ligne supprime la session spécifiée de la collection.
        $this->sessions->removeElement($session);

        // Enfin, la méthode retourne l'objet Stagiaire lui-même (via "$this") après la suppression de la session.
        // Cela permet de chaîner d'autres opérations sur l'objet Stagiaire si nécessaire, grâce à la méthode fluide.
        return $this;
    }

    // Cette méthode spéciale permet de définir comment l'objet Stagiaire doit être converti en chaîne de caractères.
    // Dans ce cas, elle retourne le prénom suivi du nom.
    public function __toString()
    {
        
        return $this->prenom." ".$this->nom;

    }

}
