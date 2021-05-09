<?php

namespace App\Service;

use App\Entity\Coupon as CouponEntity;
use App\Entity\Order as OrderEntity;
use App\Entity\Product as ProductEntity;
use App\Service\Order\Coupon as CouponService;
use App\Service\Order\Product as ProductService;
use Doctrine\ORM\EntityManagerInterface;

class Order
{
    private $entityManager;
    private $productService;
    private $couponService;

    public function __construct(EntityManagerInterface $entityManager, ProductService $productService, CouponService $couponService)
    {
        $this->entityManager = $entityManager;
        $this->productService = $productService;
        $this->couponService = $couponService;
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
            $this->productService->create($order, $product);
        }

        if ($coupon) {
            $this->couponService->create($order, $coupon);
        }

        return $order;
    }
}