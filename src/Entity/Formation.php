<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// Cette classe est une entité Doctrine ORM associée au référentiel (repository) FormationRepository.
#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    // Ces annotations définissent la propriété $id comme clé primaire auto-incrémentée en base de données.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Cette annotation définit la propriété $nomFormation comme une colonne en base de données avec une longueur maximale de 100 caractères.
    #[ORM\Column(length: 100)]
    private ?string $nomFormation = null;

    // Cette annotation définit une relation "OneToMany" entre cette entité (Formation) et une autre (Session).
    // Elle indique que chaque formation peut avoir plusieurs sessions associées.
    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Session::class)]
    private Collection $sessions;

    // Le constructeur initialise la collection de sessions comme une ArrayCollection vide.
    public function __construct()
    {
        $this->sessions = new ArrayCollection();
    }

    // Cette méthode permet de récupérer l'ID de la formation.
    public function getId(): ?int
    {
        return $this->id;
    }

    // Cette méthode permet de récupérer le nom de la formation.
    public function getNomFormation(): ?string
    {
        return $this->nomFormation;
    }

    // Cette méthode permet de définir le nom de la formation.
    public function setNomFormation(string $nomFormation): static
    {
        $this->nomFormation = $nomFormation;

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    // Cette méthode permet de récupérer la collection de sessions associées à la formation.
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    // Cette méthode permet d'ajouter une session à la collection de sessions de la formation tout en maintenant la cohérence des relations.
    public function addSession(Session $session): static
    {
        // Cette condition vérifie si la collection de sessions de la formation ne contient pas déjà la session.
        if (!$this->sessions->contains($session)) {
            // Si la session n'est pas déjà dans la collection, on l'ajoute à la collection de sessions de la formation.
            $this->sessions->add($session);
            // On associe cette session à la formation actuelle en utilisant la méthode 'setFormation'.
            // Cela établit la relation bidirectionnelle entre la formation et la session.
            $session->setFormation($this);
        }

        // On renvoie l'instance de la formation actuelle (soit "$this") après avoir effectué les opérations.
        // Cela permet de chaîner d'autres méthodes sur l'objet formation si nécessaire.
        return $this;
    }

    // Cette méthode permet de supprimer une session de la collection de sessions de la formation tout en maintenant la cohérence des relations.
    public function removeSession(Session $session): static
    {
        // Si la session est présente dans la collection, elle est supprimée.
        // Si la session est associée à cette formation, son association est supprimée.
        if ($this->sessions->removeElement($session)) {
            // donne au côté propriétaire la valeur null (à moins qu'il n'ait déjà été modifié)
            if ($session->getFormation() === $this) {
                $session->setFormation(null);
            }
        }

        return $this;
    }

    // __toString
    // Cette méthode spéciale permet de convertir l'objet formation en une chaîne de caractères.
    public function __toString()
    {
        return $this->nomFormation;
    }
}
