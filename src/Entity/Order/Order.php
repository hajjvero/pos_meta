<?php

namespace App\Entity\Order;

use App\Entity\Customer\Customer;
use App\Entity\User\User;
use App\Repository\Order\OrderRepository;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Index(name: 'order_code_date_idx', columns: ['code', 'date'])]
class Order
{
    use Timestamp;

    // ============================================================================
    // Properties
    // ============================================================================

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $totalAmount = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    // ============================== Relationships ================================
    // =============================================================================

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'orders')]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $cashier = null;

    #[ORM\OneToMany(targetEntity: OrderMeta::class, mappedBy: 'order', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $orderMetas;

    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $orderItems;

    #[ORM\OneToMany(targetEntity: OrderPayment::class, mappedBy: 'order', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $payments;

    // ============================================================================
    // Constructor
    // ============================================================================

    public function __construct()
    {
        $this->orderMetas = new ArrayCollection();
        $this->orderItems = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->date = new \DateTimeImmutable();
    }

    // ============================================================================
    // Helper methods for meta data
    // ============================================================================

    public function getMeta(string $key): ?string
    {
        foreach ($this->orderMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                return $meta->getMetaValue();
            }
        }
        return null;
    }

    public function setMeta(string $key, string $value): self
    {
        $meta = null;
        foreach ($this->orderMetas as $existingMeta) {
            if ($existingMeta->getMetaKey() === $key) {
                $meta = $existingMeta;
                break;
            }
        }

        if (!$meta) {
            $meta = new OrderMeta();
            $meta->setOrder($this);
            $meta->setMetaKey($key);
            $this->addOrderMeta($meta);
        }

        $meta->setMetaValue($value);
        return $this;
    }

    public function deleteMeta(string $key): self
    {
        foreach ($this->orderMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                $this->removeOrderMeta($meta);
                break;
            }
        }
        return $this;
    }

    // ============================================================================
    // Getters and Setters
    // ============================================================================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    // ============================== Relationships ================================
    // =============================================================================

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCashier(): ?User
    {
        return $this->cashier;
    }

    public function setCashier(?User $cashier): static
    {
        $this->cashier = $cashier;

        return $this;
    }

    /**
     * @return Collection<int, OrderMeta>
     */
    public function getOrderMetas(): Collection
    {
        return $this->orderMetas;
    }

    public function addOrderMeta(OrderMeta $orderMeta): static
    {
        if (!$this->orderMetas->contains($orderMeta)) {
            $this->orderMetas->add($orderMeta);
            $orderMeta->setOrder($this);
        }

        return $this;
    }

    public function removeOrderMeta(OrderMeta $orderMeta): static
    {
        if ($this->orderMetas->removeElement($orderMeta)) {
            // set the owning side to null (unless already changed)
            if ($orderMeta->getOrder() === $this) {
                $orderMeta->setOrder(null);
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

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrder() === $this) {
                $orderItem->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderPayment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(OrderPayment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setOrder($this);
        }

        return $this;
    }

    public function removePayment(OrderPayment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getOrder() === $this) {
                $payment->setOrder(null);
            }
        }

        return $this;
    }
}
