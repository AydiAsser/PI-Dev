<?php

namespace App\Entity;

use App\Repository\MedicamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicamentRepository::class)]
class Medicament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;
    
    
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\OneToMany(mappedBy: 'medicament', targetEntity: Prescriptions::class)]
    private Collection $prescriptions;

    #[ORM\Column(length: 255)]
    private ?string $categorie = null;

    #[ORM\OneToMany(mappedBy: 'medi', targetEntity: Panier::class)]
    private Collection $paniers;

    #[ORM\OneToMany(mappedBy: 'medicament', targetEntity: Prescri::class)]
    private Collection $prescris;

    public function __construct()
    {
        $this->prescriptions = new ArrayCollection();
        $this->paniers = new ArrayCollection();
        $this->prescris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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
            $prescription->setMedicament($this);
        }

        return $this;
    }

    public function removePrescription(Prescriptions $prescription): static
    {
        if ($this->prescriptions->removeElement($prescription)) {
            // set the owning side to null (unless already changed)
            if ($prescription->getMedicament() === $this) {
                $prescription->setMedicament(null);
            }
        }

        return $this;
    }


    public function __toString()
    {
        return (string) $this->getNom();
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

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
            $panier->setMedi($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): static
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getMedi() === $this) {
                $panier->setMedi(null);
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
            $prescri->setMedicament($this);
        }

        return $this;
    }

    public function removePrescri(Prescri $prescri): static
    {
        if ($this->prescris->removeElement($prescri)) {
            // set the owning side to null (unless already changed)
            if ($prescri->getMedicament() === $this) {
                $prescri->setMedicament(null);
            }
        }

        return $this;
    }

}
