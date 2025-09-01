<?php

namespace App\Entity\Order;

use App\Entity\Meta;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'app_order_payment_meta')]
#[ORM\Index(name: 'payment_meta_key_idx', columns: ['meta_key'])]
class OrderPaymentMeta extends Meta
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

    #[ORM\ManyToOne(targetEntity: OrderPayment::class, inversedBy: 'paymentMetas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderPayment $payment = null;

    // ============================================================================
    // Getters & Setters
    // ============================================================================

    public function getId(): ?int { return $this->id; }

    // ============================== Relationships ================================
    // =============================================================================

    public function getPayment(): ?OrderPayment { return $this->payment; }
    public function setPayment(?OrderPayment $payment): self { $this->payment = $payment; return $this; }
}
