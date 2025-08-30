<?php

namespace App\Trait;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Timestamp trait for automatically managing creation and update times within entity classes.
 *
 * @author Hamza Hajjaji <hh.hajjaji.hamza@gmail.com>
 */
trait Timestamp
{
    // ============================================================================
    // Properties
    // ============================================================================

    /**
     * Creation time.
     */
    #[ORM\Column(
        type: Types::DATETIME_IMMUTABLE,
        insertable: false,
        updatable: false,
        columnDefinition: "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
    )]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Update time.
     */
    #[ORM\Column(
        type: Types::DATETIME_IMMUTABLE,
        insertable: false,
        updatable: false,
        columnDefinition: "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        generated: "ALWAYS"
    )]
    private ?\DateTimeImmutable $updatedAt = null;

    // ============================================================================
    // Getters & Setters
    // ============================================================================

    /**
     * Retrieves the creation time.
     *
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Sets the creation time.
     *
     * @param \DateTimeImmutable|null $createdAt
     * @return Timestamp
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Retrieves the update time.
     *
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Sets the update time.
     *
     * @param \DateTimeImmutable|null $updatedAt
     * @return Timestamp
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
