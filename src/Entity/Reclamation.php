<?php


namespace App\Entity;


use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $sujet = null;


    #[ORM\Column(length: 255)]
    private ?string $description = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;
    /** @ORM\Column(type="string", nullable=true)
    */
   private $imageFile;
    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSujet(): ?string
    {
        return $this->sujet;
    }


    public function setSujet(string $sujet): static
    {
        $this->sujet = $sujet;


        return $this;
    }
    public function __toString(): string
    {
        return $this->sujet ; // Return sujet if it's set, otherwise an empty string
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


    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }


    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;


        return $this;
    }
    public function getImageFile(): ?string
    {
        return $this->imageFile;
    }


    public function setImageFile(?string $imageFile): self
    {
        $this->imageFile = $imageFile;


        return $this;
    }
   
}

