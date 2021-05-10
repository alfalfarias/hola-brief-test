<?php

namespace App\Tests\Controller\Api\CouponController;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class CreateFixedTypeTest extends ApiTestCase
{
	public function testCreateWithDuplicateCode(): void
	{
		$client = static::createClient();
		$request = $client->request('POST', '/api/coupon/coupons', [
			'json' => [
				'coupon' => [
					'code' => 'TEST_FIJO',
					'type' => 'PRICE_FIXED',
					'value' => 10
				],
				'rules' => []
			]
		]);

		$this->assertEquals(422, $client->getResponse()->getStatusCode());
		$this->assertJsonContains([
			'code' => [
				'El código ya existe'
			],
		]);
	}

	public function testPriceFixedCreate(): void
	{
		$client = static::createClient();
		$request = $client->request('POST', '/api/coupon/coupons', [
			'json' => [
				'coupon' => [
					'code' => 'C-001',
					'type' => 'PRICE_FIXED',
					'value' => 10
				],
				'rules' => [],
			]
		]);

		$this->assertResponseIsSuccessful();
		$this->assertJsonContains([
			'code' => 'C-001',
			'type' => 'PRICE_FIXED',
			'value' => 10,
			'rules' => [],
		]);
	}

	public function testPriceFixedWithMinRuleCreate(): void
	{
		$client = static::createClient();
		$request = $client->request('POST', '/api/coupon/coupons', [
			'json' => [
				'coupon' => [
					'code' => 'C-001',
					'type' => 'PRICE_FIXED',
					'value' => 15
				],
				'rules' => [
					[
						'type' => 'PRICE_MIN',
						'value' => 23
					],
				],
			]
		]);

		$this->assertResponseIsSuccessful();
		$this->assertJsonContains([
			'code' => 'C-001',
			'type' => 'PRICE_FIXED',
			'value' => 15,
			'rules' => [
				[
					'type' => 'PRICE_MIN',
					'value' => 23
				],
			],
		]);
	}

	public function testPricePercentCreate(): void
	{
		$client = static::createClient();
		$request = $client->request('POST', '/api/coupon/coupons', [
			'json' => [
				'coupon' => [
					'code' => 'C-001',
					'type' => 'PRICE_PERCENT',
					'value' => 37
				],
				'rules' => [],
			]
		]);

		$this->assertResponseIsSuccessful();
		$this->assertJsonContains([
			'code' => 'C-001',
			'type' => 'PRICE_PERCENT',
			'value' => 37,
			'rules' => [],
		]);
	}
}