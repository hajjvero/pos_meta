<?php

namespace App\Repository\Product;

use App\Entity\Product\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findByMetaKey(string $key, string $value): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.categoryMetas', 'cm')
            ->where('cm.metaKey = :key')
            ->andWhere('cm.metaValue = :value')
            ->setParameter('key', $key)
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult();
    }

    public function findRootCategories(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.parent IS NULL')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->getResult();
    }
}
