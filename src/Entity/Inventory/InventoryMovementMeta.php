<?php

namespace App\Entity\Inventory;

use App\Entity\Meta;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'app_inventory_movement_meta')]
#[ORM\Index(name: 'inventory_movement_meta_key_idx', columns: ['inventory_movement_id', 'meta_key'])]
class InventoryMovementMeta extends Meta
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

    #[ORM\ManyToOne(targetEntity: InventoryMovement::class, inversedBy: 'inventoryMovementMetas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?InventoryMovement $inventoryMovement = null;

    // ============================================================================
    // Getters & Setters
    // ============================================================================

    public function getId(): ?int
    {
        return $this->id;
    }

    // ============================== Relationships ================================
    // =============================================================================

    public function getInventoryMovement(): ?InventoryMovement
    {
        return $this->inventoryMovement;
    }

    public function setInventoryMovement(?InventoryMovement $inventoryMovement): self
    {
        $this->inventoryMovement = $inventoryMovement;
        return $this;
    }
}
