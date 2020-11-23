<?php

namespace App\Entity;

use App\Repository\QuackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Image;

/**
 * @ORM\Entity(repositoryClass=QuackRepository::class)
 */
class Quack
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $photo;


    /**
     * @ORM\ManyToOne(targetEntity=Duck::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Auteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $uploadFileName;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="quacks")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Quack::class, inversedBy="comments")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Quack::class, mappedBy="parent",  cascade={"persist", "remove"})
     *
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }


    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto(File $photo = null): self
    {
        $this->photo = $photo;

        return $this;
    }


    public function getAuteur(): ?Duck
    {
        return $this->Auteur;
    }

    public function setAuteur(?Duck $duckname): self
    {
        $this->Auteur = $duckname;

        return $this;
    }

    public function getUploadFileName(): ?string
    {
        return $this->uploadFileName;
    }

    public function setUploadFileName(?string $uploadFileName): self
    {
        $this->uploadFileName = $uploadFileName;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(self $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setParent($this);
        }

        return $this;
    }

    public function removeComment(self $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getParent() === $this) {
                $comment->setParent(null);
            }
        }

        return $this;
    }



}
