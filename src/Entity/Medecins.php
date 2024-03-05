<?php

namespace App\Entity;

use App\Repository\MedecinsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedecinsRepository::class)]
class Medecins
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $phonenumber = null;

    #[ORM\Column(length: 255)]
    private ?string $degree = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column]
    private ?int $rate = null;

    #[ORM\ManyToOne(inversedBy: 'medecins')]
    private ?Rendezvous $Medecins = null;

    #[ORM\OneToMany(mappedBy: 'Medecins', targetEntity: Rendezvous::class)]
    private Collection $rendezvouses;

    #[ORM\OneToMany(mappedBy: 'Medecins', targetEntity: Prescriptions::class)]
    private Collection $prescriptions;

    #[ORM\OneToMany(mappedBy: 'medecins', targetEntity: Rendezvouss::class)]
    private Collection $rendezvousses;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: PlanningMedecins::class)]
    private Collection $planningMedecins;

    #[ORM\OneToMany(mappedBy: 'medecins_id', targetEntity: Calendar::class)]
    private Collection $calendars;

    public function __construct()
    {
        $this->rendezvouses = new ArrayCollection();
        $this->prescriptions = new ArrayCollection();
        $this->rendezvousses = new ArrayCollection();
        $this->planningMedecins = new ArrayCollection();
        $this->calendars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPhonenumber(): ?int
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(int $phonenumber): static
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getDegree(): ?string
    {
        return $this->degree;
    }

    public function setDegree(string $degree): static
    {
        $this->degree = $degree;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getMedecins(): ?Rendezvous
    {
        return $this->Medecins;
    }

    public function setMedecins(?Rendezvous $Medecins): static
    {
        $this->Medecins = $Medecins;

        return $this;
    }

    /**
     * @return Collection<int, Rendezvous>
     */
    public function getRendezvouses(): Collection
    {
        return $this->rendezvouses;
    }

    public function addRendezvouse(Rendezvous $rendezvouse): static
    {
        if (!$this->rendezvouses->contains($rendezvouse)) {
            $this->rendezvouses->add($rendezvouse);
            $rendezvouse->setMedecins($this);
        }

        return $this;
    }

    public function removeRendezvouse(Rendezvous $rendezvouse): static
    {
        if ($this->rendezvouses->removeElement($rendezvouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezvouse->getMedecins() === $this) {
                $rendezvouse->setMedecins(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Prescriptions>
     */
    public function getPrescriptions(): Collection
    {
        return $this->prescriptions;
    }

    public function addPrescription(Prescriptions $prescription): static
    {
        if (!$this->prescriptions->contains($prescription)) {
            $this->prescriptions->add($prescription);
            $prescription->setMedecins($this);
        }

        return $this;
    }

    public function removePrescription(Prescriptions $prescription): static
    {
        if ($this->prescriptions->removeElement($prescription)) {
            // set the owning side to null (unless already changed)
            if ($prescription->getMedecins() === $this) {
                $prescription->setMedecins(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rendezvouss>
     */
    public function getRendezvousses(): Collection
    {
        return $this->rendezvousses;
    }

   
   

    public function __toString()
    {
        return (string) $this->getFirstname();
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
            $planningMedecin->setMedecin($this);
        }

        return $this;
    }

    public function removePlanningMedecin(PlanningMedecins $planningMedecin): static
    {
        if ($this->planningMedecins->removeElement($planningMedecin)) {
            // set the owning side to null (unless already changed)
            if ($planningMedecin->getMedecin() === $this) {
                $planningMedecin->setMedecin(null);
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

}
