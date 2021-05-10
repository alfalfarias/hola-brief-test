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

    public function calculateDiscount(CouponEntity $coupon, float $price): float
    {
        $coupon_type = $coupon->getType();
        if ($coupon_type === CouponEntity::TYPE['PRICE_FIXED']) {
            $discount += $coupon->getValue();
        }
        if ($coupon_type === CouponEntity::TYPE['PRICE_PERCENT']) {
            $discount += $coupon->getValue() * $price / 100;
        }

        $rules = $coupon->getRules();
        foreach ($rules as $rule) {
            $value = $rule->getValue();
            if ($rule->getType() === RuleEntity::TYPE['PRICE_MIN']) {
                if ($price < $value) {
                    $discount = 0;
                }
            }
            if ($rule->getType() === RuleEntity::TYPE['PRICE_MAX']) {
                if ($price > $value) {
                    $discount = 0;
                }
            }
        }
    }
}