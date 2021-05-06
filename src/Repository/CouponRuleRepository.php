<?php

namespace App\Repository;

use App\Entity\CouponRule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CouponRule|null find($id, $lockMode = null, $lockVersion = null)
 * @method CouponRule|null findOneBy(array $criteria, array $orderBy = null)
 * @method CouponRule[]    findAll()
 * @method CouponRule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CouponRuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CouponRule::class);
    }

    // /**
    //  * @return CouponRule[] Returns an array of CouponRule objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CouponRule
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
