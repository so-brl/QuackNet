<?php

namespace App\Entity;

use App\Repository\QuackRepository;
use App\Entity\Tag;
use Closure;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Image;
use Traversable;

/**
 * @ORM\Entity(repositoryClass=QuackRepository::class)
 */
class Quack implements Collection
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
     * @ORM\OneToMany(targetEntity=Quack::class, mappedBy="parent", cascade={"persist", "remove"})
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
    public function getTags(): ?Collection
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


    /**
     * @inheritDoc
     */
    public function add($element)
    {
        // TODO: Implement add() method.
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        // TODO: Implement clear() method.
    }

    /**
     * @inheritDoc
     */
    public function contains($element)
    {
        // TODO: Implement contains() method.
    }

    /**
     * @inheritDoc
     */
    public function isEmpty()
    {
        // TODO: Implement isEmpty() method.
    }

    /**
     * @inheritDoc
     */
    public function remove($key)
    {
        // TODO: Implement remove() method.
    }

    /**
     * @inheritDoc
     */
    public function removeElement($element)
    {
        // TODO: Implement removeElement() method.
    }

    /**
     * @inheritDoc
     */
    public function containsKey($key)
    {
        // TODO: Implement containsKey() method.
    }

    /**
     * @inheritDoc
     */
    public function get($key)
    {
        // TODO: Implement get() method.
    }

    /**
     * @inheritDoc
     */
    public function getKeys()
    {
        // TODO: Implement getKeys() method.
    }

    /**
     * @inheritDoc
     */
    public function getValues()
    {
        // TODO: Implement getValues() method.
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value)
    {
        // TODO: Implement set() method.
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @inheritDoc
     */
    public function first()
    {
        // TODO: Implement first() method.
    }

    /**
     * @inheritDoc
     */
    public function last()
    {
        // TODO: Implement last() method.
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        // TODO: Implement key() method.
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        // TODO: Implement current() method.
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        // TODO: Implement next() method.
    }

    /**
     * @inheritDoc
     */
    public function exists(Closure $p)
    {
        // TODO: Implement exists() method.
    }

    /**
     * @inheritDoc
     */
    public function filter(Closure $p)
    {
        // TODO: Implement filter() method.
    }

    /**
     * @inheritDoc
     */
    public function forAll(Closure $p)
    {
        // TODO: Implement forAll() method.
    }

    /**
     * @inheritDoc
     */
    public function map(Closure $func)
    {
        // TODO: Implement map() method.
    }

    /**
     * @inheritDoc
     */
    public function partition(Closure $p)
    {
        // TODO: Implement partition() method.
    }

    /**
     * @inheritDoc
     */
    public function indexOf($element)
    {
        // TODO: Implement indexOf() method.
    }

    /**
     * @inheritDoc
     */
    public function slice($offset, $length = null)
    {
        // TODO: Implement slice() method.
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        // TODO: Implement getIterator() method.
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        // TODO: Implement count() method.
    }
}
