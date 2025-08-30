<?php

namespace App\Entity\Inventory;

use App\Entity\Product\Product;
use App\Entity\User\User;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(name: 'inventory_movement_type_idx', columns: ['type'])]
class InventoryMovement
{
    use Timestamp;

    // ============================================================================
    // Properties
    // ============================================================================

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 30, enumType: InventoryMovementTypeEnum::class)]
    private ?InventoryMovementTypeEnum $type = null;

    #[ORM\Column(type: Types::FLOAT, options: ['default' => 0])]
    private ?float $quantity = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $reason = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    // ============================== Relationships ================================
    // =============================================================================

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'inventoryMovements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\OneToMany(targetEntity: InventoryMovementMeta::class, mappedBy: 'inventoryMovement', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $inventoryMovementMetas;

    #[ORM\ManyToOne(inversedBy: 'inventoryMovements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $inventor = null;

    // ============================================================================
    // Constructor
    // ============================================================================

    public function __construct()
    {
        $this->inventoryMovementMetas = new ArrayCollection();
        $this->date = new \DateTimeImmutable();
    }

    // ============================================================================
    // Helper methods for meta data
    // ============================================================================

    public function getMeta(string $key): ?string
    {
        foreach ($this->inventoryMovementMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                return $meta->getMetaValue();
            }
        }
        return null;
    }

    public function setMeta(string $key, string $value): self
    {
        $meta = null;
        foreach ($this->inventoryMovementMetas as $existingMeta) {
            if ($existingMeta->getMetaKey() === $key) {
                $meta = $existingMeta;
                break;
            }
        }

        if (!$meta) {
            $meta = new InventoryMovementMeta();
            $meta->setInventoryMovement($this);
            $meta->setMetaKey($key);
            $this->addInventoryMovementMeta($meta);
        }

        $meta->setMetaValue($value);
        return $this;
    }

    public function deleteMeta(string $key): self
    {
        foreach ($this->inventoryMovementMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                $this->removeInventoryMovementMeta($meta);
                break;
            }
        }

        return $this;
    }

    // ============================================================================
    // Getters & Setters
    // ============================================================================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?InventoryMovementTypeEnum
    {
        return $this->type;
    }

    public function setType(InventoryMovementTypeEnum $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    // ============================== Relationships ================================
    // =============================================================================

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return Collection<int, InventoryMovementMeta>
     */
    public function getInventoryMovementMetas(): Collection
    {
        return $this->inventoryMovementMetas;
    }

    public function addInventoryMovementMeta(InventoryMovementMeta $inventoryMovementMeta): self
    {
        if (!$this->inventoryMovementMetas->contains($inventoryMovementMeta)) {
            $this->inventoryMovementMetas->add($inventoryMovementMeta);
            $inventoryMovementMeta->setInventoryMovement($this);
        }

        return $this;
    }

    public function removeInventoryMovementMeta(InventoryMovementMeta $inventoryMovementMeta): self
    {
        if ($this->inventoryMovementMetas->removeElement($inventoryMovementMeta)) {
            // set the owning side to null (unless already changed)
            if ($inventoryMovementMeta->getInventoryMovement() === $this) {
                $inventoryMovementMeta->setInventoryMovement(null);
            }
        }

        return $this;
    }

    public function getInventor(): ?User
    {
        return $this->inventor;
    }

    public function setInventor(?User $inventor): static
    {
        $this->inventor = $inventor;

        return $this;
    }
}
