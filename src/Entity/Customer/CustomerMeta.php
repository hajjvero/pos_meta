<?php

namespace App\Entity\Customer;

use App\Entity\Meta;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(name: 'customer_meta_key_idx', columns: ['meta_key'])]
class CustomerMeta extends Meta
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

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'customerMetas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    // ============================================================================
    // Getters & Setters
    // ============================================================================

    public function getId(): ?int
    {
        return $this->id;
    }

    // ============================== Relationships ================================
    // =============================================================================

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;
        return $this;
    }
}
