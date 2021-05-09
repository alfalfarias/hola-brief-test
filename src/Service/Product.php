<?php

namespace App\Service;

use App\Entity\Product as ProductEntity;
use Doctrine\ORM\EntityManagerInterface;

class Product
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(ProductEntity $product): ProductEntity
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product;
    }
}