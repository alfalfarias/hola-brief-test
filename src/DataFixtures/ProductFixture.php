<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Service\Product as ProductService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product->setCode('Camisa');
        $product->setPrice(3);
        $this->productService->create($product);

        $product = new Product();
        $product->setCode('Camisa_premium');
        $product->setPrice(10);
        $this->productService->create($product);

        $product = new Product();
        $product->setCode('Traje');
        $product->setPrice(12);
        $this->productService->create($product);
    }
}
