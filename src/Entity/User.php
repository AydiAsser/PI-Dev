<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $region = null;

    #[ORM\Column(nullable: true)]
    private ?float $rate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $degree = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $role = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: PlanningMedecins::class)]
    private Collection $planningMedecins;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Calendar::class)]
    private Collection $calendars;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Panier::class)]
    private Collection $paniers;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Prescri::class)]
    private Collection $prescris;

    public function __construct()
    {
        $this->planningMedecins = new ArrayCollection();
        $this->calendars = new ArrayCollection();
        $this->paniers = new ArrayCollection();
        $this->prescris = new ArrayCollection();
    }

   
    public function __toString()
    {
        return (string) $this->getFirstName();
    }


    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getDegree(): ?string
    {
        return $this->degree;
    }

    public function setDegree(?string $degree): static
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * @return Collection<int, PlanningMedecins>
     */
    public function getPlanningMedecins(): Collection
    {
        return $this->planningMedecins;
    }

    public function addPlanningMedecin(PlanningMedecins $planningMedecin): static
    {
        if (!$this->planningMedecins->contains($planningMedecin)) {
            $this->planningMedecins->add($planningMedecin);
            $planningMedecin->setUser($this);
        }

        return $this;
    }

    public function removePlanningMedecin(PlanningMedecins $planningMedecin): static
    {
        if ($this->planningMedecins->removeElement($planningMedecin)) {
            // set the owning side to null (unless already changed)
            if ($planningMedecin->getUser() === $this) {
                $planningMedecin->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Calendar>
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    public function addCalendar(Calendar $calendar): static
    {
        if (!$this->calendars->contains($calendar)) {
            $this->calendars->add($calendar);
            $calendar->setUser($this);
        }

        return $this;
    }

    public function removeCalendar(Calendar $calendar): static
    {
        if ($this->calendars->removeElement($calendar)) {
            // set the owning side to null (unless already changed)
            if ($calendar->getUser() === $this) {
                $calendar->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): static
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->setClient($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): static
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getClient() === $this) {
                $panier->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Prescri>
     */
    public function getPrescris(): Collection
    {
        return $this->prescris;
    }

    public function addPrescri(Prescri $prescri): static
    {
        if (!$this->prescris->contains($prescri)) {
            $this->prescris->add($prescri);
            $prescri->setPatient($this);
        }

        return $this;
    }

    public function removePrescri(Prescri $prescri): static
    {
        if ($this->prescris->removeElement($prescri)) {
            // set the owning side to null (unless already changed)
            if ($prescri->getPatient() === $this) {
                $prescri->setPatient(null);
            }
        }

        return $this;
    }

   


    
}