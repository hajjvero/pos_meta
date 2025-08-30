<?php

namespace App\Entity\Order;

use App\Entity\Meta;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(name: 'order_meta_key_idx', columns: ['meta_key'])]
class OrderMeta extends Meta
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

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderMetas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    // ============================================================================
    // Getters & Setters
    // ============================================================================

    public function getId(): ?int { return $this->id; }

    // ============================== Relationships ================================
    // =============================================================================

    public function getOrder(): ?Order { return $this->order; }
    public function setOrder(?Order $order): self { $this->order = $order; return $this; }
}
