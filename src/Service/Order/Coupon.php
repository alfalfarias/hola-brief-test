<?php

namespace App\Service\Order;

use App\Entity\Coupon as CouponEntity;
use App\Entity\Order as OrderEntity;
use App\Entity\OrderCoupon as OrderCouponEntity;
use App\Entity\OrderCouponRule as OrderCouponRuleEntity;
use Doctrine\ORM\EntityManagerInterface;

class Coupon
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(OrderEntity $order, CouponEntity $coupon): OrderCouponEntity
    {
        $order_coupon = new OrderCouponEntity();
        $order_coupon->setOrder($order);
        $order_coupon->setCode($coupon->getCode());
        $order_coupon->setType($coupon->getType());
        $order_coupon->setValue($coupon->getValue());

        $order->setCoupon($order_coupon);

        $this->entityManager->persist($order_coupon);
        $this->entityManager->flush();

        foreach ($coupon->getRules() as $rule) {
            $order_coupon_rule = new OrderCouponRuleEntity();
            $order_coupon_rule->setCoupon($order_coupon);
            $order_coupon_rule->setType($rule->getType());
            $order_coupon_rule->setValue($rule->getValue());

            $order_coupon->addRule($order_coupon_rule);

            $this->entityManager->persist($order_coupon_rule);
            $this->entityManager->flush();
        }

        return $order_coupon;
    }
}