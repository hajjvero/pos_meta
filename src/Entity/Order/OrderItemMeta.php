<?php

namespace App\Entity\Order;

use App\Entity\Meta;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'app_order_item_meta')]
#[ORM\Index(name: 'order_item_meta_key_idx', columns: ['meta_key'])]
class OrderItemMeta extends Meta
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

    #[ORM\ManyToOne(targetEntity: OrderItem::class, inversedBy: 'orderItemMetas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderItem $orderItem = null;

    // ============================================================================
    // Getters and Setters
    // ============================================================================
    public function getId(): ?int
    {
        return $this->id;
    }

    // ============================== Relationships ================================
    // =============================================================================

    public function getOrderItem(): ?OrderItem
    {
        return $this->orderItem;
    }

    public function setOrderItem(?OrderItem $orderItem): self
    {
        $this->orderItem = $orderItem;
        return $this;
    }
}
