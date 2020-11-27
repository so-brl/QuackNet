<?php

namespace App\Entity;

use App\Repository\DuckRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DuckRepository::class)
 * @UniqueEntity(fields={"duckname"}, message="There is already an account with this duckname")
 */
class Duck implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $duckname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"read"})
     */
    private $password;
    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Quack::class, mappedBy="Auteur", orphanRemoval=true)
     */
    private $quacks;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $uploadFileName;

    /**
     * @return mixed
     */
    public function getQuacks()
    {
        return $this->quacks;
    }

    /**
     * @param mixed $quacks
     */
    public function setQuacks($quacks): void
    {
        $this->quacks = $quacks;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDuckname(): ?string
    {
        return $this->duckname;
    }

    public function setDuckname(string $duckname): self
    {
        $this->duckname = $duckname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @inheritDoc
     */
    public function getUsername(): string
    {
        return $this->duckname;
    }


    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


public function isVerified(): bool
{
    return $this->isVerified;
}

public function setIsVerified(bool $isVerified): self
{
    $this->isVerified = $isVerified;

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

}
