<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class Meta
{
    // ============================================================================
    // Properties
    // ============================================================================

    #[ORM\Column(type: Types::STRING, length: 255)]
    protected ?string $metaKey = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $metaValue = null;

    // ============================================================================
    // Getters & Setters
    // ============================================================================

    public function getMetaKey(): ?string { return $this->metaKey; }
    public function setMetaKey(string $metaKey): self { $this->metaKey = $metaKey; return $this; }
    public function getMetaValue(): ?string { return $this->metaValue; }
    public function setMetaValue(?string $metaValue): self { $this->metaValue = $metaValue; return $this; }
}
