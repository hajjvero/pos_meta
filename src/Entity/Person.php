<?php

namespace App\Entity;

use App\Trait\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\MappedSuperclass]
abstract class Person
{
    use Timestamp;

    // ============================================================================
    // Properties
    // ============================================================================

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    protected ?string $name = null;

    #[ORM\Column(length: 180, unique: true, nullable: true)]
    #[Assert\Email]
    #[Assert\Unique]
    protected ?string $email = null;

    #[ORM\Column(length: 30, unique: true, nullable: true)]
    #[Assert\Unique]
    protected ?string $phone = null;

    // ============================================================================
    // Getters and Setters
    // ============================================================================

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
