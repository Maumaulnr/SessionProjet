<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\ORM\Mapping as ORM;

// Cette classe est une entité Doctrine ORM associée au référentiel (repository) ProgrammeRepository.
#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    // Ces annotations définissent la propriété $id comme clé primaire auto-incrémentée en base de données.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

     // Cette annotation définit la propriété $nbJours comme une colonne en base de données.
    #[ORM\Column]
    private ?int $nbJours = null;

    // Cette annotation définit une relation "ManyToOne" entre cette entité (Programme) et une autre (Cours).
    // Elle indique que chaque programme est associé à un cours.
    // Le paramètre 'nullable: false' signifie que le cours ne peut pas être nul (obligatoire).
    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cours $cours = null;

    // Cette annotation définit une relation "ManyToOne" entre cette entité (Programme) et une autre (Session).
    // Elle indique que chaque programme est associé à une session.
    // Le paramètre 'nullable: false' signifie que la session ne peut pas être nulle (obligatoire).
    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Session $session = null;

    // Cette méthode permet de récupérer l'ID du programme.
    public function getId(): ?int
    {
        return $this->id;
    }

    // Cette méthode permet de récupérer le nombre de jours du programme
    public function getNbJours(): ?int
    {
        return $this->nbJours;
    }

    // Cette méthode permet de définir le nombre de jours du programme.
    public function setNbJours(int $nbJours): static
    {
        $this->nbJours = $nbJours;

        return $this;
    }

    // Cette méthode permet de récupérer le cours associé au programme.
    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    // Cette méthode permet de définir le cours associé au programme.
    public function setCours(?Cours $cours): static
    {
        $this->cours = $cours;

        return $this;
    }

    // Cette méthode permet de récupérer la session associée au programme.
    public function getSession(): ?Session
    {
        return $this->session;
    }

    // Cette méthode permet de définir la session associée au programme.
    public function setSession(?Session $session): static
    {
        $this->session = $session;

        return $this;
    }

    // __toString
    // Cette méthode spéciale permet de convertir l'objet programme en une chaîne de caractères.
    public function __toString()
    {
        return $this->cours." ".$this->session;
    }
    
}
