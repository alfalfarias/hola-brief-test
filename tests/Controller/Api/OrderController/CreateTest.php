<?php

namespace App\Tests\Controller\Api\OrderController;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class CreateTest extends ApiTestCase
{
	public function testCreateWithoutCoupon(): void
	{
		$client = static::createClient();
		$request = $client->request('POST', '/api/order/orders', [
			'json' => [
				'products' => [
					[
						'code' => 'Camisa'
					],
					[
						'code' => 'Traje'
					]
				],
				'coupon' => null
			],
		]);

		$this->assertResponseIsSuccessful();
		$this->assertJsonContains([
			'price' => 15,
			'discount' => 0,
			'total' => 15
		]);
	}

	public function testCreateWithDiscountFixedCoupon(): void
	{
		$client = static::createClient();
		$request = $client->request('POST', '/api/order/orders', [
			'json' => [
				'products' => [
					[
						'code' => 'Camisa'
					],
					[
						'code' => 'Traje'
					]
				],
				'coupon' => [
					'code' => 'TEST_FIJO',
				],
			],
		]);

		$this->assertResponseIsSuccessful();
		$this->assertJsonContains([
			'price' => 15,
			'discount' => 10,
			'total' => 5
		]);
	}

	public function testCreateWithDiscountPercentCoupon(): void
	{
		$client = static::createClient();
		$request = $client->request('POST', '/api/order/orders', [
			'json' => [
				'products' => [
					[
						'code' => 'Camisa'
					],
					[
						'code' => 'Traje'
					]
				],
				'coupon' => [
					'code' => 'TEST_20',
				],
			],
		]);

		$this->assertResponseIsSuccessful();
		$this->assertJsonContains([
			'price' => 15,
			'discount' => 3,
			'total' => 12,
		]);
	}

	public function testCreateWithCouponAndMinRestrictionInvalid(): void
	{
		$client = static::createClient();
		$request = $client->request('POST', '/api/order/orders', [
			'json' => [
				'products' => [
					[
						'code' => 'Camisa'
					],
					[
						'code' => 'Traje'
					]
				],
				'coupon' => [
					'code' => 'TEST_FIJO_MIN',
				],
			],
		]);

		$this->assertResponseIsSuccessful();
		$this->assertJsonContains([
			'price' => 15,
			'discount' => 0,
			'total' => 15,
		]);
	}

	public function testCreateWithCouponAndMinRestriction(): void
	{
		$client = static::createClient();
		$request = $client->request('POST', '/api/order/orders', [
			'json' => [
				'products' => [
					[
						'code' => 'Camisa_premium'
					],
					[
						'code' => 'Traje'
					]
				],
				'coupon' => [
					'code' => 'TEST_FIJO_MIN',
				],
			],
		]);

		$this->assertResponseIsSuccessful();
		$this->assertJsonContains([
			'price' => 22,
			'discount' => 10,
			'total' => 12,
		]);
	}
}
