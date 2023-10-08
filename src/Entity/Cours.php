<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// Cette annotation indique que cette classe est une entité Doctrine ORM.
// Elle est associée à un référentiel (repository) de type CoursRepository.
// Les entités sont des objets qui représentent des données dans la base de données.
#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    // Ces annotations définissent la propriété $id comme clé primaire auto-incrémentée en base de données.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Cette annotation définit la propriété $nomModule comme une colonne dans la base de données avec une longueur maximale de 100 caractères.
    #[ORM\Column(length: 100)]
    private ?string $nomModule = null;

    // Cette annotation définit une relation "OneToMany" entre cette entité (Cours) et une autre (Programme).
    // Elle indique que chaque cours peut avoir plusieurs programmes associés.
    #[ORM\OneToMany(mappedBy: 'cours', targetEntity: Programme::class)]
    private Collection $programmes;

    // Cette annotation définit une relation "ManyToOne" entre cette entité (Cours) et une autre (Categorie).
    // Elle indique que chaque cours appartient à une catégorie.
    // Le paramètre 'nullable: false' signifie que la catégorie ne peut pas être nulle (obligatoire).
    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    // Le constructeur initialise la collection de programmes comme une ArrayCollection vide.
    public function __construct()
    {
        $this->programmes = new ArrayCollection();
    }

    // Cette méthode permet de récupérer l'ID du cours.
    public function getId(): ?int
    {
        return $this->id;
    }

    // Cette méthode permet de récupérer le nom du module (cours).
    public function getNomModule(): ?string
    {
        return $this->nomModule;
    }

    // Cette méthode permet de définir le nom du module (cours).
    public function setNomModule(string $nomModule): static
    {
        $this->nomModule = $nomModule;

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    // Cette méthode permet de récupérer la collection de programmes associés au cours.
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    // Cette méthode permet d'ajouter un programme à la collection de programmes du cours tout en maintenant la cohérence des relations.
    public function addProgramme(Programme $programme): static
    {
        // Cette condition vérifie si la collection de programmes du cours ne contient pas déjà le programme.
        if (!$this->programmes->contains($programme)) {
            // Si le programme n'est pas déjà dans la collection, on l'ajoute à la collection de programmes du cours.
            $this->programmes->add($programme);
            // On associe ce programme au cours actuel en utilisant la méthode 'setCours'.
            // Cela établit la relation bidirectionnelle entre le cours et le programme.
            $programme->setCours($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): static
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getCours() === $this) {
                $programme->setCours(null);
            }
        }

        // On renvoie l'instance du cours actuel (soit "$this") après avoir effectué les opérations.
        // Cela permet de chaîner d'autres méthodes sur l'objet cours si nécessaire.
        return $this;
    }

    // Cette méthode permet de récupérer la catégorie à laquelle appartient le cours.
    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    // Cette méthode permet de définir la catégorie à laquelle appartient le cours.
    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    // __toString
    // Cette méthode spéciale permet de convertir l'objet cours en une chaîne de caractères.
    // Dans ce cas, elle renvoie le nom du module (cours).
    public function __toString()
    {
        return $this->nomModule;
    }
    
}
