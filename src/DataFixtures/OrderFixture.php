<?php

namespace App\DataFixtures;

use App\DataFixtures\CouponFixture;
use App\DataFixtures\ProductFixture;
use App\Entity\Coupon;
use App\Entity\Order;
use App\Entity\Product;
use App\Service\Order as OrderService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixture extends Fixture implements DependentFixtureInterface
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function load(ObjectManager $manager)
    {
        $products = $manager->getRepository(Product::class)->findBy([
            'code' => ['Camisa', 'Traje'],
        ]);
        $coupon = null;
        $order = $this->orderService->create($products, $coupon);


        $products = $manager->getRepository(Product::class)->findBy([
            'code' => ['Camisa', 'Traje'],
        ]);
        $coupon = $manager->getRepository(Coupon::class)->findOneBy([
            'code' => 'TEST_FIJO',
        ]);
        $order = $this->orderService->create($products, $coupon);


        $products = $manager->getRepository(Product::class)->findBy([
            'code' => ['Camisa', 'Traje'],
        ]);
        $coupon = $manager->getRepository(Coupon::class)->findOneBy([
            'code' => 'TEST_20',
        ]);
        $order = $this->orderService->create($products, $coupon);


        $products = $manager->getRepository(Product::class)->findBy([
            'code' => ['Camisa', 'Traje'],
        ]);
        $coupon = $manager->getRepository(Coupon::class)->findOneBy([
            'code' => 'TEST_FIJO_MIN',
        ]);
        $order = $this->orderService->create($products, $coupon);


        $products = $manager->getRepository(Product::class)->findBy([
            'code' => ['Camisa_premium', 'Traje'],
        ]);
        $coupon = $manager->getRepository(Coupon::class)->findOneBy([
            'code' => 'TEST_FIJO_MIN',
        ]);
        $order = $this->orderService->create($products, $coupon);
    }

    public function getDependencies()
    {
        return [
            CouponFixture::class,
            ProductFixture::class,
        ];
    }
}
