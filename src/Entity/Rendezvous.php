<?php

namespace App\Entity;

use App\Repository\RendezvousRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezvousRepository::class)]
class Rendezvous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $raison = null;

    #[ORM\OneToMany(mappedBy: 'rendezvous', targetEntity: Patients::class)]
    private Collection $patients;

    #[ORM\OneToMany(mappedBy: 'Medecins', targetEntity: Medecins::class)]
    private Collection $medecins;

    #[ORM\ManyToOne(inversedBy: 'rendezvouses')]
    private ?Medecins $Medecins = null;

    #[ORM\Column]
    private ?int $nbr = null;

    public function __construct()
    {
        $this->patients = new ArrayCollection();
        $this->medecins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(string $raison): static
    {
        $this->raison = $raison;

        return $this;
    }

    /**
     * @return Collection<int, Patients>
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patients $patient): static
    {
        if (!$this->patients->contains($patient)) {
            $this->patients->add($patient);
            $patient->setRendezvous($this);
        }

        return $this;
    }

    public function removePatient(Patients $patient): static
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getRendezvous() === $this) {
                $patient->setRendezvous(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Medecins>
     */
    public function getMedecins(): Collection
    {
        return $this->medecins;
    }

    public function addMedecin(Medecins $medecin): static
    {
        if (!$this->medecins->contains($medecin)) {
            $this->medecins->add($medecin);
            $medecin->setMedecins($this);
        }

        return $this;
    }

    public function removeMedecin(Medecins $medecin): static
    {
        if ($this->medecins->removeElement($medecin)) {
            // set the owning side to null (unless already changed)
            if ($medecin->getMedecins() === $this) {
                $medecin->setMedecins(null);
            }
        }

        return $this;
    }

    public function setMedecins(?Medecins $Medecins): static
    {
        $this->Medecins = $Medecins;

        return $this;
    }

    public function getNbr(): ?int
    {
        return $this->nbr;
    }

    public function setNbr(int $nbr): static
    {
        $this->nbr = $nbr;

        return $this;
    }
}
