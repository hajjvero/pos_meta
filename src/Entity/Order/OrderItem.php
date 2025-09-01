<?php

namespace App\Entity\Order;

use App\Entity\Product\Product;
use App\Repository\Order\OrderItemRepository;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
#[ORM\Table(name: 'app_order_item')]
#[ORM\Index(name: 'order_item_name_idx', columns: ['name'])]
class OrderItem
{
    use Timestamp;

    // ============================================================================
    // Properties
    // ============================================================================

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::FLOAT, options: ['default' => 0])]
    private ?float $quantity = null;

    #[ORM\Column(type: Types::FLOAT, options: ['default' => 0])]
    private ?float $unitPrice = null;

    // ============================== Relationships ================================
    // =============================================================================

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'orderItems')]
    private ?Product $product = null;

    #[ORM\OneToMany(targetEntity: OrderItemMeta::class, mappedBy: 'orderItem', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $orderItemMetas;

    public function __construct()
    {
        $this->orderItemMetas = new ArrayCollection();
    }

    // ============================================================================
    // Helper methods for meta data
    // ============================================================================

    public function getMeta(string $key): ?string
    {
        foreach ($this->orderItemMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                return $meta->getMetaValue();
            }
        }
        return null;
    }

    public function setMeta(string $key, string $value): self
    {
        $meta = null;
        foreach ($this->orderItemMetas as $existingMeta) {
            if ($existingMeta->getMetaKey() === $key) {
                $meta = $existingMeta;
                break;
            }
        }

        if (!$meta) {
            $meta = new OrderItemMeta();
            $meta->setOrderItem($this);
            $meta->setMetaKey($key);
            $this->addOrderItemMeta($meta);
        }

        $meta->setMetaValue($value);
        return $this;
    }

    public function deleteMeta(string $key): self
    {
        foreach ($this->orderItemMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                $this->removeOrderItemMeta($meta);
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    // ============================== Relationships ================================
    // =============================================================================

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;
        return $this;
    }

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
     * @return Collection<int, OrderItemMeta>
     */
    public function getOrderItemMetas(): Collection
    {
        return $this->orderItemMetas;
    }

    public function addOrderItemMeta(OrderItemMeta $orderItemMeta): self
    {
        if (!$this->orderItemMetas->contains($orderItemMeta)) {
            $this->orderItemMetas->add($orderItemMeta);
            $orderItemMeta->setOrderItem($this);
        }

        return $this;
    }

    public function removeOrderItemMeta(OrderItemMeta $orderItemMeta): self
    {
        if ($this->orderItemMetas->removeElement($orderItemMeta)) {
            // set the owning side to null (unless already changed)
            if ($orderItemMeta->getOrderItem() === $this) {
                $orderItemMeta->setOrderItem(null);
            }
        }

        return $this;
    }
}
