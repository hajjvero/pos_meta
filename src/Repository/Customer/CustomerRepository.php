<?php

namespace App\Repository\Customer;

use App\Entity\Customer\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function findByEmailOrPhone(string $email, string $phone): ?Customer
    {
        return $this->createQueryBuilder('c')
            ->where('c.email = :email OR c.phone = :phone')
            ->setParameter('email', $email)
            ->setParameter('phone', $phone)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getTopCustomers(int $limit = 10): array
    {
        return $this->createQueryBuilder('c')
            ->select('c, COUNT(o) as HIDDEN orderCount, SUM(o.totalAmount) as totalSpent')
            ->leftJoin('c.orders', 'o')
            ->groupBy('c.id')
            ->orderBy('totalSpent', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
