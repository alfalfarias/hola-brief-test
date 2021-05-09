<?php

namespace App\Service;

use App\Entity\Coupon as CouponEntity;
use App\Entity\Order as OrderEntity;
use App\Entity\OrderCoupon as OrderCouponEntity;
use App\Entity\OrderCouponRule as OrderCouponRuleEntity;
use App\Entity\OrderProduct as OrderProductEntity;
use App\Entity\Product as ProductEntity;
use Doctrine\ORM\EntityManagerInterface;

class Order
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(array $products, CouponEntity $coupon = null): OrderEntity
    {
        $price = 0;
        foreach ($products as $product) {
            $price += $product->getPrice();
        }

        $discount = 0;
        if ($coupon) {
            $coupon_type = $coupon->getType();
            if ($coupon_type === CouponEntity::TYPE['PRICE_FIXED']) {
                $discount += $coupon->getValue();
            }
            if ($coupon_type === CouponEntity::TYPE['PRICE_PERCENT']) {
                $discount += $coupon->getValue() * $price / 100;
            }
        }

        $total = $price - $discount;

        $order = new OrderEntity();
        $order->setPrice($price);
        $order->setDiscount($discount);
        $order->setTotal($total);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        foreach ($products as $product) {
            $order_product = new OrderProductEntity();
            $order_product->setOrder($order);
            $order_product->setCode($product->getCode());
            $order_product->setPrice($product->getPrice());

            $order->addProduct($order_product);

            $this->entityManager->persist($order_product);
            $this->entityManager->flush();
        }

        if ($coupon) {
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
        }

        return $order;
    }
}