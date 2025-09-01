<?php

namespace App\Entity\Product;

use App\Entity\Meta;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'app_product_meta')]
#[ORM\Index(name: 'product_meta_key_idx', columns: ['meta_key'])]
class ProductMeta extends Meta
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

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productMetas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    // ============================================================================
    // Getters & Setters
    // ============================================================================

    public function getId(): ?int { return $this->id; }

    // ============================== Relationships ================================
    // =============================================================================

    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): self { $this->product = $product; return $this; }
}

