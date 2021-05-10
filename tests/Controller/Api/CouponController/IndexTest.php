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
		$this->assertJsonContains([
			[
				'code' => 'TEST_20',
				'type' => 'PRICE_PERCENT',
				'value' => 20,
				'rules' => []
			],
			[
				'code' => 'TEST_FIJO_MIN',
				'type' => 'PRICE_FIXED',
				'value' => 10,
				'rules' => [
					[
						'type' => 'PRICE_MIN',
						'value' => 20
					]
				]
			],
			[
				'code' => 'TEST_FIJO',
				'type' => 'PRICE_FIXED',
				'value' => 10,
				'rules' => []
			]
		]);
	}
}
