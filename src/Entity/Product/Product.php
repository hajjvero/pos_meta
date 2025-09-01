<?php

namespace App\Entity\Product;

use App\Entity\Inventory\InventoryMovement;
use App\Entity\Order\OrderItem;
use App\Repository\Product\ProductRepository;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'app_product')]
#[ORM\Index(name: 'product_name_sku_idx', columns: ['name', 'sku'])]
class Product
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
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 100, unique: true, nullable: true)]
    #[Assert\Unique]
    private ?string $sku = null;

    #[ORM\Column(type: Types::FLOAT, options: ['default' => 0.0])]
    #[Assert\PositiveOrZero]
    private ?float $price = null;

    // ============================== Relationships ================================
    // =============================================================================

    #[ORM\OneToMany(targetEntity: ProductMeta::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $productMetas;

    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'product')]
    private Collection $orderItems;

    #[ORM\OneToMany(targetEntity: InventoryMovement::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $inventoryMovements;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    private Collection $categories;

    // ============================================================================
    // Constructor
    // ============================================================================

    public function __construct()
    {
        $this->productMetas = new ArrayCollection();
        $this->orderItems = new ArrayCollection();
        $this->inventoryMovements = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    // ============================================================================
    // Helper methods for meta data
    // ============================================================================

    public function getMeta(string $key): ?string
    {
        foreach ($this->productMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                return $meta->getMetaValue();
            }
        }
        return null;
    }

    public function setMeta(string $key, string $value): self
    {
        $meta = null;
        foreach ($this->productMetas as $existingMeta) {
            if ($existingMeta->getMetaKey() === $key) {
                $meta = $existingMeta;
                break;
            }
        }

        if (!$meta) {
            $meta = new ProductMeta();
            $meta->setProduct($this);
            $meta->setMetaKey($key);
            $this->productMetas->add($meta);
        }

        $meta->setMetaValue($value);
        return $this;
    }

    public function deleteMeta(string $key): self
    {
        foreach ($this->productMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                $this->removeProductMeta($meta);
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

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    // ============================== Relationships ================================
    // =============================================================================

    /**
     * @return Collection<int, ProductMeta>
     */
    public function getProductMetas(): Collection
    {
        return $this->productMetas;
    }

    public function addProductMeta(ProductMeta $productMeta): self
    {
        if (!$this->productMetas->contains($productMeta)) {
            $this->productMetas->add($productMeta);
            $productMeta->setProduct($this);
        }

        return $this;
    }

    public function removeProductMeta(ProductMeta $productMeta): self
    {
        if ($this->productMetas->removeElement($productMeta)) {
            // set the owning side to null (unless already changed)
            if ($productMeta->getProduct() === $this) {
                $productMeta->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getProduct() === $this) {
                $orderItem->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InventoryMovement>
     */
    public function getInventoryMovements(): Collection
    {
        return $this->inventoryMovements;
    }

    public function addInventoryMovement(InventoryMovement $inventoryMovement): self
    {
        if (!$this->inventoryMovements->contains($inventoryMovement)) {
            $this->inventoryMovements->add($inventoryMovement);
            $inventoryMovement->setProduct($this);
        }

        return $this;
    }

    public function removeInventoryMovement(InventoryMovement $inventoryMovement): self
    {
        if ($this->inventoryMovements->removeElement($inventoryMovement)) {
            // set the owning side to null (unless already changed)
            if ($inventoryMovement->getProduct() === $this) {
                $inventoryMovement->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }
}
