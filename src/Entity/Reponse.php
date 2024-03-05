<?php


namespace App\Entity;


use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $sujetrep = null;


    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reclamation $idrec = null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSujetrep(): ?string
    {
       
        return $this->sujetrep;
    }


    public function setSujetrep(string $sujetrep): static
    {
        $this->sujetrep = $sujetrep;


        return $this;
    }


    public function getIdrec(): ?Reclamation
    {
        return $this->idrec;
    }


    public function setIdrec(Reclamation $idrec): static
    {
        $this->idrec = $idrec;


        return $this;
    }
    public function __toString(): string
{
    return $this->sujetrep ; // Return sujet if it's set, otherwise an empty string
}


}



