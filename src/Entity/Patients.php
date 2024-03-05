<?php

namespace App\Entity;

use App\Repository\PatientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientsRepository::class)]
class Patients
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

    #[ORM\ManyToOne(inversedBy: 'patients')]
    private ?Rendezvous $rendezvous = null;

    #[ORM\OneToMany(mappedBy: 'patients', targetEntity: Prescriptions::class)]
    private Collection $prescriptions;

    public function __construct()
    {
        $this->prescriptions = new ArrayCollection();
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

    public function getRendezvous(): ?Rendezvous
    {
        return $this->rendezvous;
    }

    public function setRendezvous(?Rendezvous $rendezvous): static
    {
        $this->rendezvous = $rendezvous;

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
            $prescription->setPatients($this);
        }

        return $this;
    }

    public function removePrescription(Prescriptions $prescription): static
    {
        if ($this->prescriptions->removeElement($prescription)) {
            // set the owning side to null (unless already changed)
            if ($prescription->getPatients() === $this) {
                $prescription->setPatients(null);
            }
        }

        return $this;
    }



    public function __toString()
    {
        return (string) $this->getId();
    }





}
