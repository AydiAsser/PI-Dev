<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Medecin $author;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contenu = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbLikes = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbComments = null;

    #[ORM\OneToMany(mappedBy: 'article_id', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();

        $this->created_at = new \DateTimeImmutable();
        $this->nbLikes = 0;
        $this->nbComments = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?Medecin
    {
        return $this->author;
    }

    public function setAuthor(?Medecin $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getNbLikes(): ?int
    {
        return $this->nbLikes;
    }

    public function setNbLikes(?int $nbLikes): static
    {
        $this->nbLikes = $nbLikes;

        return $this;
    }

    public function getNbComments(): ?int
    {
        return $this->nbComments;
    }

    public function setNbComments(?int $nbComments): static
    {
        $this->nbComments = $nbComments;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setArticleId($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getArticleId() === $this) {
                $commentaire->setArticleId(null);
            }
        }

        return $this;
    }
}
