<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\CouponRule as Rule;
use App\Service\Coupon as CouponService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponFixture extends Fixture
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function load(ObjectManager $manager)
    {
        $coupon = new Coupon();
        $coupon->setCode('TEST_FIJO');
        $coupon->setType(Coupon::TYPE['PRICE_FIXED']);
        $coupon->setValue(10);
        $rules = [];
        $this->couponService->create($coupon, $rules);

        $coupon = new Coupon();
        $coupon->setCode('TEST_FIJO_MIN');
        $coupon->setType(Coupon::TYPE['PRICE_FIXED']);
        $coupon->setValue(10);
        $rule_min = new Rule();
        $rule_min->setType(Rule::TYPE['PRICE_MIN']);
        $rule_min->setValue(20);
        $rules = [$rule_min];
        $this->couponService->create($coupon, $rules);

        $coupon = new Coupon();
        $coupon->setCode('TEST_20');
        $coupon->setType(Coupon::TYPE['PRICE_PERCENT']);
        $coupon->setValue(20);
        $rules = [];
        $this->couponService->create($coupon, $rules);
    }
}