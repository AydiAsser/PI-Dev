<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Validator\Constraints as CustomAssert;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{

    public function getArticleTitleWithId(): string
    {
        return 'ID: '. $this->id . ' - ' . $this->title;
    }
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?User $author;


    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le titre de l'article ne peut pas être vide")]
    #[Assert\Length(min: 5, minMessage: "Le titre doit comporter au moins 5 caractères.")]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank(message: "Le contenu de l'article ne peut pas être vide")]
    #[Assert\Length(min: 20, minMessage: "Le contenu de l'article doit comporter au moins 20 caractères.")]
    private ?string $contenu = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbLikes = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbComments = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\Column(type: 'json', nullable: true)]
    private $likesList = [];

    #[ORM\Column(nullable: true)]
    private ?string $picture = null;

    #[Vich\UploadableField(mapping: "article_pictures", fileNameProperty: "picture")]
    private ?File $pictureFile = null;


    public function __construct()
    {
        $this->commentaires = new ArrayCollection();

        $this->created_at = new \DateTimeImmutable();
        $this->nbLikes = 0;
        $this->nbComments = 0;
    }

    public function getLikesList(): ?array
    {
        return $this->likesList;
    }

    public function setLikesList(?array $likesList): self
    {
        $this->likesList = $likesList;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?user
    {
        return $this->author;
    }

    public function setAuthor(?user $author): static
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
            $commentaire->setArticle($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getArticle() === $this) {
                $commentaire->setArticle(null);
            }
        }

        return $this;
    }


    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    public function setPictureFile(?File $pictureFile = null): self
    {
        $this->pictureFile = $pictureFile;

        return $this;
    }
}
