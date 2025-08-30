<?php

namespace App\Repository\Order;

use App\Entity\Order\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findTodayOrders(): array
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $tomorrow = clone $today;
        $tomorrow->modify('+1 day');

        return $this->createQueryBuilder('o')
            ->where('o.orderDate >= :today')
            ->andWhere('o.orderDate < :tomorrow')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->orderBy('o.orderDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getTotalSales(\DateTime $from, \DateTime $to): float
    {
        $result = $this->createQueryBuilder('o')
            ->select('SUM(o.totalAmount) as total')
            ->where('o.orderDate >= :from')
            ->andWhere('o.orderDate <= :to')
            ->andWhere('o.orderStatus = :status')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('status', 'completed')
            ->getQuery()
            ->getSingleScalarResult();

        return $result ?? 0.0;
    }

    public function findByCustomer(int $customerId): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.customer = :customerId')
            ->setParameter('customerId', $customerId)
            ->orderBy('o.orderDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
