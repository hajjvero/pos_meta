<?php

namespace App\Entity\User;

use App\Entity\Meta;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'app_user_meta')]
#[ORM\Index(name: 'user_meta_key_idx', columns: ['meta_key'])]
class UserMeta extends Meta
{
    // ============================================================================
    // Properties
    // ============================================================================

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    // ============================== Relationships ================================
    // =============================================================================

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userMetas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // ============================================================================
    // Getters and Setters
    // ============================================================================

    public function getId(): ?int
    {
        return $this->id;
    }

    // ============================== Relationships ================================
    // =============================================================================

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
