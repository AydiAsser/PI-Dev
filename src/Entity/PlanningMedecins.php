<?php


namespace App\Entity;


use App\Repository\PlanningMedecinsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: PlanningMedecinsRepository::class)]
class PlanningMedecins
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $jour_debut = null;


    #[ORM\Column(length: 255)]
    private ?string $jour_fin = null;


    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure_debut = null;


    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure_fin = null;


    #[ORM\Column]
    private ?bool $disponibilite = null;


   


    #[ORM\ManyToOne(inversedBy: 'planningMedecins')]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getJourDebut(): ?string
    {
        return $this->jour_debut;
    }


    public function setJourDebut(string $jour_debut): static
    {
        $this->jour_debut = $jour_debut;


        return $this;
    }


    public function getJourFin(): ?string
    {
        return $this->jour_fin;
    }


    public function setJourFin(string $jour_fin): static
    {
        $this->jour_fin = $jour_fin;


        return $this;
    }


    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heure_debut;
    }


    public function setHeureDebut(\DateTimeInterface $heure_debut): static
    {
        $this->heure_debut = $heure_debut;


        return $this;
    }


    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heure_fin;
    }


    public function setHeureFin(\DateTimeInterface $heure_fin): static
    {
        $this->heure_fin = $heure_fin;


        return $this;
    }


    public function isDisponibilite(): ?bool
    {
        return $this->disponibilite;
    }


    public function setDisponibilite(bool $disponibilite): static
    {
        $this->disponibilite = $disponibilite;


        return $this;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }


    public function setUser(?User $user): static
    {
        $this->user = $user;


        return $this;
    }
}
