<?php

namespace App\Tests\Controller\Api\CouponController;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class IndexTest extends ApiTestCase
{
	public function testIndex(): void
	{
		$client = static::createClient([]);
		$request = $client->request('GET', '/api/coupon/coupons');

		$this->assertResponseIsSuccessful();
	}
}
