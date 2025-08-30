<?php

namespace App\Repository\Product;

use App\Entity\Product\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByMetaKey(string $key, string $value): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.productMetas', 'pm')
            ->where('pm.metaKey = :key')
            ->andWhere('pm.metaValue = :value')
            ->setParameter('key', $key)
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult();
    }
}
