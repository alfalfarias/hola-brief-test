<?php

namespace App\Service\Order;

use App\Entity\Order as OrderEntity;
use App\Entity\OrderProduct as OrderProductEntity;
use App\Entity\Product as ProductEntity;
use Doctrine\ORM\EntityManagerInterface;

class Product
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(OrderEntity $order, ProductEntity $product): OrderProductEntity
    {
        $order_product = new OrderProductEntity();
        $order_product->setOrder($order);
        $order_product->setCode($product->getCode());
        $order_product->setPrice($product->getPrice());

        $order->addProduct($order_product);

        $this->entityManager->persist($order_product);
        $this->entityManager->flush();

        return $order_product;
    }
}