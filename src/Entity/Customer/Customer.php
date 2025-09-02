<?php

namespace App\Entity\Customer;

use App\Entity\Person;
use App\Entity\Order\Order;
use App\Repository\Customer\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ORM\Table(name: 'app_customer')]
#[ORM\Index(name: 'customer_name_email_phone_idx', columns: ['name', 'email', 'phone'])]
class Customer extends Person
{
    // ============================================================================
    // Properties
    // ============================================================================

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $type = null;

    // ============================== Relationships ================================
    // =============================================================================

    #[ORM\OneToMany(targetEntity: CustomerMeta::class, mappedBy: 'customer', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $customerMetas;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'customer')]
    private Collection $orders;

    // ============================================================================
    // Constructor
    // ============================================================================

    public function __construct()
    {
        $this->customerMetas = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    // ============================================================================
    // Helper methods for meta data
    // ============================================================================

    public function getMeta(string $key): ?string
    {
        foreach ($this->customerMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                return $meta->getMetaValue();
            }
        }
        return null;
    }

    public function setMeta(string $key, string $value): self
    {
        $meta = null;
        foreach ($this->customerMetas as $existingMeta) {
            if ($existingMeta->getMetaKey() === $key) {
                $meta = $existingMeta;
                break;
            }
        }

        if (!$meta) {
            $meta = new CustomerMeta();
            $meta->setCustomer($this);
            $meta->setMetaKey($key);
            $this->addCustomerMeta($meta);
        }

        $meta->setMetaValue($value);
        return $this;
    }

    public function deleteMeta(string $key): self
    {
        foreach ($this->customerMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                $this->removeCustomerMeta($meta);
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    // ============================== Relationships ================================
    // =============================================================================

    /**
     * @return Collection<int, CustomerMeta>
     */
    public function getCustomerMetas(): Collection
    {
        return $this->customerMetas;
    }

    public function addCustomerMeta(CustomerMeta $customerMeta): static
    {
        if (!$this->customerMetas->contains($customerMeta)) {
            $this->customerMetas->add($customerMeta);
            $customerMeta->setCustomer($this);
        }

        return $this;
    }

    public function removeCustomerMeta(CustomerMeta $customerMeta): static
    {
        if ($this->customerMetas->removeElement($customerMeta)) {
            // set the owning side to null (unless already changed)
            if ($customerMeta->getCustomer() === $this) {
                $customerMeta->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }

    // ============================================================================
    // Implemented methods
    // ============================================================================

    public function __toString(): string
    {
        return $this->name;
    }
}
