<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// Cette classe est une entité Doctrine ORM associée au référentiel (repository) SessionRepository.
#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    // Ces annotations définissent la propriété $id comme clé primaire auto-incrémentée en base de données.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Cette annotation définit la propriété $nomSession comme une colonne en base de données avec une longueur maximale de 255 caractères.
    #[ORM\Column(length: 255)]
    private ?string $nomSession = null;

    // Cette annotation définit la propriété $dateDebut comme une colonne en base de données de type DATETIME_MUTABLE.
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    // Cette annotation définit la propriété $dateFin comme une colonne en base de données de type DATETIME_MUTABLE.
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    // Cette annotation définit la propriété $placesTheoriques comme une colonne en base de données.
    #[ORM\Column]
    private ?int $placesTheoriques = null;

    // Cette annotation définit une relation "OneToMany" entre cette entité (Session) et une autre (Programme).
    // Elle indique que chaque session peut avoir plusieurs programmes associés.
    // La cascade "persist" signifie que lors de la persistance d'une session, les programmes associés sont également persistés.
    // L'option "orphanRemoval" indique que les programmes orphelins (non associés à une session) seront supprimés.
    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Programme::class, cascade:["persist"], orphanRemoval:true)]
    private Collection $programmes;

    // Cette annotation définit une relation "ManyToOne" entre cette entité (Session) et une autre (Formation).
    // Elle indique que chaque session est associée à une formation.
    #[ORM\ManyToOne(inversedBy: 'sessions')]
    private ?Formation $formation = null;

    // Cette annotation définit une relation "ManyToMany" entre cette entité (Session) et une autre (Stagiaire).
    // Elle indique que chaque session peut avoir plusieurs stagiaires associés.
    // La propriété "mappedBy" indique le nom de la propriété inverse dans l'entité Stagiaire.
    #[ORM\ManyToMany(targetEntity: Stagiaire::class, mappedBy: 'sessions')]
    private Collection $stagiaires;

    // Le constructeur initialise les collections de programmes et de stagiaires comme des ArrayCollection vides.
    public function __construct()
    {
        $this->programmes = new ArrayCollection();
        $this->stagiaires = new ArrayCollection();
    }

    // Cette méthode permet de récupérer l'ID de la session.
    public function getId(): ?int
    {
        return $this->id;
    }

    // Cette méthode permet de récupérer le nom de la session.
    public function getNomSession(): ?string
    {
        return $this->nomSession;
    }

    // Cette méthode permet de définir le nom de la session.
    public function setNomSession(string $nomSession): static
    {
        $this->nomSession = $nomSession;

        return $this;
    }

    // Cette méthode permet de récupérer la date de début de la session.
    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    // Cette méthode permet de définir la date de début de la session.
    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    // Cette méthode permet de récupérer la date de fin de la session.
    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    // Cette méthode permet de définir la date de fin de la session.
    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    // Cette méthode permet de récupérer le nombre de places théoriques de la session.
    public function getPlacesTheoriques(): ?int
    {
        return $this->placesTheoriques;
    }

    // Cette méthode permet de définir le nombre de places théoriques de la session.
    public function setPlacesTheoriques(int $placesTheoriques): static
    {
        $this->placesTheoriques = $placesTheoriques;

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    // Cette méthode permet de récupérer la collection de programmes associés à la session.
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    // Cette méthode permet d'ajouter un programme à la collection de programmes de la session tout en maintenant la cohérence des relations.
    public function addProgramme(Programme $programme): static
    {
        // Vérifie si le programme n'est pas déjà dans la collection de programmes de la session.
        if (!$this->programmes->contains($programme)) {
            // Si le programme n'est pas déjà présent, l'ajoute à la collection de programmes de la session.
            $this->programmes->add($programme);
            // Définit la session actuelle comme la session associée au programme.
            $programme->setSession($this);
        }

        return $this;
    }

    // Cette méthode permet de supprimer un programme de la collection de programmes de la session tout en maintenant la cohérence des relations.
    public function removeProgramme(Programme $programme): static
    {
        // Si le programme est présent dans la collection, il est supprimé.
        // Si le programme est associé à cette session, son association est supprimée.
        if ($this->programmes->removeElement($programme)) {
            // donne au côté propriétaire la valeur null (à moins qu'il n'ait déjà été modifié)
            if ($programme->getSession() === $this) {
                $programme->setSession(null);
            }
        }

        return $this;
    }

    // Cette méthode permet de récupérer la formation associée à la session.
    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    // Cette méthode permet de définir la formation associée à la session.
    public function setFormation(?Formation $formation): static
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection<int, Stagiaire>
     */
    // Cette méthode permet de récupérer la collection de stagiaires associés à la session.
    public function getStagiaires(): Collection
    {
        return $this->stagiaires;
    }

    /**
     * cette méthode permet d'ajouter un stagiaire à la liste des stagiaires d'une session tout en maintenant la cohérence des relations entre les entités. 
     * Elle s'assure que le stagiaire n'est pas ajouté deux fois et que la session est également associée au stagiaire.
     */
    public function addStagiaire(Stagiaire $stagiaire): static
    {
        // Si le stagiaire n'est pas déjà présent dans la collection 
        if (!$this->stagiaires->contains($stagiaire)) {
            // alors on l'ajoute à la collection
            $this->stagiaires->add($stagiaire);
            $stagiaire->addSession($this);
        }

        return $this;
    }

    // Cette méthode removeStagiaire permet de retirer un stagiaire de la collection de stagiaires associés à la session
    public function removeStagiaire(Stagiaire $stagiaire): static
    {
        // Vérifie si le stagiaire est présent dans la collection de stagiaires de la session.
        if ($this->stagiaires->removeElement($stagiaire)) {
            // Si le stagiaire est présent et a été retiré avec succès, supprime la session de la liste des sessions du stagiaire.
            $stagiaire->removeSession($this);
        }

        return $this;
    }

    // __toString
    // Cette méthode spéciale permet de convertir l'objet programme en une chaîne de caractères.
    public function __toString()
    {
        
        return $this->nomSession;

    }

}
