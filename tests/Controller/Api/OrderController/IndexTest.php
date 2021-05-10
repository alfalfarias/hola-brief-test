<?php

namespace App\Tests\Controller\Api\OrderController;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class IndexTest extends ApiTestCase
{
	public function testIndex(): void
	{
		$response = static::createClient()->request('GET', '/api/order/orders');

		$this->assertResponseIsSuccessful();
		$this->assertJsonContains([
			[
				'price' => 22,
				'discount' => 10,
				'total' => 12,
				'products' => [
					[
						'code' => 'Camisa_premium',
						'price' => 10
					],
					[
						'code' => 'Traje',
						'price' => 12
					]
				],
				'coupon' => [
					'code' => 'TEST_FIJO_MIN',
					'type' => 'PRICE_FIXED',
					'value' => 10,
					'rules' => [
						[
							'type' => 'PRICE_MIN',
							'value' => 20
						]
					]
				]
			],
			[
				'price' => 15,
				'discount' => 0,
				'total' => 15,
				'products' => [
					[
						'code' => 'Camisa',
						'price' => 3
					],
					[
						'code' => 'Traje',
						'price' => 12
					]
				],
				'coupon' => [
					'code' => 'TEST_FIJO_MIN',
					'type' => 'PRICE_FIXED',
					'value' => 10,
					'rules' => [
						[
							'type' => 'PRICE_MIN',
							'value' => 20
						]
					]
				]
			],
			[
				'price' => 15,
				'discount' => 3,
				'total' => 12,
				'products' => [
					[
						'code' => 'Camisa',
						'price' => 3
					],
					[
						'code' => 'Traje',
						'price' => 12
					]
				],
				'coupon' => [
					'code' => 'TEST_20',
					'type' => 'PRICE_PERCENT',
					'value' => 20,
					'rules' => []
				]
			],
			[
				'price' => 15,
				'discount' => 10,
				'total' => 5,
				'products' => [
					[
						'code' => 'Camisa',
						'price' => 3
					],
					[
						'code' => 'Traje',
						'price' => 12
					]
				],
				'coupon' => [
					'code' => 'TEST_FIJO',
					'type' => 'PRICE_FIXED',
					'value' => 10,
					'rules' => []
				]
			],
			[
				'price' => 15,
				'discount' => 0,
				'total' => 15,
				'products' => [
					[
						'code' => 'Camisa',
						'price' => 3
					],
					[
						'code' => 'Traje',
						'price' => 12
					]
				],
				'coupon' => null
			]
		]);
	}
}
