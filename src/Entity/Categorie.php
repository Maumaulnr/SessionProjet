<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// Cette annotation indique que cette classe est une entité Doctrine ORM.
// Elle est associée à un référentiel (repository) de type CategorieRepository.
// Les entités sont des objets qui représentent des données dans la base de données.
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{

    // Cette annotation définit la propriété $id comme la clé primaire de l'entité.
    // Elle est également générée automatiquement (auto-increment).
    // La colonne correspondante en base de données est créée.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Cette annotation définit la propriété $nomCategorie comme une colonne dans la base de données.
    // La colonne a une longueur maximale de 100 caractères.
    #[ORM\Column(length: 100)]
    private ?string $nomCategorie = null;

    // Cette annotation définit une relation "OneToMany" entre cette entité (Categorie) et une autre (Cours).
    // Elle indique que chaque catégorie peut avoir plusieurs cours associés.
    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Cours::class, orphanRemoval: true)]
    private Collection $cours;

    // Le constructeur initialise la collection de cours comme une ArrayCollection vide.
    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }

    // Cette méthode permet de récupérer l'ID de la catégorie.
    public function getId(): ?int
    {
        return $this->id;
    }

    // Cette méthode permet de récupérer le nom de la catégorie.
    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    // Cette méthode permet de définir le nom de la catégorie.
    public function setNomCategorie(string $nomCategorie): static
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    // Cette méthode permet de récupérer la collection de cours associés à la catégorie.
    public function getCours(): Collection
    {
        return $this->cours;
    }

    // Cette méthode permet d'ajouter un cours à la collection de cours de la catégorie.
    public function addCour(Cours $cour): static
    {
        // Cette condition vérifie si la collection de cours de la catégorie ne contient pas déjà le cours.
        if (!$this->cours->contains($cour)) {
            // Si le cours n'est pas déjà dans la collection, on l'ajoute à la collection de cours de la catégorie.
            $this->cours->add($cour);
            // On associe ce cours à la catégorie actuelle en utilisant la méthode 'setCategorie'.
            // Cela établit la relation bidirectionnelle entre la catégorie et le cours.
            $cour->setCategorie($this);
        }

        // On renvoie l'instance de la catégorie actuelle (soit "$this") après avoir effectué les opérations.
        // Cela permet de chaîner d'autres méthodes sur l'objet catégorie si nécessaire.
        return $this;
    }

    // Si le cours est présent dans la collection, il est supprimé.
    // Si le cours est associé à cette catégorie, son association est supprimée.
    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // donne au côté propriétaire la valeur null (à moins qu'il n'ait déjà été modifié)
            if ($cour->getCategorie() === $this) {
                $cour->setCategorie(null);
            }
        }

        // Cette méthode permet de supprimer un cours de la collection de cours de la catégorie.
        return $this;
    }

    // __toString
    // Cette méthode spéciale permet de convertir l'objet catégorie en une chaîne de caractères.
    // Dans ce cas, elle renvoie le nom de la catégorie.
    public function __toString()
    {
        return $this->nomCategorie;
    }

}
