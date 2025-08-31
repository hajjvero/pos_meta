<?php

namespace App\Entity\Option;

use App\Repository\Option\OptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
#[ORM\Table(name: 'app_option')]
#[ORM\Index(name: 'option_key_idx', columns: ['option_key'])]
class Option
{
    // ============================================================================
    // Properties
    // ============================================================================

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[ORM\Column(length: 200, unique: true)]
    private ?string $optionKey = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $optionValue = null;

    // ============================================================================
    // Getters & Setters
    // ============================================================================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptionKey(): ?string
    {
        return $this->optionKey;
    }

    public function setOptionKey(string $optionKey): static
    {
        $this->optionKey = $optionKey;
        return $this;
    }

    public function getOptionValue(): ?string
    {
        return $this->optionValue;
    }

    public function setOptionValue(?string $optionValue): static
    {
        $this->optionValue = $optionValue;
        return $this;
    }
}
