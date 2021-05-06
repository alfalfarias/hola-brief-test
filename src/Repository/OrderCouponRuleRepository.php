<?php

namespace App\Repository;

use App\Entity\OrderCouponRule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderCouponRule|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderCouponRule|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderCouponRule[]    findAll()
 * @method OrderCouponRule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderCouponRuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderCouponRule::class);
    }

    // /**
    //  * @return OrderCouponRule[] Returns an array of OrderCouponRule objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderCouponRule
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
