<?php

namespace App\Repository;

use App\Entity\OrderCoupon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderCoupon|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderCoupon|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderCoupon[]    findAll()
 * @method OrderCoupon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderCouponRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderCoupon::class);
    }

    // /**
    //  * @return OrderCoupon[] Returns an array of OrderCoupon objects
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
    public function findOneBySomeField($value): ?OrderCoupon
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
