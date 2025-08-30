<?php

namespace App\Entity\Product;

use App\Entity\Meta;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(name: 'category_meta_key_idx', columns: ['meta_key'])]
class CategoryMeta extends Meta
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

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'categoryMetas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    // ============================================================================
    // Getters and Setters
    // ============================================================================
    public function getId(): ?int
    {
        return $this->id;
    }

    // ============================== Relationships ================================
    // =============================================================================

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }
}
