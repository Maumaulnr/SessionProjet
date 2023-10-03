<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomSession = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column]
    private ?int $placesTheoriques = null;

    // #[ORM\Column]
    // private ?int $nbPlace;


    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Programme::class, cascade:["persist"], orphanRemoval:true)]
    private Collection $programmes;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    private ?Formation $formation = null;

    #[ORM\ManyToMany(targetEntity: Stagiaire::class, mappedBy: 'sessions')]
    private Collection $stagiaires;

    public function __construct()
    {
        $this->programmes = new ArrayCollection();
        $this->stagiaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSession(): ?string
    {
        return $this->nomSession;
    }

    public function setNomSession(string $nomSession): static
    {
        $this->nomSession = $nomSession;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPlacesTheoriques(): ?int
    {
        return $this->placesTheoriques;
    }

    public function setPlacesTheoriques(int $placesTheoriques): static
    {
        $this->placesTheoriques = $placesTheoriques;

        return $this;
    }

    // Calculer le nombre de places

    // public function getNbPlace(): ?int
    // {
    //     return $this->nbPlace;
    // }

    // public function setNbPlace(int $nbPlace): self
    // {
    //     $this->nbPlace = $nbPlace;

    //     return $this;
    // }

    // public function getNbPlacesReservees()
    // {
    //     $reservees = $this->nbPlace - count($this->stagiaires);
    // }

    // public function getNbPlacesRestantes()
    // {
    //     $reservees = $this->nbPlace - count($this->stagiaires);
    //     $restantes = $this->nbPlace - $reservees;

    //     return $restantes;
    // }


    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): static
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes->add($programme);
            $programme->setSession($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): static
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getSession() === $this) {
                $programme->setSession(null);
            }
        }

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): static
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection<int, Stagiaire>
     */
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

    public function removeStagiaire(Stagiaire $stagiaire): static
    {
        if ($this->stagiaires->removeElement($stagiaire)) {
            $stagiaire->removeSession($this);
        }

        return $this;
    }

    public function __toString()
    {
        
        return $this->nomSession;

    }


}
