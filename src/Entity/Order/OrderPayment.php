<?php

namespace App\Entity\Order;

use App\Entity\PaymentMethodEnum;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'app_order_payment')]
class OrderPayment
{
    use Timestamp;

    // ============================================================================
    // Properties
    // ============================================================================

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 50, enumType: PaymentMethodEnum::class)]
    private ?PaymentMethodEnum $method = null;

    #[ORM\Column(type: Types::FLOAT, options: ['default' => 0.0])]
    private ?float $amount = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    // ============================== Relationships ================================
    // =============================================================================

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    #[ORM\OneToMany(targetEntity: OrderPaymentMeta::class, mappedBy: 'payment', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $paymentMetas;

    // ============================================================================
    // Constructor
    // ============================================================================

    public function __construct()
    {
        $this->paymentMetas = new ArrayCollection();
        $this->date = new \DateTimeImmutable();
    }

    // ============================================================================
    // Helper methods for meta data
    // ============================================================================

    public function getMeta(string $key): ?string
    {
        foreach ($this->paymentMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                return $meta->getMetaValue();
            }
        }
        return null;
    }

    public function setMeta(string $key, string $value): self
    {
        $meta = null;
        foreach ($this->paymentMetas as $existingMeta) {
            if ($existingMeta->getMetaKey() === $key) {
                $meta = $existingMeta;
                break;
            }
        }

        if (!$meta) {
            $meta = new OrderPaymentMeta();
            $meta->setPayment($this);
            $meta->setMetaKey($key);
            $this->addPaymentMeta($meta);
        }

        $meta->setMetaValue($value);
        return $this;
    }

    public function deleteMeta(string $key): self
    {
        foreach ($this->paymentMetas as $meta) {
            if ($meta->getMetaKey() === $key) {
                $this->removePaymentMeta($meta);
                break;
            }
        }

        return $this;
    }

    // ============================================================================
    // Getters & Setters
    // ============================================================================

    public function getId(): ?int { return $this->id; }

    public function getMethod(): ?PaymentMethodEnum { return $this->method; }
    public function setMethod(PaymentMethodEnum $method): self { $this->method = $method; return $this; }

    public function getAmount(): ?float { return $this->amount; }
    public function setAmount(float $amount): self { $this->amount = $amount; return $this; }

    public function getNotes(): ?string { return $this->notes; }
    public function setNotes(?string $notes): self { $this->notes = $notes; return $this; }

    public function getDate(): ?\DateTimeImmutable { return $this->date; }
    public function setDate(\DateTimeImmutable $date): self { $this->date = $date; return $this; }

    // ============================== Relationships ================================
    // =============================================================================

    public function getOrder(): ?Order { return $this->order; }
    public function setOrder(?Order $order): self { $this->order = $order; return $this; }

    /**
     * @return Collection<int, OrderPaymentMeta>
     */
    public function getPaymentMetas(): Collection
    {
        return $this->paymentMetas;
    }

    public function addPaymentMeta(OrderPaymentMeta $paymentMeta): self
    {
        if (!$this->paymentMetas->contains($paymentMeta)) {
            $this->paymentMetas->add($paymentMeta);
            $paymentMeta->setPayment($this);
        }

        return $this;
    }

    public function removePaymentMeta(OrderPaymentMeta $paymentMeta): self
    {
        if ($this->paymentMetas->removeElement($paymentMeta)) {
            // set the owning side to null (unless already changed)
            if ($paymentMeta->getPayment() === $this) {
                $paymentMeta->setPayment(null);
            }
        }

        return $this;
    }
}
