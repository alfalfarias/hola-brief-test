<?php

namespace App\Service;

use App\Entity\Coupon as CouponEntity;
use App\Entity\CouponRule as RuleEntity;
use Doctrine\ORM\EntityManagerInterface;

class Coupon
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(CouponEntity $coupon, array $rules = []): CouponEntity
    {
        $this->entityManager->persist($coupon);
        $this->entityManager->flush();

        foreach ($rules as $rule) {
            $rule->setCoupon($coupon);
            $coupon->addRule($rule);
            
            $this->entityManager->persist($rule);
            $this->entityManager->flush();
        }

        return $coupon;
    }
}