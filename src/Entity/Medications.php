<?php

namespace App\Entity;

use App\Repository\MedicationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicationsRepository::class)]
class Medications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $disponibilite = null;

    #[ORM\Column(length: 255)]
    private ?string $instruction_usage = null;

    #[ORM\ManyToMany(targetEntity: Prescriptions::class, mappedBy: 'medicaments')]
    private Collection $prescriptions;

    public function __construct()
    {
        $this->prescriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getInstructionUsage(): ?string
    {
        return $this->instruction_usage;
    }

    public function setInstructionUsage(string $instruction_usage): static
    {
        $this->instruction_usage = $instruction_usage;

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
            $prescription->addMedicament($this);
        }

        return $this;
    }

    public function removePrescription(Prescriptions $prescription): static
    {
        if ($this->prescriptions->removeElement($prescription)) {
            $prescription->removeMedicament($this);
        }

        return $this;
    }

    
    public function __toString()
    {
        return (string) $this->getid();
    }

}
