<?php

namespace App\Tests\Controller\Api\ProductController;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class CreateTest extends ApiTestCase
{
	public function testCreateWithDuplicateCode(): void
	{
		$client = static::createClient();
		$request = $client->request('POST', '/api/product/products', [
			'json' => [
				'code' => 'Traje',
				'price' => 12,
			]
		]);

		$this->assertEquals(422, $client->getResponse()->getStatusCode());
		$this->assertJsonContains([
			'code' => [
				'El código ya existe'
			],
		]);
	}
	
	public function testCreate(): void
	{
		$client = static::createClient();
		$request = $client->request('POST', '/api/product/products', [
			'json' => [
				'code' => 'P-001',
				'price' => 1019.57,
			]
		]);

		$this->assertResponseIsSuccessful();
		$this->assertJsonContains([
			'code' => 'P-001',
			'price' => 1019.57,
		]);
	}
}